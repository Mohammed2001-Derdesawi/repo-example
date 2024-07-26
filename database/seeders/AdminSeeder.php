<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manager::query()->create([
            'name' => 'Meem Admin',
            'email' => 'admin@meem-sa.com',
            'password' => '123123123',
            'gender' => 'MALE',
        ]);
    }
}
