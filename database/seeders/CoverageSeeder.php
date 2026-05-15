<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoverageSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Akhalia'],
            ['name' => 'Amborkhana'],
            ['name' => 'Arambagh'],
            ['name' => 'Baghbari'],
            ['name' => 'Baluchar'],
            ['name' => 'Barthokhola'],
            ['name' => 'Bonkolapara'],
            ['name' => 'Borobazar'],
            ['name' => 'Chandipul'],
            ['name' => 'Chasnipir'],
            ['name' => 'Charadighir Par'],
            ['name' => 'Chowkidekhi'],
            ['name' => 'Dargah Gate'],
            ['name' => 'Dargah Moholla'],
            ['name' => 'Daria Para'],
            ['name' => 'Dargah Moholla'],
            ['name' => 'Elashkandi'],
            ['name' => 'Fazil Chist'],
            ['name' => 'Guabari'],
            ['name' => 'Hatimbagh'],
            ['name' => 'Housing Estate'],
            ['name' => 'Jalalabad'],
            ['name' => 'Jallarpar'],
            ['name' => 'Jatarpur'],
            ['name' => 'Jail Road'],
            ['name' => 'Jer Jeri Para'],
            ['name' => 'Kadamtoli'],
            ['name' => 'Kajalshah'],
            ['name' => 'Kanishail'],
            ['name' => 'Kasdobir'],
            ['name' => 'Kazitula'],
            ['name' => 'Kolbakhani'],
            ['name' => 'Kuarpar'],
            ['name' => 'Kumargaw'],
            ['name' => 'Kumarpara'],
            ['name' => 'Lakkatura'],
            ['name' => 'Lakecity'],
            ['name' => 'Laladighirpar'],
            ['name' => 'Lalmati'],
            ['name' => 'Lakripara'],
            ['name' => 'Lamabazar'],
            ['name' => 'Lauai'],
            ['name' => 'Loharpara'],
            ['name' => 'Lovely Road'],
            ['name' => 'Madhu Shahid'],
            ['name' => 'Masimpur'],
            ['name' => 'Medical'],
            ['name' => 'Mendibagh'],
            ['name' => 'Mitali (Arambagh)'],
            ['name' => 'Mitali (Subid Bazar)'],
            ['name' => 'Mirabazar'],
            ['name' => 'Mirboxtula'],
            ['name' => 'Mirer Moidan'],
            ['name' => 'Mirjajangal'],
            ['name' => 'Modina Market'],
            ['name' => 'Mohajonpotti'],
            ['name' => 'Naiorpool'],
            ['name' => 'Neharipara (Akhalia)'],
            ['name' => 'Noyashorok'],
            ['name' => 'Pathantula'],
            ['name' => 'Pir Moholla'],
            ['name' => 'Police Line'],
            ['name' => 'Puran Medical Rd'],
            ['name' => 'Rajargalli'],
            ['name' => 'Rajpara'],
            ['name' => 'Ragib Rabeya Medical'],
            ['name' => 'Raynogor'],
            ['name' => 'Rikabi Bazar'],
            ['name' => 'Sagordighirpar'],
            ['name' => 'Shahi Eidgah'],
            ['name' => 'Shaplabagh'],
            ['name' => 'Shamimabad'],
            ['name' => 'Sheikhghat'],
            ['name' => 'Sheikhpara'],
            ['name' => 'Shibganj'],
            ['name' => 'Shodagartola'],
            ['name' => 'Sobujbag'],
            ['name' => 'Sonarpara'],
            ['name' => 'Stadium'],
            ['name' => 'Subhanighat'],
            ['name' => 'Subid Bazar'],
            ['name' => 'Surma (Akhalia)'],
            ['name' => 'Taltola'],
            ['name' => 'Tarapur'],
            ['name' => 'Temukhi'],
            ['name' => 'Tilagarh'],
            ['name' => 'Uposhohor'],
            ['name' => 'Vatalia'],
        ];

        sort($data);

        $insert = array_map(function ($item) {
            return [
                'name' => $item['name'],
                'user_id' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data);

        DB::table('coverages')->insert($insert);
    }
}
