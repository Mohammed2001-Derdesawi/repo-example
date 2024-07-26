<?php

namespace Database\Seeders;

use App\Models\Info;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Info::query()->updateOrCreate(['key' => 'logo'], [
            'key' => 'logo',
            'type' => 'image',
            'name_en' => 'logo',
            'name_ar' => 'الشعار',
        ]);
    }
}
