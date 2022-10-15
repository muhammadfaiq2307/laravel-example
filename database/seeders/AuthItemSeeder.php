<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemAuthItem;

class AuthItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemAuthItem::insert([
            [
                'name'      => 'operator',
                'type'      => 1,
                'description'=> 'operator',
                'created_by'    => 'seeder',
                'created_at'    => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
