<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthTip;

class HealthTipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tips = [
            [
                'category' => 'Hidrasi',
                'tip' => 'Minum 8 gelas air setiap hari.',
                'content' => 'Air membantu menjaga keseimbangan cairan tubuh, membantu sistem pencernaan, dan meningkatkan konsentrasi selama beraktivitas.',
                'icon' => 'water_drop',
                'order' => 1,
            ],
            [
                'category' => 'Kesehatan Mental',
                'tip' => 'Sempatkan meditasi 5 menit di pagi hari.',
                'content' => 'Melakukan latihan pernapasan atau meditasi singkat dapat menurunkan tingkat stres dan memberikan ketenangan sebelum memulai hari yang sibuk.',
                'icon' => 'self_improvement',
                'order' => 2,
            ],
            [
                'category' => 'Aktivitas Fisik',
                'tip' => 'Jalan kaki minimal 30 menit sehari.',
                'content' => 'Berjalan kaki secara rutin dapat meningkatkan daya tahan tubuh, menjaga kesehatan jantung, dan membantu membakar kalori secara alami.',
                'icon' => 'directions_run',
                'order' => 3,
            ],
        ];

        foreach ($tips as $tip) {
            HealthTip::updateOrCreate(['tip' => $tip['tip']], $tip);
        }
    }
}
