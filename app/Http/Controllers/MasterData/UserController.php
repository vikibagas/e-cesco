<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /** @var list<string> */
    private const LEVELS = [
        'admin',
        'admin_network',
        'agent',
        'domex',
        'driver',
        'finance',
        'inbound',
        'intercity',
        'international',
        'kurir',
        'kurir_agent',
        'logistics',
        'network',
        'operator',
        'outbound',
        'sales',
    ];

    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);

        $users = User::query()
            ->leftJoin('cabang', 'users.id_cabang', '=', 'cabang.id_cabang')
            ->select([
                'users.id',
                'users.name',
                'users.namalengkap',
                'users.username',
                'users.email',
                'users.level',
                'users.id_cabang',
                'users.blokir',
                'cabang.nama_cabang as branch_name',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('users.username', 'like', "%{$search}%")
                        ->orWhere('users.namalengkap', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('users.level', 'like', "%{$search}%");
                });
            })
            ->orderBy('users.namalengkap')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->namalengkap ?: $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'level' => $user->level,
                'branch' => $user->branch_name,
                'blocked' => $user->blokir === 'Y',
            ]);

        return Inertia::render('Operations/MasterData/Users/Index', [
            'users' => $users,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operations/MasterData/Users/Form', [
            'user' => null,
            'levels' => self::LEVELS,
            'branches' => $this->branches(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $user = new User;
        $this->fillUser($user, $data);
        $user->save();

        return to_route('e-cesco.master-data.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Operations/MasterData/Users/Form', [
            'user' => [
                'id' => $user->id,
                'name' => $user->namalengkap ?: $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'telp' => $user->telp,
                'level' => $user->level,
                'id_cabang' => $user->id_cabang,
                'blokir' => $user->blokir,
            ],
            'levels' => self::LEVELS,
            'branches' => $this->branches(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatedData($request, $user);
        $password = $data['password'] ?? null;
        unset($data['password']);

        if ($user->level === 'admin'
            && ($data['level'] !== 'admin' || $data['blokir'] === 'Y')
            && ! $this->activeAdminsExcept($user)->exists()) {
            return back()->with('error', 'User ini adalah admin aktif terakhir. Aktifkan admin lain terlebih dahulu.');
        }

        $this->fillUser($user, $data);

        if (is_string($password) && $password !== '') {
            $user->password = $password;
        }

        $user->save();

        return to_route('e-cesco.master-data.users.index')->with('success', 'Perubahan user berhasil disimpan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ((int) $user->getKey() === (int) auth('web')->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        if ($user->level === 'admin' && ! $this->activeAdminsExcept($user)->exists()) {
            return back()->with('error', 'Admin aktif terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return to_route('e-cesco.master-data.users.index')->with('success', 'User berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request, ?User $user = null): array
    {
        $passwordRules = $user === null
            ? ['required', 'string', 'confirmed', Password::min(12)]
            : ['nullable', 'string', 'confirmed', Password::min(12)];

        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'username')->ignore($user?->id),
                Rule::unique('users_client', 'username'),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'telp' => ['nullable', 'string', 'max:20'],
            'level' => ['required', Rule::in(self::LEVELS)],
            'id_cabang' => ['nullable', 'integer', Rule::exists('cabang', 'id_cabang')],
            'blokir' => ['required', Rule::in(['Y', 'N'])],
            'password' => $passwordRules,
        ]);
    }

    /** @param array<string, mixed> $data */
    private function fillUser(User $user, array $data): void
    {
        $user->fill([
            'name' => $data['name'],
            'namalengkap' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'telp' => $data['telp'] ?? null,
            'phone' => $data['telp'] ?? null,
            'level' => $data['level'],
            'id_cabang' => $data['id_cabang'] ?? null,
            'blokir' => $data['blokir'],
        ]);

        if (isset($data['password']) && $data['password'] !== '') {
            $user->password = $data['password'];
        }
    }

    /** @return Builder<User> */
    private function activeAdminsExcept(User $user): Builder
    {
        return User::query()
            ->where('level', 'admin')
            ->where('blokir', 'N')
            ->where('id', '!=', $user->id)
            ->where(function (Builder $query): void {
                $query->whereNull('tanggalkadaluarsa')
                    ->orWhereDate('tanggalkadaluarsa', '>=', today());
            });
    }

    /** @return array<int, array{id: int, label: string}> */
    private function branches(): array
    {
        return DB::table('cabang')
            ->select(['id_cabang as id', 'kode_cabang', 'nama_cabang'])
            ->orderBy('nama_cabang')
            ->get()
            ->map(fn (object $branch): array => [
                'id' => (int) $branch->id,
                'label' => $branch->kode_cabang.' — '.$branch->nama_cabang,
            ])
            ->all();
    }
}
