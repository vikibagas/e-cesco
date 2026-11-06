<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CabangController extends Controller
{
    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $status = in_array($request->query('status'), ['Y', 'N'], true) ? $request->query('status') : '';

        $branches = Cabang::query()
            ->select(['id_cabang', 'kode_cabang', 'nama_cabang', 'kota_text', 'provinsi_text', 'alamat1', 'pic', 'telp', 'email', 'status', 'cabang_cesco'])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('kode_cabang', 'like', "%{$search}%")
                        ->orWhere('nama_cabang', 'like', "%{$search}%")
                        ->orWhere('kota_text', 'like', "%{$search}%")
                        ->orWhere('provinsi_text', 'like', "%{$search}%")
                        ->orWhere('pic', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn (Builder $query) => $query->where('status', $status))
            ->orderBy('nama_cabang')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Cabang $cabang): array => [
                'id' => $cabang->id_cabang,
                'code' => $cabang->kode_cabang,
                'name' => $cabang->nama_cabang,
                'city' => $cabang->kota_text,
                'province' => $cabang->provinsi_text,
                'address' => $cabang->alamat1,
                'contact' => $cabang->pic,
                'phone' => $cabang->telp,
                'email' => $cabang->email,
                'active' => $cabang->status === 'Y',
                'cesco' => $cabang->cabang_cesco === 'Y',
            ]);

        return Inertia::render('Operations/MasterData/Branches/Index', [
            'branches' => $branches,
            'filters' => ['search' => $search, 'status' => $status],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operations/MasterData/Branches/Form', ['branch' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['created_date'] = now();
        $data['created_by'] = (int) auth('web')->id();
        $data['tampil_front_end'] = 'N';

        Cabang::query()->create($data);

        return to_route('e-cesco.master-data.branches.index')->with('success', 'Cabang berhasil dibuat.');
    }

    public function edit(Cabang $cabang): Response
    {
        return Inertia::render('Operations/MasterData/Branches/Form', [
            'branch' => [
                'id' => $cabang->id_cabang,
                'code' => $cabang->kode_cabang,
                'name' => $cabang->nama_cabang,
                'province' => $cabang->provinsi_text,
                'city' => $cabang->kota_text,
                'address' => $cabang->alamat1,
                'addressDetails' => $cabang->alamat2,
                'contact' => $cabang->pic,
                'phone' => $cabang->telp,
                'email' => $cabang->email,
                'maps' => $cabang->maps,
                'googleMaps' => $cabang->google_maps,
                'cesco' => $cabang->cabang_cesco,
                'active' => $cabang->status,
            ],
        ]);
    }

    public function update(Request $request, Cabang $cabang): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['updated_last'] = now();
        $data['updated_by'] = (int) auth('web')->id();

        $cabang->fill($data)->save();

        return to_route('e-cesco.master-data.branches.index')->with('success', 'Perubahan cabang berhasil disimpan.');
    }

    public function destroy(Cabang $cabang): RedirectResponse
    {
        if (DB::table('users')->where('id_cabang', $cabang->id_cabang)->exists()) {
            return back()->with('error', 'Cabang tidak dapat dihapus karena masih digunakan oleh akun staf. Nonaktifkan cabang jika tidak lagi dipakai.');
        }

        try {
            $cabang->delete();
        } catch (QueryException) {
            return back()->with('error', 'Cabang masih terhubung dengan data operasional dan tidak dapat dihapus. Nonaktifkan cabang jika tidak lagi dipakai.');
        }

        return to_route('e-cesco.master-data.branches.index')->with('success', 'Cabang berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'kode_cabang' => ['required', 'string', 'size:3'],
            'nama_cabang' => ['required', 'string', 'max:50'],
            'provinsi_text' => ['nullable', 'string', 'max:50'],
            'kota_text' => ['nullable', 'string', 'max:75'],
            'alamat1' => ['nullable', 'string', 'max:90'],
            'alamat2' => ['nullable', 'string', 'max:10000'],
            'pic' => ['nullable', 'string', 'max:35'],
            'telp' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:50'],
            'maps' => ['nullable', 'string', 'max:1000'],
            'google_maps' => ['nullable', 'url', 'max:150'],
            'cabang_cesco' => ['required', Rule::in(['Y', 'N'])],
            'status' => ['required', Rule::in(['Y', 'N'])],
        ]);
    }
}
