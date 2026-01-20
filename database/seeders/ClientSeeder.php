<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'nama_client' => 'Pak Herry',
                'perusahaan' => 'Orzora Kosmetic',
                'no_telepon' => '6285746381415',
                'tagihan' => 1150000,
                'kode_client' => 'CLN001',
                'alamat' => 'Lumajang',
                'jabatan' => 'Owner',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Rafika',
                'perusahaan' => 'PT. Romero Group',
                'no_telepon' => '6281336159090',
                'tagihan' => 240000,
                'kode_client' => 'CLN002',
                'alamat' => 'Lumajang',
                'jabatan' => 'Staff',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Dina',
                'perusahaan' => 'Toko Sembako',
                'no_telepon' => '6282244334247',
                'tagihan' => 100000,
                'kode_client' => 'CLN005',
                'alamat' => 'Probolinggo',
                'jabatan' => 'Owner',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Dr. Ajeng',
                'perusahaan' => 'Mde Clinic',
                'no_telepon' => '6285232327777',
                'tagihan' => 50000,
                'kode_client' => 'CLN006',
                'alamat' => 'Lumajang',
                'jabatan' => 'Dokter',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Fitri',
                'perusahaan' => 'Boutique',
                'no_telepon' => '6281336455554',
                'tagihan' => 45000,
                'kode_client' => 'CLN007',
                'alamat' => 'Lumajang',
                'jabatan' => 'Owner',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Ilham',
                'perusahaan' => 'RR Gadgetstore',
                'no_telepon' => '6282244104881',
                'tagihan' => 70000,
                'kode_client' => 'CLN008',
                'alamat' => 'Lumajang',
                'jabatan' => 'Owner',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'AMAR',
                'perusahaan' => 'SMK MULU',
                'no_telepon' => '6281332031193',
                'tagihan' => 700000,
                'kode_client' => 'CLN014',
                'alamat' => 'Labruk',
                'jabatan' => 'Staff',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'RYO MEY MARTINO',
                'perusahaan' => 'PT RYO DIGITAL PRINTING GROUP',
                'no_telepon' => '6281336777077',
                'tagihan' => 5000000,
                'kode_client' => 'CLN015',
                'alamat' => 'JL AHMAD YANI NO 19 LMJ',
                'jabatan' => 'DIREKTUR UTAMA',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Aldi Putra',
                'perusahaan' => 'Toko',
                'no_telepon' => '6285236655776',
                'tagihan' => 50000,
                'kode_client' => 'CLN016',
                'alamat' => '-',
                'jabatan' => '-',
                'bulan' => '01',
            ],
            [
                'nama_client' => 'Bidan Imroatul Khoyimah ,S.ST',
                'perusahaan' => 'Klinik Mandiri',
                'no_telepon' => '+6281234328420',
                'tagihan' => 200000,
                'kode_client' => 'CLN017',
                'alamat' => 'Candipuro, Lumajang',
                'jabatan' => 'Bidan',
                'bulan' => '01',
            ],

        ];
        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
