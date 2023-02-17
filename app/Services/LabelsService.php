<?php


namespace App\Services;


use App\Models\Labels;

class LabelsService
{

    public function createLabel(array $data)
    {
        Labels::create([
            'name'=>$data['name'],
        ]);
    }

    public function updateLabel(array $data, Labels $labels)
    {
        $labels->update([
            'name'=>$data['name'],
        ]);
    }
}
