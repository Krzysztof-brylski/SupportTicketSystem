<?php

namespace Database\Seeders;

use App\Models\Labels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Labels::create([
           'name'=>'bug'
        ]);
        Labels::create([
            'name'=>'question'
        ]);
        Labels::create([
            'name'=>'enhancement'
        ]);
    }
}
