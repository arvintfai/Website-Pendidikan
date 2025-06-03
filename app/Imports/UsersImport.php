<?php

namespace App\Imports;

use Log;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User([
            'name'  => $row['name'],
            'email' => $row['username'],
            'password'    => Hash::make($row['password']),
            'remember_token' => Str::random(10),
        ]);

        $roleName = $row['role'] ?? 'student';

        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($role);
        } else {
            // Opsional: Log error atau buat role baru jika tidak ditemukan
            \Log::warning("Role '{$roleName}' not found for user '{$user->email}'.");
            // Role::create(['name' => $roleName]); // Bisa juga otomatis membuat role jika tidak ada
            // $user->assignRole($roleName);
        }

        return $user;
    }

    /**
     * Opsional: Menentukan heading row jika Excel Anda punya header
     */
    public function headingRow(): int
    {
        return 1; // Baris pertama adalah heading
    }
}
