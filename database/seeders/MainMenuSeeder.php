<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemMainMenu;

class MainMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemMainMenu::insert([
            ['link'=> 'absensi/create','color'=> 'bg-gradient-blue','icon'=> 'ni ni-single-02','description'=> 'Absensi','created_by'=> 'seeder','created_at'=> date('Y-m-d H:i:s')],
            ['link'=> 'accepted-do-ndc/index-outstanding','color'=> 'bg-gradient-primary','icon'=> 'ni ni-single-copy-04','description'=> 'Outstanding DO NDC','created_by'=> 'seeder','created_at'=> date('Y-m-d H:i:s')],
        ]);
    }
}
