<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            // Page 11 — Pharmaceutical, Healthcare & Life Science Clients (1–26)
            ['name' => 'Kalbe Farma', 'sort_order' => 1],
            ['name' => 'Sakafarma Laboratories', 'sort_order' => 2],
            ['name' => 'Dankos Farma', 'sort_order' => 3],
            ['name' => 'Dexa Medica', 'sort_order' => 4],
            ['name' => 'Beta Pharmacon', 'sort_order' => 5],
            ['name' => 'Mahakam Beta Farma', 'sort_order' => 6],
            ['name' => 'Bayer', 'sort_order' => 7],
            ['name' => 'Prosweal Indomax', 'sort_order' => 8],
            ['name' => 'Medifarma Laboratories', 'sort_order' => 9],
            ['name' => 'Darya-Varia Laboratoria', 'sort_order' => 10],
            ['name' => 'Actavis', 'sort_order' => 11],
            ['name' => 'Sanbe Farma', 'sort_order' => 12],
            ['name' => 'Bio Farma', 'sort_order' => 13],
            ['name' => 'Lapi Laboratories', 'sort_order' => 14],
            ['name' => 'Meiji', 'sort_order' => 15],
            ['name' => 'Molex Ayus', 'sort_order' => 16],
            ['name' => 'Otsuka', 'sort_order' => 17],
            ['name' => 'Widatra Bhakti', 'sort_order' => 18],
            ['name' => 'B. Braun', 'sort_order' => 19],
            ['name' => 'Erela', 'sort_order' => 20],
            ['name' => 'Pharos', 'sort_order' => 21],
            ['name' => 'Prima Medika Laboratories', 'sort_order' => 22],
            ['name' => 'Fahrenheit', 'sort_order' => 23],
            ['name' => 'Rohto', 'sort_order' => 24],
            ['name' => 'Tempo Scan', 'sort_order' => 25],
            ['name' => 'Combiphar', 'sort_order' => 26],

            // Page 12 — F&B, Dairy, Testing Labs, Institutions & Universities (27–61)
            ['name' => 'Kalbe Nutritionals', 'sort_order' => 27],
            ['name' => 'Indofood', 'sort_order' => 28],
            ['name' => 'Nutrifood', 'sort_order' => 29],
            ['name' => 'Wings Food', 'sort_order' => 30],
            ['name' => 'Mayora', 'sort_order' => 31],
            ['name' => 'Danone', 'sort_order' => 32],
            ['name' => 'Unilever', 'sort_order' => 33],
            ['name' => 'Orang Tua', 'sort_order' => 34],
            ['name' => 'Sosro', 'sort_order' => 35],
            ['name' => 'Garudafood', 'sort_order' => 36],
            ['name' => 'Salim Ivomas Pratama', 'sort_order' => 37],
            ['name' => 'Cimory', 'sort_order' => 38],
            ['name' => 'Charoen Pokphand', 'sort_order' => 39],
            ['name' => 'Japfa Food', 'sort_order' => 40],
            ['name' => 'Nestle', 'sort_order' => 41],
            ['name' => 'Sarihusada', 'sort_order' => 42],
            ['name' => 'Global Dairi Alami', 'sort_order' => 43],
            ['name' => 'Indolakto', 'sort_order' => 44],
            ['name' => 'Ultrajaya', 'sort_order' => 45],
            ['name' => 'Diamond', 'sort_order' => 46],
            ['name' => 'Greenfields', 'sort_order' => 47],
            ['name' => 'SIG', 'sort_order' => 48],
            ['name' => 'SGS', 'sort_order' => 49],
            ['name' => 'TUV NORD', 'sort_order' => 50],
            ['name' => 'Intertek', 'sort_order' => 51],
            ['name' => 'Sucofindo', 'sort_order' => 52],
            ['name' => 'Kemenkes BB Binomika', 'sort_order' => 53],
            ['name' => 'Alkesda', 'sort_order' => 54],
            ['name' => 'Universitas Islam Indonesia', 'sort_order' => 55],
            ['name' => 'UIN Sunan Kalijaga Yogyakarta', 'sort_order' => 56],
            ['name' => 'Universitas Gadjah Mada', 'sort_order' => 57],
            ['name' => 'Universitas Indonesia', 'sort_order' => 58],
            ['name' => 'Universitas Pelita Harapan', 'sort_order' => 59],
            ['name' => 'Prodia', 'sort_order' => 60],
            ['name' => 'CITO', 'sort_order' => 61],
        ];

        foreach ($clients as $clientData) {
            Client::updateOrCreate(
                ['name' => $clientData['name']],
                [
                    'logo_path' => '',
                    'sort_order' => $clientData['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
