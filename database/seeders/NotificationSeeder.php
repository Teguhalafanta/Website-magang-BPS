<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::create([
            'title' => 'Pengajuan Diterima',
            'message' => 'Pengajuan magang Anda telah disetujui oleh admin.',
        ]);

        Notification::create([
            'title' => 'Jadwal Baru',
            'message' => 'Ada jadwal bimbingan baru minggu depan.',
        ]);
    }
}
