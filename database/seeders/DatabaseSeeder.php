<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create();
        $this->call(LaratrustSeeder::class);
        $superAdmin = Manager::create([
            'name' => 'Meem Admin',
            'email' => "admin@meem-sa.com" ,
            'password' => "123123123",
            'gender' => 'MALE'
        ]);
        $superAdmin->attachRole('super-admin');
        $this->call(SiteContentSeeder::class);

    }
}
