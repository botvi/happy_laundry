<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'superadmin',
            'name' => 'Superadmin',
            'role' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'foto_profile' => 'https://lh3.googleusercontent.com/a/ACg8ocJroOovPBUo7cGCcixp-sCq9_hQ9fvBvR_g7flBSsRck8-yvXkf=s96-c',
            'no_wa' => '082211104642',
            'password' => Hash::make('password'), // Pastikan mengganti 'password' dengan kata sandi yang aman
        ]);
    }
}