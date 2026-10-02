<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email' => 'admin@my-db.com', 'name' => 'Administrator', 'role' => 'ADMIN'],
            ['email' => 'adam@my-db.com',  'name' => 'Adam Smith',    'role' => 'USER'],
            ['email' => 'user@my-db.com',  'name' => 'User',          'role' => 'USER'],
        ];

        foreach ($rows as $row) {
            // role และ email ไม่อยู่ใน Fillable จึงใช้ forceFill
            // password จะถูก hash อัตโนมัติจาก casts() 'hashed' ใน User
            User::firstOrNew(['email' => $row['email']])
                ->forceFill([
                    'name' => $row['name'],
                    'role' => $row['role'],
                    'password' => '1234',
                    'email_verified_at' => now(),
                ])
                ->save();
        }
    }
}