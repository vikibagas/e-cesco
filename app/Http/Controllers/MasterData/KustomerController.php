<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Kustomer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class KustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $status = in_array($request->query('status'), ['Y', 'N'], true) ? $request->query('status') : '';

        $customers = Kustomer::query()
            ->leftJoin('cabang', 'kustomer.id_cabang', '=', 'cabang.id_cabang')
            ->select([
                'kustomer.id_kustomer', 'kustomer.kode_kustomer', 'kustomer.nama_kustomer',
                'kustomer.callname', 'kustomer.telp', 'kustomer.pic_utama', 'kustomer.status',
                'kustomer.cesco', 'kustomer.dhl', 'cabang.nama_cabang as branch_name',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('kustomer.kode_kustomer', 'like', "%{$search}%")
                        ->orWhere('kustomer.nama_kustomer', 'like', "%{$search}%")
                        ->orWhere('kustomer.callname', 'like', "%{$search}%")
                        ->orWhere('cabang.nama_cabang', 'like', "%{$search}%")
                        ->orWhere('kustomer.telp', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn (Builder $query) => $query->where('kustomer.status', $status))
            ->orderBy('kustomer.nama_kustomer')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Kustomer $customer): array => [
                'id' => $customer->id_kustomer,
                'code' => $customer->kode_kustomer,
                'name' => $customer->nama_kustomer,
                'callname' => $customer->callname,
                'branch' => $customer->branch_name,
                'contact' => $customer->pic_utama,
                'phone' => $customer->telp,
                'active' => $customer->status === 'Y',
                'cesco' => $customer->cesco === 'Y',
                'dhl' => $customer->dhl === 'Y',
            ]);

        return Inertia::render('Operations/MasterData/Customers/Index', [
            'customers' => $customers,
            'filters' => ['search' => $search, 'status' => $status],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operations/MasterData/Customers/Form', [
            'customer' => null,
            'branches' => $this->branches(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $customer = new Kustomer;
        $customer->forceFill($this->customerAttributes($data) + [
            'akun_utama' => $data['akun_utama'] ?? '',
            'loc_code' => 'N',
            'loc_code_pilih' => 'N',
            'pid' => 'N',
            'tagihan_ke' => null,
            'payment_discount' => 0,
            'custom_invoice' => 'N',
            'all_awb' => 'N',
            'warehouse' => 'N',
            'departement' => 'N',
            'created_date' => now(),
            'created_by' => (int) auth('web')->id(),
        ])->save();

        return to_route('e-cesco.master-data.customers.index')->with('success', 'Kustomer berhasil dibuat.');
    }

    public function edit(Kustomer $kustomer): Response
    {
        return Inertia::render('Operations/MasterData/Customers/Form', [
            'customer' => [
                'id' => $kustomer->id_kustomer,
                'inisial' => $kustomer->inisial,
                'id_cabang' => $kustomer->id_cabang,
                'kode_kustomer' => $kustomer->kode_kustomer,
                'nama_kustomer' => $kustomer->nama_kustomer,
                'callname' => $kustomer->callname,
                'alamat' => $kustomer->alamat,
                'telp' => $kustomer->telp,
                'pic_utama' => $kustomer->pic_utama,
                'status' => $kustomer->status,
                'dhl' => $kustomer->dhl ?? 'N',
                'cesco' => $kustomer->cesco ?? 'N',
                'akun_utama' => $kustomer->akun_utama,
            ],
            'branches' => $this->branches((int) $kustomer->id_cabang),
        ]);
    }

    public function update(Request $request, Kustomer $kustomer): RedirectResponse
    {
        $data = $this->validatedData($request);
        $kustomer->forceFill($this->customerAttributes($data) + [
            'akun_utama' => $data['akun_utama'] ?? '',
            'updated_last' => now(),
            'updated_by' => (int) auth('web')->id(),
        ])->save();

        return to_route('e-cesco.master-data.customers.index')->with('success', 'Perubahan kustomer berhasil disimpan.');
    }

    public function destroy(Kustomer $kustomer): RedirectResponse
    {
        if (DB::table('users_client')->where('id_kustomer', $kustomer->id_kustomer)->exists()) {
            return back()->with('error', 'Kustomer masih digunakan akun user client. Nonaktifkan kustomer jika tidak lagi dipakai.');
        }

        try {
            $kustomer->delete();
        } catch (QueryException) {
            return back()->with('error', 'Kustomer masih terhubung dengan data operasional dan tidak dapat dihapus. Nonaktifkan kustomer jika tidak lagi dipakai.');
        }

        return to_route('e-cesco.master-data.customers.index')->with('success', 'Kustomer berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'inisial' => ['nullable', 'string', 'max:3'],
            'id_cabang' => ['required', 'integer', Rule::exists('cabang', 'id_cabang')],
            'kode_kustomer' => ['required', 'string', 'size:3'],
            'nama_kustomer' => ['required', 'string', 'max:100'],
            'callname' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:300'],
            'telp' => ['nullable', 'string', 'max:100'],
            'pic_utama' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(['Y', 'N'])],
            'dhl' => ['required', Rule::in(['Y', 'N'])],
            'cesco' => ['required', Rule::in(['Y', 'N'])],
            'akun_utama' => ['nullable', Rule::in(['', 'Y'])],
        ]);
    }

    /** @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function customerAttributes(array $data): array
    {
        return [
            'inisial' => $data['inisial'] ?? null,
            'id_cabang' => $data['id_cabang'],
            'kode_kustomer' => $data['kode_kustomer'],
            'nama_kustomer' => $data['nama_kustomer'],
            'callname' => $data['callname'] ?? '',
            'alamat' => $data['alamat'] ?? null,
            'telp' => $data['telp'] ?? null,
            'pic_utama' => $data['pic_utama'] ?? null,
            'status' => $data['status'],
            'dhl' => $data['dhl'],
            'cesco' => $data['cesco'],
        ];
    }

    /** @return array<int, array{id: int, label: string}> */
    private function branches(?int $currentBranchId = null): array
    {
        return DB::table('cabang')
            ->select(['id_cabang as id', 'kode_cabang', 'nama_cabang'])
            ->where(function (QueryBuilder $query) use ($currentBranchId): void {
                $query->where('status', 'Y');
                if ($currentBranchId !== null) {
                    $query->orWhere('id_cabang', $currentBranchId);
                }
            })
            ->orderBy('nama_cabang')
            ->get()
            ->map(fn (object $branch): array => [
                'id' => (int) $branch->id,
                'label' => $branch->kode_cabang.' — '.$branch->nama_cabang,
            ])
            ->all();
    }
}
