<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    function run(): void
    {
        $users = [
            ['admin@my-db.com', 'Administrator', 'ADMIN'],
            ['user@my-db.com', 'User', 'USER'],
            ['adam@my-db.com', 'Adam Smith', 'USER'],
        ];

        foreach ($users as [$email, $name, $role]) {
            // ถ้ามีบัญชีอยู่แล้วจะไม่ทับ (ไม่รีเซ็ตรหัสผ่านเดิม)
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => $role,
                    'password' => 'password', // plain text, cast 'hashed' จะเข้ารหัสให้
                ],
            );
        }
    }
}