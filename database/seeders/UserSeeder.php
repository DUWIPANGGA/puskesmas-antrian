<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Puskesmas Jagapura',
            'email' => 'admin@puskesmas.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Puskesmas No. 1, Jagapura',
            'birth_date' => '1985-05-15',
        ]);

        // Dokter-dokter
        $dokters = [
            [
                'name' => 'dr. Ahmad Wijaya, Sp.PD',
                'email' => 'dokter.ahmad@puskesmas.com',
                'phone' => '081234567891',
                'address' => 'Jl. Dokter No. 1, Jagapura',
                'birth_date' => '1980-03-10',
            ],
            [
                'name' => 'dr. Siti Rahmah, Sp.G',
                'email' => 'dokter.siti@puskesmas.com',
                'phone' => '081234567892',
                'address' => 'Jl. Kesehatan No. 2, Jagapura',
                'birth_date' => '1985-07-20',
            ],
            [
                'name' => 'dr. Budi Santoso, Sp.A',
                'email' => 'dokter.budi@puskesmas.com',
                'phone' => '081234567893',
                'address' => 'Jl. Anak Sehat No. 3, Jagapura',
                'birth_date' => '1978-11-05',
            ],
            [
                'name' => 'dr. Dewi Anggraeni',
                'email' => 'dokter.dewi@puskesmas.com',
                'phone' => '081234567894',
                'address' => 'Jl. Ibu dan Anak No. 4, Jagapura',
                'birth_date' => '1988-09-12',
            ],
            [
                'name' => 'dr. Rizki Firmansyah',
                'email' => 'dokter.rizki@puskesmas.com',
                'phone' => '081234567895',
                'address' => 'Jl. Sejahtera No. 5, Jagapura',
                'birth_date' => '1990-12-25',
            ],
        ];

        foreach ($dokters as $dokter) {
            User::create(array_merge($dokter, [
                'password' => Hash::make('password123'),
                'role' => 'dokter',
            ]));
        }

        // Apoteker
        $apotekers = [
            [
                'name' => 'Apt. Hendra Gunawan, S.Farm',
                'email' => 'apoteker.hendra@puskesmas.com',
                'phone' => '081234567896',
                'address' => 'Jl. Apotek No. 6, Jagapura',
                'birth_date' => '1987-04-18',
            ],
            [
                'name' => 'Apt. Maya Sari, S.Farm',
                'email' => 'apoteker.maya@puskesmas.com',
                'phone' => '081234567897',
                'address' => 'Jl. Obat No. 7, Jagapura',
                'birth_date' => '1992-08-22',
            ],
        ];

        foreach ($apotekers as $apoteker) {
            User::create(array_merge($apoteker, [
                'password' => Hash::make('password123'),
                'role' => 'apoteker',
            ]));
        }

        // Pasien (20 pasien sample)
        $pasiens = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567801', 'address' => 'Jl. Mawar No. 1', 'birth_date' => '1990-01-15'],
            ['name' => 'Siti Aminah', 'email' => 'siti@example.com', 'phone' => '081234567802', 'address' => 'Jl. Melati No. 2', 'birth_date' => '1988-03-20'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com', 'phone' => '081234567803', 'address' => 'Jl. Kenanga No. 3', 'birth_date' => '1995-07-10'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081234567804', 'address' => 'Jl. Anggrek No. 4', 'birth_date' => '1992-11-25'],
            ['name' => 'Rizki Pratama', 'email' => 'rizki@example.com', 'phone' => '081234567805', 'address' => 'Jl. Cemara No. 5', 'birth_date' => '1985-09-30'],
            ['name' => 'Nurul Hidayah', 'email' => 'nurul@example.com', 'phone' => '081234567806', 'address' => 'Jl. Flamboyan No. 6', 'birth_date' => '1998-02-14'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'phone' => '081234567807', 'address' => 'Jl. Kamboja No. 7', 'birth_date' => '1983-12-01'],
            ['name' => 'Rina Marlina', 'email' => 'rina@example.com', 'phone' => '081234567808', 'address' => 'Jl. Sakura No. 8', 'birth_date' => '1991-06-18'],
            ['name' => 'Dodi Hermawan', 'email' => 'dodi@example.com', 'phone' => '081234567809', 'address' => 'Jl. Teratai No. 9', 'birth_date' => '1987-04-22'],
            ['name' => 'Lisa Permata', 'email' => 'lisa@example.com', 'phone' => '081234567810', 'address' => 'Jl. Dahlia No. 10', 'birth_date' => '1993-10-05'],
            ['name' => 'Andi Wijaya', 'email' => 'andi@example.com', 'phone' => '081234567811', 'address' => 'Jl. Kencana No. 11', 'birth_date' => '1989-08-17'],
            ['name' => 'Ratna Dewi', 'email' => 'ratna@example.com', 'phone' => '081234567812', 'address' => 'Jl. Mutiara No. 12', 'birth_date' => '1996-01-28'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra@example.com', 'phone' => '081234567813', 'address' => 'Jl. Permata No. 13', 'birth_date' => '1984-07-09'],
            ['name' => 'Yuni Astuti', 'email' => 'yuni@example.com', 'phone' => '081234567814', 'address' => 'Jl. Safir No. 14', 'birth_date' => '1994-03-12'],
            ['name' => 'Agus Salim', 'email' => 'agus@example.com', 'phone' => '081234567815', 'address' => 'Jl. Berlian No. 15', 'birth_date' => '1986-11-03'],
            ['name' => 'Sri Mulyani', 'email' => 'sri@example.com', 'phone' => '081234567816', 'address' => 'Jl. Zamrud No. 16', 'birth_date' => '1997-05-21'],
            ['name' => 'Taufik Hidayat', 'email' => 'taufik@example.com', 'phone' => '081234567817', 'address' => 'Jl. Topaz No. 17', 'birth_date' => '1982-09-14'],
            ['name' => 'Indah Permatasari', 'email' => 'indah@example.com', 'phone' => '081234567818', 'address' => 'Jl. Ruby No. 18', 'birth_date' => '1991-12-07'],
            ['name' => 'Bayu Setiawan', 'email' => 'bayu@example.com', 'phone' => '081234567819', 'address' => 'Jl. Opal No. 19', 'birth_date' => '1988-02-29'],
            ['name' => 'Citra Ayu', 'email' => 'citra@example.com', 'phone' => '081234567820', 'address' => 'Jl. Giok No. 20', 'birth_date' => '1995-06-24'],
        ];

        foreach ($pasiens as $pasien) {
            User::create(array_merge($pasien, [
                'password' => Hash::make('password123'),
                'role' => 'pasien',
            ]));
        }
    }
}