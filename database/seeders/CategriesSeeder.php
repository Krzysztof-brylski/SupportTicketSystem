<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categories::create([
            'name'=>'Uncategorized'
        ]);
        Categories::create([
           'name'=>'Payment'
        ]);
        Categories::create([
            'name'=>'Technical question'
        ]);
    }
}
