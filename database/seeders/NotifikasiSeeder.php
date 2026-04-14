<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Antrian;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $pasiens = User::where('role', 'pasien')->get();
        $antrians = Antrian::where('status', 'dipanggil')->get();
        
        // Notifikasi panggilan antrian
        foreach ($antrians as $antrian) {
            Notifikasi::create([
                'user_id' => $antrian->pasien_id,
                'antrian_id' => $antrian->id,
                'judul' => 'Panggilan Antrian',
                'pesan' => "Nomor antrian {$antrian->nomor_antrian} sudah dipanggil. Segera menuju poli yang dituju.",
                'tipe' => 'panggilan',
                'is_read' => rand(0, 1),
                'read_at' => rand(0, 1) ? now()->subMinutes(rand(1, 30)) : null,
            ]);
        }
        
        // Notifikasi informasi untuk pasien
        $infoMessages = [
            'Jaga kesehatan dengan menerapkan pola hidup sehat',
            'Jangan lupa cuci tangan pakai sabun',
            'Puskesmas buka dari jam 08.00 - 14.00',
            'Untuk pendaftaran online bisa melalui website',
            'Bawa KTP dan Kartu Berobat saat berkunjung',
        ];
        
        foreach ($pasiens as $pasien) {
            // Beri 2-3 notifikasi info per pasien
            $jumlahInfo = rand(2, 3);
            for ($i = 0; $i < $jumlahInfo; $i++) {
                Notifikasi::create([
                    'user_id' => $pasien->id,
                    'antrian_id' => null,
                    'judul' => 'Informasi Puskesmas',
                    'pesan' => $infoMessages[array_rand($infoMessages)],
                    'tipe' => 'info',
                    'is_read' => rand(0, 1),
                    'read_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
                ]);
            }
        }
    }
}