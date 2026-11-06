<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UserClientController extends Controller
{
    /** @var list<string> */
    private const TYPES = ['all', 'outbound', 'inbound'];

    public function index(Request $request): Response
    {
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);

        $clients = UserClient::query()
            ->leftJoin('kustomer', 'users_client.id_kustomer', '=', 'kustomer.id_kustomer')
            ->select([
                'users_client.id_user',
                'users_client.username',
                'users_client.email',
                'users_client.fullname',
                'users_client.type',
                'users_client.company',
                'users_client.phone',
                'users_client.blocked',
                'users_client.expired_date',
                'kustomer.nama_kustomer as customer_name',
            ])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('username', 'like', "%{$search}%")
                        ->orWhere('fullname', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('kustomer.nama_kustomer', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('fullname')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (UserClient $client): array => [
                'id' => $client->id_user,
                'name' => $client->fullname ?: $client->company ?: $client->username,
                'username' => $client->username,
                'email' => $client->email,
                'company' => $client->customer_name ?: $client->company,
                'type' => $client->type,
                'blocked' => $client->blocked === 'Y',
                'expired' => $client->expired_date?->isBefore(today()) ?? false,
            ]);

        return Inertia::render('Operations/MasterData/UsersClient/Index', [
            'clients' => $clients,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Operations/MasterData/UsersClient/Form', [
            'client' => null,
            'types' => self::TYPES,
            'customers' => $this->customers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $client = new UserClient;
        $client->forceFill([
            'id_kustomer' => $data['id_kustomer'] ?? null,
            'customers' => $data['customers'] ?? null,
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'fullname' => $data['fullname'],
            'type' => $data['type'],
            'company' => $this->customerName($data['id_kustomer'] ?? null),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'blocked' => $data['blocked'],
            'expired_date' => $data['expired_date'] ?? null,
            'report_awb_loc' => $data['report_awb_loc'],
            'other_customer' => 'N',
            'forget_password' => 'N',
            'warehouse' => 'N',
            'show_invoice' => 'N',
        ])->save();

        return to_route('e-cesco.master-data.users-client.index')->with('success', 'User client berhasil dibuat.');
    }

    public function edit(UserClient $userClient): Response
    {
        return Inertia::render('Operations/MasterData/UsersClient/Form', [
            'client' => [
                'id' => $userClient->id_user,
                'id_kustomer' => $userClient->id_kustomer,
                'customers' => $userClient->customers,
                'username' => $userClient->username,
                'email' => $userClient->email,
                'fullname' => $userClient->fullname,
                'type' => $userClient->type,
                'company' => $userClient->company,
                'phone' => $userClient->phone,
                'blocked' => $userClient->blocked,
                'expired_date' => $userClient->expired_date?->format('Y-m-d'),
                'report_awb_loc' => $userClient->report_awb_loc,
            ],
            'types' => self::TYPES,
            'customers' => $this->customers(),
        ]);
    }

    public function update(Request $request, UserClient $userClient): RedirectResponse
    {
        $data = $this->validatedData($request, $userClient);
        $password = $data['password'] ?? null;
        unset($data['password']);

        $userClient->forceFill([
            'id_kustomer' => $data['id_kustomer'] ?? null,
            'customers' => $data['customers'] ?? null,
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'fullname' => $data['fullname'],
            'type' => $data['type'],
            'company' => $this->customerName($data['id_kustomer'] ?? null) ?? $userClient->company,
            'phone' => $data['phone'] ?? null,
            'blocked' => $data['blocked'],
            'expired_date' => $data['expired_date'] ?? null,
            'report_awb_loc' => $data['report_awb_loc'],
        ]);

        if (is_string($password) && $password !== '') {
            $userClient->password = $password;
        }

        $userClient->save();

        return to_route('e-cesco.master-data.users-client.index')->with('success', 'Perubahan user client berhasil disimpan.');
    }

    public function destroy(UserClient $userClient): RedirectResponse
    {
        $userClient->delete();

        return to_route('e-cesco.master-data.users-client.index')->with('success', 'User client berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request, ?UserClient $userClient = null): array
    {
        $passwordRules = $userClient === null
            ? ['required', 'string', 'confirmed', Password::min(12)]
            : ['nullable', 'string', 'confirmed', Password::min(12)];

        return $request->validate([
            'id_kustomer' => ['nullable', 'integer', Rule::exists('kustomer', 'id_kustomer')],
            'customers' => ['nullable', 'string', 'max:100'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users_client', 'username')->ignore($userClient?->id_user, 'id_user'),
                Rule::unique('users', 'username'),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:100',
                Rule::unique('users_client', 'email')->ignore($userClient?->id_user, 'id_user'),
            ],
            'fullname' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(self::TYPES)],
            'phone' => ['nullable', 'string', 'max:13'],
            'blocked' => ['required', Rule::in(['Y', 'N'])],
            'expired_date' => ['nullable', 'date'],
            'report_awb_loc' => ['required', 'integer', 'min:0'],
            'password' => $passwordRules,
        ]);
    }

    /** @return array<int, array{id: int, label: string, name: string}> */
    private function customers(): array
    {
        return DB::table('kustomer')
            ->select(['id_kustomer as id', 'kode_kustomer', 'nama_kustomer'])
            ->orderBy('nama_kustomer')
            ->get()
            ->map(fn (object $customer): array => [
                'id' => (int) $customer->id,
                'label' => $customer->kode_kustomer.' — '.$customer->nama_kustomer,
                'name' => $customer->nama_kustomer,
            ])
            ->all();
    }

    private function customerName(int|string|null $customerId): ?string
    {
        if ($customerId === null || $customerId === '') {
            return null;
        }

        $name = DB::table('kustomer')->where('id_kustomer', $customerId)->value('nama_kustomer');

        return is_string($name) ? $name : null;
    }
}
