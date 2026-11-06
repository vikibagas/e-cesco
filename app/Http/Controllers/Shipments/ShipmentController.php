<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ShipmentController extends Controller
{
    /** @var array<int, string> */
    private const STATUSES = [1 => 'Booking', 2 => 'Pickup', 3 => 'Outbound', 4 => 'Inbound', 5 => 'Delivery', 6 => 'Delivered', 7 => 'Cancelled', 8 => 'Returned'];

    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $filter = in_array($request->query('filter'), ['processing', 'transit', 'delivered', 'attention'], true) ? $request->query('filter') : 'all';
        $baseQuery = $this->shipmentQuery();
        $query = clone $baseQuery;

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('p.airwaybill_kode', 'like', "%{$search}%")
                    ->orWhere('k.nama_kustomer', 'like', "%{$search}%")
                    ->orWhere('p.kota_asal', 'like', "%{$search}%")
                    ->orWhere('p.kota_tujuan', 'like', "%{$search}%")
                    ->orWhere('pelayanan.nama_pelayanan', 'like', "%{$search}%");
            });
        }

        if ($filter === 'processing') {
            $query->where('p.status', 1);
        } elseif ($filter === 'transit') {
            $query->whereIn('p.status', [2, 3, 4, 5]);
        } elseif ($filter === 'delivered') {
            $query->where('p.status', 6);
        } elseif ($filter === 'attention') {
            $query->where(function (Builder $query): void {
                $query->where('p.terlambat_pickup', 1)->orWhere('p.terlambat_delivery', 1);
            });
        }

        $shipments = $query->orderByDesc('p.id_pengiriman')->paginate(15)->withQueryString()->through(fn (object $shipment): array => [
            'id' => (int) $shipment->id_pengiriman,
            'awb' => $shipment->airwaybill_kode,
            'customer' => $shipment->customer_name,
            'origin' => $shipment->origin,
            'destination' => $shipment->destination,
            'service' => $shipment->service_name,
            'status' => (int) $shipment->status,
            'statusLabel' => self::STATUSES[(int) $shipment->status] ?? 'Unknown',
            'delayedPickup' => (int) $shipment->terlambat_pickup === 1,
            'delayedDelivery' => (int) $shipment->terlambat_delivery === 1,
            'pickupDate' => $shipment->pickup_date,
            'pickupBy' => $shipment->pickup_by_name,
            'deliveryDate' => $shipment->delivery_date,
            'deliveryBy' => $shipment->delivery_by_name,
            'recipient' => $shipment->recipient,
        ]);

        return Inertia::render('Operations/Shipments/Index', [
            'shipments' => $shipments,
            'filters' => ['search' => $search, 'filter' => $filter],
            'summary' => [
                'total' => (clone $baseQuery)->count(),
                'processing' => (clone $baseQuery)->where('p.status', 1)->count(),
                'transit' => (clone $baseQuery)->whereIn('p.status', [2, 3, 4, 5])->count(),
                'delivered' => (clone $baseQuery)->where('p.status', 6)->count(),
                'attention' => (clone $baseQuery)->where(function (Builder $query): void {
                    $query->where('p.terlambat_pickup', 1)->orWhere('p.terlambat_delivery', 1);
                })->count(),
            ],
        ]);
    }

    public function edit(int $shipment): Response
    {
        $record = DB::table('pengiriman')->where('id_pengiriman', $shipment)->first();
        abort_if($record === null, 404);

        return Inertia::render('Operations/Shipments/Edit', ['shipment' => [
            'id' => (int) $record->id_pengiriman,
            'awb' => $record->airwaybill_kode,
            'sender' => $record->nama_pengirim,
            'senderCompany' => $record->perusahaan_pengirim,
            'senderAddress' => $record->alamat_pengirim,
            'senderPhone' => $record->hp_pengirim,
            'recipient' => $record->nama_penerima,
            'recipientCompany' => $record->perusahaan_penerima,
            'recipientAddress' => $record->alamat_penerima,
            'recipientAddressDetails' => $record->alamat_penerima2,
            'recipientPhone' => $record->hp_penerima,
            'goods' => $record->jenis_barang,
            'notes' => $record->catatan,
        ]]);
    }

    public function update(Request $request, int $shipment): RedirectResponse
    {
        $data = $request->validate([
            'sender' => ['nullable', 'string', 'max:100'],
            'senderCompany' => ['nullable', 'string', 'max:100'],
            'senderAddress' => ['nullable', 'string', 'max:300'],
            'senderPhone' => ['nullable', 'string', 'max:20'],
            'recipient' => ['nullable', 'string', 'max:100'],
            'recipientCompany' => ['nullable', 'string', 'max:100'],
            'recipientAddress' => ['nullable', 'string', 'max:100'],
            'recipientAddressDetails' => ['nullable', 'string', 'max:80'],
            'recipientPhone' => ['nullable', 'string', 'max:20'],
            'goods' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:100'],
        ]);
        $updated = DB::table('pengiriman')->where('id_pengiriman', $shipment)->update([
            'nama_pengirim' => $data['sender'] ?? null,
            'perusahaan_pengirim' => $data['senderCompany'] ?? null,
            'alamat_pengirim' => $data['senderAddress'] ?? null,
            'hp_pengirim' => $data['senderPhone'] ?? null,
            'nama_penerima' => $data['recipient'] ?? null,
            'perusahaan_penerima' => $data['recipientCompany'] ?? null,
            'alamat_penerima' => $data['recipientAddress'] ?? null,
            'alamat_penerima2' => $data['recipientAddressDetails'] ?? null,
            'hp_penerima' => $data['recipientPhone'] ?? null,
            'jenis_barang' => $data['goods'] ?? null,
            'catatan' => $data['notes'] ?? null,
            'updated_last' => now(),
            'updated_by' => (int) auth('web')->id(),
        ]);
        abort_if($updated === 0 && ! DB::table('pengiriman')->where('id_pengiriman', $shipment)->exists(), 404);

        return to_route('e-cesco.shipments.index')->with('success', 'Perubahan pengiriman berhasil disimpan.');
    }

    public function cancel(Request $request, int $shipment): RedirectResponse
    {
        $data = $request->validate(['cancel_reason' => ['required', 'string', 'max:100']]);
        $record = DB::table('pengiriman')->where('id_pengiriman', $shipment)->first();
        abort_if($record === null, 404);

        if (in_array((int) $record->status, [6, 7], true)) {
            return back()->with('error', 'Pengiriman yang sudah terkirim atau dibatalkan tidak dapat dibatalkan kembali.');
        }

        DB::table('pengiriman')->where('id_pengiriman', $shipment)->update([
            'status' => 7,
            'cancel_reason' => $data['cancel_reason'],
            'updated_last' => now(),
            'updated_by' => (int) auth('web')->id(),
        ]);

        return to_route('e-cesco.shipments.index')->with('success', 'Pengiriman berhasil dibatalkan.');
    }

    public function printAwb(int $shipment): View
    {
        return view('shipments.print-awb', ['shipment' => $this->printData($shipment)]);
    }

    public function printLabel(int $shipment): View
    {
        return view('shipments.print-label', ['shipment' => $this->printData($shipment)]);
    }

    private function shipmentQuery(): Builder
    {
        return DB::table('pengiriman as p')
            ->leftJoin('kustomer as k', 'k.id_kustomer', '=', 'p.id_kustomer')
            ->leftJoin('pelayanan', 'pelayanan.id_pelayanan', '=', 'p.service')
            ->leftJoin('users as pickup_user', 'pickup_user.id', '=', 'p.pickup_by')
            ->leftJoin('users as delivery_user', 'delivery_user.id', '=', 'p.delivery_by')
            ->select([
                'p.id_pengiriman', 'p.airwaybill_kode', 'p.status', 'p.terlambat_pickup',
                'p.terlambat_delivery', 'p.pickup_date', 'p.delivery_date',
                'k.nama_kustomer as customer_name', 'pelayanan.nama_pelayanan as service_name',
            ])
            ->selectRaw("COALESCE(NULLIF(p.kota_asal, ''), p.provinsi_asal, '') as origin")
            ->selectRaw("COALESCE(NULLIF(p.kota_tujuan, ''), p.provinsi_tujuan, '') as destination")
            ->selectRaw("COALESCE(NULLIF(pickup_user.namalengkap, ''), pickup_user.name, '') as pickup_by_name")
            ->selectRaw("COALESCE(NULLIF(delivery_user.namalengkap, ''), delivery_user.name, '') as delivery_by_name")
            ->selectRaw("COALESCE(NULLIF(p.delivery_name, ''), p.nama_penerima, '') as recipient");
    }

    private function printData(int $shipment): object
    {
        $record = DB::table('pengiriman as p')
            ->leftJoin('kustomer as k', 'k.id_kustomer', '=', 'p.id_kustomer')
            ->leftJoin('pelayanan', 'pelayanan.id_pelayanan', '=', 'p.service')
            ->select('p.*', 'k.nama_kustomer as customer_name', 'pelayanan.nama_pelayanan as service_name')
            ->where('p.id_pengiriman', $shipment)
            ->first();

        abort_if($record === null, 404);

        return $record;
    }
}
