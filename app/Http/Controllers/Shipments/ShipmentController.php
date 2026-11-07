<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShipmentController extends Controller
{
    /** @var array<int, string> */
    private const STATUSES = [1 => 'Booking', 2 => 'Pickup', 3 => 'Outbound', 4 => 'Inbound', 5 => 'Delivery', 6 => 'Delivered', 7 => 'Cancelled', 8 => 'Returned'];

    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $filter = in_array($request->query('filter'), ['processing', 'transit'], true) ? $request->query('filter') : 'all';
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }
        $query = $this->shipmentQuery();

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

        $shipments = $query->orderByDesc('p.id_pengiriman')->paginate($perPage)->withQueryString()->through(fn (object $shipment): array => [
            'id' => (int) $shipment->id_pengiriman,
            'awb' => $shipment->airwaybill_kode,
            'customer' => $shipment->customer_name,
            'customerId' => (int) $shipment->id_kustomer,
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

        $countQuery = $this->shipmentQuery();
        $counts = [
            'all' => (clone $countQuery)->count(),
            'processing' => (clone $countQuery)->where('p.status', 1)->count(),
            'transit' => (clone $countQuery)->whereIn('p.status', [2, 3, 4, 5])->count(),
        ];

        return Inertia::render('Operations/Shipments/Index', [
            'shipments' => $shipments,
            'filters' => ['search' => $search, 'filter' => $filter, 'perPage' => $perPage],
            'counts' => $counts,
        ]);
    }

    public function edit(int $shipment): Response
    {
        $this->requireStaff();
        $this->findShipment($shipment);
        $record = DB::table('pengiriman')->where('id_pengiriman', $shipment)->first();
        abort_if($record === null, 404);

        return Inertia::render('Operations/Shipments/Edit', [
            'shipment' => [
                'id' => (int) $record->id_pengiriman,
                'awb' => $record->airwaybill_kode,
                'customerId' => (int) $record->id_kustomer,
                'sender' => $record->nama_pengirim,
                'senderCompany' => $record->perusahaan_pengirim,
                'senderAddress' => $record->alamat_pengirim,
                'senderAddressDetails' => $record->alamat_pengirim2,
                'senderPhone' => $record->hp_pengirim,
                'recipient' => $record->nama_penerima,
                'recipientCompany' => $record->perusahaan_penerima,
                'recipientAddress' => $record->alamat_penerima,
                'recipientAddressDetails' => $record->alamat_penerima2,
                'recipientPhone' => $record->hp_penerima,
                'goods' => $record->jenis_barang,
                'notes' => $record->catatan,
                'service' => $record->service,
                'actualWeight' => $record->berat_aktual,
                'packages' => $record->collie,
                'commodity' => $record->komoditi,
                'dimensions' => array_pad(explode('x', (string) $record->ukuran_barang), 3, ''),
                'packing' => $record->packing_kayu,
                'insured' => $record->asuransi,
                'declaredValue' => $record->nilai_barang,
                'returnDocument' => $record->surat_jalan,
                'specialPayment' => $record->pembayaran_khusus,
                'locCode' => $record->loc_code,
                'pid' => $record->pid,
                'paymentName' => $record->nama_pembayaran,
                'paymentAddress' => $record->alamat_pembayaran,
                'paymentContact' => $record->cp_pembayaran,
                'paymentPhone' => $record->telp_pembayaran,
                'container20' => $record->{'20ft'} ?? 0,
                'container40' => $record->{'40ft'} ?? 0,
                'container40HighCube' => $record->{'40ft_hc'} ?? 0,
                'lcl' => $record->lcl ?? 0,
                'route' => trim(($record->kota_asal ?: $record->provinsi_asal).' → '.($record->kota_tujuan ?: $record->provinsi_tujuan), ' →'),
                'collies' => DB::table('detail_collie')->where('id_pengiriman', $shipment)->orderBy('koli_ke')->get([
                    'catatan', 'berat', 'p', 'l', 't', 'berat_real',
                ])->map(static fn (object $collie): array => [
                    'note' => $collie->catatan,
                    'weight' => $collie->berat,
                    'width' => $collie->p,
                    'length' => $collie->l,
                    'height' => $collie->t,
                    'actualWeight' => $collie->berat_real,
                ])->values(),
                'priceLocked' => $record->harga_kunci !== null,
            ],
            'services' => DB::table('pelayanan')->orderBy('nama_pelayanan')->get(['id_pelayanan', 'nama_pelayanan']),
            'customers' => DB::table('kustomer')->where(function (Builder $query) use ($record): void {
                $query->where('status', '!=', 'N')->orWhere('id_kustomer', $record->id_kustomer);
            })->orderBy('nama_kustomer')->get(['id_kustomer', 'nama_kustomer', 'loc_code']),
        ]);
    }

    public function update(Request $request, int $shipment): RedirectResponse
    {
        $this->requireStaff();
        $record = $this->findShipment($shipment);
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
            'service' => ['nullable', 'integer', 'exists:pelayanan,id_pelayanan'],
            'customerId' => ['required', 'integer', 'exists:kustomer,id_kustomer'],
            'actualWeight' => ['nullable', 'integer', 'min:1', 'max:36000'],
            'packages' => ['nullable', 'integer', 'min:1', 'max:250'],
            'senderAddressDetails' => ['nullable', 'string', 'max:300'],
            'commodity' => ['required', 'in:1,2'],
            'length' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'width' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'packing' => ['required', 'in:1,2,3'],
            'insured' => ['required', 'in:Y,N'],
            'declaredValue' => ['nullable', 'integer', 'min:0', 'max:2000000000'],
            'returnDocument' => ['required', 'in:Y,N'],
            'specialPayment' => ['required', 'in:0,1'],
            'locCode' => [Rule::requiredIf(DB::table('kustomer')->where('id_kustomer', $request->input('customerId'))->value('loc_code') === 'Y'), 'nullable', 'string', 'max:100'],
            'pid' => [Rule::requiredIf(in_array((int) $request->input('customerId'), [13, 29, 31], true)), 'nullable', 'string', 'max:20'],
            'paymentName' => ['nullable', 'string', 'max:100'],
            'paymentAddress' => ['nullable', 'string', 'max:200'],
            'paymentContact' => ['nullable', 'string', 'max:100'],
            'paymentPhone' => ['nullable', 'string', 'max:20'],
            'container20' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'container40' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'container40HighCube' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'lcl' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'collies' => ['nullable', 'array', 'max:250'],
            'collies.*.note' => ['nullable', 'string', 'max:275'],
            'collies.*.weight' => ['required_with:collies', 'integer', 'min:1', 'max:450'],
            'collies.*.width' => ['required_with:collies', 'integer', 'min:1', 'max:450'],
            'collies.*.length' => ['required_with:collies', 'integer', 'min:1', 'max:450'],
            'collies.*.height' => ['required_with:collies', 'integer', 'min:1', 'max:450'],
            'collies.*.actualWeight' => ['required_with:collies', 'numeric', 'min:0.01', 'max:450'],
        ]);
        $lockedFieldsChanged = (isset($data['service']) && (int) $data['service'] !== (int) $record->service)
            || (isset($data['customerId']) && (int) $data['customerId'] !== (int) $record->id_kustomer)
            || (isset($data['actualWeight']) && (float) $data['actualWeight'] !== (float) $record->berat_aktual)
            || (isset($data['packages']) && (int) $data['packages'] !== (int) $record->collie);
        $collies = $data['collies'] ?? [];
        $existingCollies = DB::table('detail_collie')->where('id_pengiriman', $shipment)->orderBy('koli_ke')->get();
        $colliesChanged = $existingCollies->count() !== count($collies)
            || ($collies !== [] && $existingCollies->contains(fn (object $collie, int $index): bool => (int) $collie->berat !== (int) ($collies[$index]['weight'] ?? 0)
                || (int) $collie->p !== (int) ($collies[$index]['width'] ?? 0)
                || (int) $collie->l !== (int) ($collies[$index]['length'] ?? 0)
                || (int) $collie->t !== (int) ($collies[$index]['height'] ?? 0)
                || (float) $collie->berat_real !== (float) ($collies[$index]['actualWeight'] ?? 0)
            ));
        if ($record->harga_kunci !== null && ($lockedFieldsChanged || $colliesChanged)) {
            return back()->with('error', 'Pelanggan, layanan, berat, dan koli terkunci setelah proses penagihan.');
        }
        if ($collies !== [] && count($collies) !== (int) ($data['packages'] ?? $record->collie)) {
            return back()->withErrors(['packages' => 'Jumlah detail koli harus sama dengan jumlah koli.'])->withInput();
        }
        $sizeParts = array_map(static fn (string $key): string => trim((string) ($data[$key] ?? '')), ['length', 'width', 'height']);
        $size = count(array_filter($sizeParts, static fn (string $part): bool => $part !== '')) === 3 ? implode('x', $sizeParts) : '';
        if ($collies !== []) {
            $size = implode('x', [$collies[0]['length'], $collies[0]['width'], $collies[0]['height']]);
        }
        $shipmentColumns = array_flip(Schema::getColumnListing('pengiriman'));
        $updates = [
            'nama_pengirim' => $data['sender'] ?? null,
            'perusahaan_pengirim' => $data['senderCompany'] ?? null,
            'alamat_pengirim' => $data['senderAddress'] ?? null,
            'alamat_pengirim2' => $data['senderAddressDetails'] ?? null,
            'hp_pengirim' => $data['senderPhone'] ?? null,
            'nama_penerima' => $data['recipient'] ?? null,
            'id_kustomer' => $data['customerId'],
            'perusahaan_penerima' => $data['recipientCompany'] ?? null,
            'alamat_penerima' => $data['recipientAddress'] ?? null,
            'alamat_penerima2' => $data['recipientAddressDetails'] ?? null,
            'hp_penerima' => $data['recipientPhone'] ?? null,
            'jenis_barang' => $data['goods'] ?? null,
            'catatan' => $data['notes'] ?? null,
            'service' => $data['service'] ?? $record->service,
            'berat_aktual' => $data['actualWeight'] ?? $record->berat_aktual,
            'collie' => $data['packages'] ?? $record->collie,
            'komoditi' => $data['commodity'],
            'ukuran_barang' => $size,
            'packing_kayu' => $data['packing'],
            'asuransi' => $data['insured'],
            'nilai_barang' => $data['insured'] === 'Y' ? ($data['declaredValue'] ?? 0) : 0,
            'surat_jalan' => $data['returnDocument'],
            'pembayaran_khusus' => $data['specialPayment'],
            'loc_code' => $data['locCode'] ?? null,
            'pid' => $data['pid'] ?? null,
            'nama_pembayaran' => $data['paymentName'] ?? null,
            'alamat_pembayaran' => $data['paymentAddress'] ?? null,
            'cp_pembayaran' => $data['paymentContact'] ?? null,
            'telp_pembayaran' => $data['paymentPhone'] ?? null,
            '20ft' => $data['container20'] ?? 0,
            '40ft' => $data['container40'] ?? 0,
            '40ft_hc' => $data['container40HighCube'] ?? 0,
            'lcl' => $data['lcl'] ?? 0,
            'updated_last' => now(),
            'updated_by' => (int) auth('web')->id(),
        ];
        $updates = array_intersect_key($updates, $shipmentColumns);

        DB::transaction(function () use ($shipment, $collies, $updates): void {
            DB::table('pengiriman')->where('id_pengiriman', $shipment)->update($updates);

            if ($collies !== []) {
                DB::table('detail_collie')->where('id_pengiriman', $shipment)->delete();
                foreach ($collies as $index => $collie) {
                    DB::table('detail_collie')->insert([
                        'id_pengiriman' => $shipment,
                        'koli_ke' => $index + 1,
                        'catatan' => $collie['note'] ?? null,
                        'berat' => $collie['weight'],
                        'p' => $collie['width'],
                        'l' => $collie['length'],
                        't' => $collie['height'],
                        'berat_real' => $collie['actualWeight'],
                    ]);
                }
            }
        });

        return to_route('e-cesco.shipments.index')->with('success', 'Perubahan pengiriman berhasil disimpan.');
    }

    public function cancel(Request $request, int $shipment): RedirectResponse
    {
        $this->requireStaff();
        $data = $request->validate(['cancel_reason' => ['required', 'string', 'max:100']]);
        $record = $this->findShipment($shipment);

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

    public function printInspection(int $shipment): View
    {
        return view('shipments.print-inspection', ['shipment' => $this->printData($shipment)]);
    }

    public function printAsus(int $shipment): View
    {
        $record = $this->printData($shipment);
        abort_unless((int) $record->id_kustomer === 106, 404);

        return view('shipments.print-asus', [
            'shipment' => $record,
            'items' => DB::table('asus_detail_catatan')->where('id_pengiriman', $shipment)->orderBy('id_catatan')->get(),
        ]);
    }

    public function status(int $shipment): JsonResponse
    {
        $accessRecord = $this->findShipment($shipment);
        $record = $this->shipmentQuery()->where('p.id_pengiriman', $shipment)->first();
        abort_if($record === null, 404);
        $events = [];
        $this->addEvent($events, $record->created_date, 'Booking', $record->booking_by_name);
        $this->addEvent($events, $record->pickup_date, 'Pickup', $record->pickup_by_name);
        $this->addEvent($events, $record->manifest_date, 'Outbound', $record->outbound_by_name);
        $this->addEvent($events, $record->manifest_received, 'Inbound / Transit received', $record->inbound_by_name);
        $this->addEvent($events, $record->courier_date, 'Delivery process', $record->courier_by_name);
        $this->addEvent($events, $record->delivery_date, 'Delivered', $record->delivery_by_name);
        if ((int) $record->status === 7) {
            $events[] = ['date' => $record->updated_last, 'status' => 'Cancelled', 'by' => '', 'note' => $record->cancel_reason];
        }

        return response()->json([
            'shipment' => [
                'id' => (int) $record->id_pengiriman,
                'awb' => $record->airwaybill_kode,
                'status' => (int) $record->status,
                'delivered' => (int) $record->status === 6,
                'recipient' => $record->recipient,
                'deliveryDate' => $record->delivery_date,
                'deliverySignature' => $accessRecord->delivery_sign,
                'photoDeliveryVerified' => $accessRecord->photo_delivery_sesuai === 'Y',
                'photoUrl' => (int) $record->status === 6 && is_file($this->deliveryPhotoPath($record->airwaybill_kode))
                    ? route('e-cesco.shipments.delivery-photo', $shipment)
                    : null,
            ],
            'events' => $events,
        ]);
    }

    public function updatePickupDate(Request $request, int $shipment): RedirectResponse
    {
        $this->requireStaff();
        $this->findShipment($shipment);
        $data = $request->validate(['pickup_date' => ['nullable', 'date']]);
        DB::table('pengiriman')->where('id_pengiriman', $shipment)->update([
            'pickup_date' => $data['pickup_date'] ?? null,
            'updated_last' => now(),
            'updated_by' => (int) auth('web')->id(),
        ]);

        return back()->with('success', 'Tanggal pickup berhasil diperbarui.');
    }

    public function deliveryPhoto(int $shipment): BinaryFileResponse
    {
        $record = $this->findShipment($shipment);
        abort_unless((int) $record->status === 6, 404);
        $path = $this->deliveryPhotoPath($record->airwaybill_kode);
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'private, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function deliveryPhotoPath(string $awb): string
    {
        abort_unless(preg_match('/^[A-Za-z0-9_-]{1,12}$/', $awb) === 1, 404);

        return dirname(base_path()).DIRECTORY_SEPARATOR.'cesco-logisticscom'.DIRECTORY_SEPARATOR
            .'e-cesco'.DIRECTORY_SEPARATOR.'reportsnew'.DIRECTORY_SEPARATOR.'delivery_photo'.DIRECTORY_SEPARATOR.$awb.'.jpg';
    }

    public function import(Request $request): RedirectResponse
    {
        $this->requireStaff();
        $data = $request->validate([
            'type' => ['required', 'in:recipients,weights,services,asus'],
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);
        $file = $data['file'];
        $handle = fopen($file->getRealPath(), 'rb');
        abort_if($handle === false, 422, 'File CSV tidak dapat dibaca.');
        fgetcsv($handle); // Header row.
        $succeeded = 0;
        $failed = 0;
        $rowNumber = 1;
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                $rowNumber++;
                if (count($row) === 1 && trim((string) $row[0]) === '') {
                    continue;
                }
                if ($rowNumber > 1001) {
                    $failed++;
                    break;
                }
                try {
                    $this->applyImportRow($data['type'], $row);
                    $succeeded++;
                } catch (\Throwable) {
                    $failed++;
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        } finally {
            fclose($handle);
        }

        return back()->with('success', "Import selesai: {$succeeded} baris berhasil, {$failed} baris dilewati.");
    }

    /** @param array<int, mixed> $row */
    private function applyImportRow(string $type, array $row): void
    {
        $awb = trim((string) ($type === 'asus' ? ($row[11] ?? '') : ($row[0] ?? '')));
        abort_if($awb === '' || mb_strlen($awb) > 12, 422);
        $shipmentQuery = DB::table('pengiriman as p');
        $this->applyAccessScope($shipmentQuery);
        $shipment = $shipmentQuery->where('p.airwaybill_kode', $awb)->first();
        abort_if($shipment === null, 422);

        if ($type === 'recipients') {
            $recipient = trim((string) ($row[1] ?? ''));
            $date = trim((string) ($row[2] ?? ''));
            abort_if($recipient === '' || mb_strlen($recipient) > 100 || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || ! checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4)), 422);
            DB::table('pengiriman')->where('id_pengiriman', $shipment->id_pengiriman)->update([
                'delivery_name' => $recipient, 'delivery_date' => $date, 'courier_date' => $date,
                'status' => 6, 'delivery' => 'Y', 'updated_last' => now(), 'updated_by' => (int) auth('web')->id(),
            ]);

            return;
        }

        if ($type === 'weights' || $type === 'services') {
            abort_if($shipment->harga_kunci !== null, 422);
            if ($type === 'weights') {
                $weight = filter_var($row[1] ?? null, FILTER_VALIDATE_INT);
                abort_if($weight === false || $weight <= 0 || $weight > 450, 422);
                DB::table('pengiriman')->where('id_pengiriman', $shipment->id_pengiriman)->update(['berat_aktual' => $weight, 'updated_last' => now(), 'updated_by' => (int) auth('web')->id()]);
            } else {
                $service = filter_var($row[1] ?? null, FILTER_VALIDATE_INT);
                abort_if($service === false || ! DB::table('pelayanan')->where('id_pelayanan', $service)->exists(), 422);
                DB::table('pengiriman')->where('id_pengiriman', $shipment->id_pengiriman)->update(['service' => $service, 'updated_last' => now(), 'updated_by' => (int) auth('web')->id()]);
            }

            return;
        }

        $columns = ['product', 'aprc', 'part_n', 'part_n_desc', 'rma_no', 'qty', 'remark', 'width', 'height', 'length', 'weight'];
        $item = array_combine($columns, array_slice(array_pad($row, 12, ''), 0, 11));
        foreach (['product' => 25, 'aprc' => 40, 'part_n' => 25, 'part_n_desc' => 35, 'rma_no' => 15, 'remark' => 25] as $column => $limit) {
            abort_if(mb_strlen(trim((string) $item[$column])) > $limit, 422);
        }
        $qty = filter_var($item['qty'], FILTER_VALIDATE_INT);
        abort_if($qty === false || $qty < 1 || $qty > 999, 422);
        $dimensions = [];
        foreach (['width', 'height', 'length', 'weight'] as $dimension) {
            $value = filter_var($item[$dimension], FILTER_VALIDATE_INT);
            abort_if($value === false || $value < 1 || $value > 999, 422);
            $dimensions[$dimension] = $value;
        }
        DB::table('asus_detail_catatan')->insert([
            'id_pengiriman' => $shipment->id_pengiriman,
            'product' => trim((string) $item['product']), 'aprc' => trim((string) $item['aprc']),
            'part_n' => trim((string) $item['part_n']), 'part_n_desc' => trim((string) $item['part_n_desc']),
            'rma_no' => trim((string) $item['rma_no']), 'qty' => $qty, 'remark' => trim((string) $item['remark']),
            ...$dimensions,
        ]);
    }

    private function findShipment(int $shipment): object
    {
        $query = DB::table('pengiriman as p');
        $this->applyAccessScope($query);
        $query->where('p.id_pengiriman', $shipment);
        $record = $query->first();
        abort_if($record === null, 404);

        return $record;
    }

    private function requireStaff(): void
    {
        abort_unless(request()->session()->get('portal_guard') === 'web', 403);
    }

    /** @param array<int, array{date: mixed, status: string, by: string|null, note?: string|null}> $events */
    private function addEvent(array &$events, mixed $date, string $status, ?string $by): void
    {
        if ($date !== null && $date !== '') {
            $events[] = ['date' => $date, 'status' => $status, 'by' => $by];
        }
    }

    private function shipmentQuery(): Builder
    {
        $query = DB::table('pengiriman as p');
        $this->applyAccessScope($query);

        return $query
            ->leftJoin('kustomer as k', 'k.id_kustomer', '=', 'p.id_kustomer')
            ->leftJoin('pelayanan', 'pelayanan.id_pelayanan', '=', 'p.service')
            ->leftJoin('users as pickup_user', 'pickup_user.id', '=', 'p.pickup_by')
            ->leftJoin('users as delivery_user', 'delivery_user.id', '=', 'p.delivery_by')
            ->select([
                'p.id_pengiriman', 'p.airwaybill_kode', 'p.status', 'p.terlambat_pickup',
                'p.terlambat_delivery', 'p.pickup_date', 'p.delivery_date',
                'p.created_date', 'p.manifest_date', 'p.manifest_received', 'p.courier_date', 'p.id_kustomer',
                'p.cancel_reason', 'p.updated_last',
                'k.nama_kustomer as customer_name', 'pelayanan.nama_pelayanan as service_name',
            ])
            ->leftJoin('users as booking_user', 'booking_user.id', '=', 'p.created_by')
            ->leftJoin('users as outbound_user', 'outbound_user.id', '=', 'p.manifest_by')
            ->leftJoin('users as inbound_user', 'inbound_user.id', '=', 'p.received_transit_by')
            ->leftJoin('users as courier_user', 'courier_user.id', '=', 'p.courier_by')
            ->selectRaw("COALESCE(NULLIF(p.kota_asal, ''), p.provinsi_asal, '') as origin")
            ->selectRaw("COALESCE(NULLIF(p.kota_tujuan, ''), p.provinsi_tujuan, '') as destination")
            ->selectRaw("COALESCE(NULLIF(pickup_user.namalengkap, ''), pickup_user.name, '') as pickup_by_name")
            ->selectRaw("COALESCE(NULLIF(delivery_user.namalengkap, ''), delivery_user.name, '') as delivery_by_name")
            ->selectRaw("COALESCE(NULLIF(booking_user.namalengkap, ''), booking_user.name, '') as booking_by_name")
            ->selectRaw("COALESCE(NULLIF(outbound_user.namalengkap, ''), outbound_user.name, '') as outbound_by_name")
            ->selectRaw("COALESCE(NULLIF(inbound_user.namalengkap, ''), inbound_user.name, '') as inbound_by_name")
            ->selectRaw("COALESCE(NULLIF(courier_user.namalengkap, ''), courier_user.name, '') as courier_by_name")
            ->selectRaw("COALESCE(NULLIF(p.delivery_name, ''), p.nama_penerima, '') as recipient");
    }

    private function applyAccessScope(Builder $query): void
    {
        if (request()->session()->get('portal_guard') === 'clients') {
            $query->where('p.id_kustomer', (int) (Auth::guard('clients')->user()->id_kustomer ?? 0));

            return;
        }

        $user = Auth::guard('web')->user();
        abort_unless($user !== null, 403);
        $query->where('p.id_kustomer', '!=', 0);

        if ($user->level === 'domex') {
            $query->where('p.tanggal', '>', now()->subMonths(5)->toDateString());
            if ((int) $user->id_kustomer > 0) {
                $query->where('p.id_kustomer', (int) $user->id_kustomer);
            }
        } elseif ($user->level === 'agent') {
            $branchId = (int) $user->id_cabang;
            $query->where('p.tanggal', '>', now()->subMonths(5)->toDateString())
                ->where(function (Builder $scope) use ($branchId): void {
                    $scope->where('p.pickup_by_agent', $branchId)
                        ->orWhere('p.delivery_by_agent', $branchId)
                        ->orWhereExists(function (Builder $manifest) use ($branchId): void {
                            $manifest->selectRaw('1')->from('manifest_detail as md')
                                ->join('manifest as m', 'm.id_manifest', '=', 'md.id_manifest')
                                ->whereColumn('md.id_pengiriman', 'p.id_pengiriman')
                                ->where(function (Builder $branch) use ($branchId): void {
                                    $branch->where('m.manifest_to', $branchId)->orWhere('m.manifest_from', $branchId);
                                });
                        });
                });
        } elseif ($user->level === 'kurir') {
            $query->where(function (Builder $scope) use ($user): void {
                $scope->where('p.pickup_by', (int) $user->id)->orWhere('p.courier_by', (int) $user->id);
            });
        } else {
            $query->where('p.tanggal', '>', now()->subMonths(8)->toDateString());
        }
    }

    private function printData(int $shipment): object
    {
        $this->findShipment($shipment);
        $query = DB::table('pengiriman as p');
        $this->applyAccessScope($query);
        $record = $query
            ->leftJoin('kustomer as k', 'k.id_kustomer', '=', 'p.id_kustomer')
            ->leftJoin('pelayanan', 'pelayanan.id_pelayanan', '=', 'p.service')
            ->select('p.*', 'k.nama_kustomer as customer_name', 'pelayanan.nama_pelayanan as service_name')
            ->where('p.id_pengiriman', $shipment)
            ->first();

        abort_if($record === null, 404);

        return $record;
    }
}
