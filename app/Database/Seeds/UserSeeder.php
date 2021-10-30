<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        //
        $user = new UserModel;
        $faker = \Faker\Factory::create();
        $user->save(
            [
                'name'        =>    'admin',
                'email'       =>    'admin@gmail.com',
                'phone'       =>    '1234567890',
                'role'        =>    'admin',
                'otp'         =>    NULL,
                'expiry'      =>    NULL,
                'created_at'  =>    date('Y-m-d H:i:s'),
                'updated_at'  =>    NULL,
            ]
        );
    }
}
