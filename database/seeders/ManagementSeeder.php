<?php

namespace Database\Seeders;

use App\Models\Management;
use Illuminate\Database\Seeder;

class ManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managements = [
            [
                'name' => 'Erik Haryanto',
                'position_id' => 'Komisaris / Manajer Pemasaran & Penjualan',
                'position_en' => 'Marketing & sales manager and commissioner',
                'bio_id' => "2001 – 2013: Pharmacy industry laboratory\n2013 – Now: Marketing & sales manager and commissioner",
                'bio_en' => "2001 – 2013: Pharmacy industry laboratory\n2013 – Now: Marketing & sales manager and commissioner",
                'photo_path' => null,
                'sort_order' => 1,
                'is_active' => false,
            ],
            [
                'name' => 'Fernanda Ramadhan F',
                'position_id' => 'Direktur Penjualan',
                'position_en' => 'Sales and director',
                'bio_id' => '2022 – Now: Sales and director',
                'bio_en' => '2022 – Now: Sales and director',
                'photo_path' => null,
                'sort_order' => 2,
                'is_active' => false,
            ],
            [
                'name' => 'Hazin Yusuf',
                'position_id' => 'Direktur / Manajer Pemasaran',
                'position_en' => 'Marketing manager and director',
                'bio_id' => '2001 – Now: Has been pioneering the life science business as marketing manager and director',
                'bio_en' => '2001 – Now: Has been pioneering the life science business as marketing manager and director',
                'photo_path' => null,
                'sort_order' => 3,
                'is_active' => false,
            ],
        ];

        foreach ($managements as $management) {
            Management::updateOrCreate(
                ['name' => $management['name']],
                $management
            );
        }
    }
}
