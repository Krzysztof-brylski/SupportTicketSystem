<?php


namespace App\Services;
use App\Models\Categories;

class CategoriesService
{

    public function createCategory(array $data)
    {
        Categories::create([
            'name'=>$data['name']
        ]);
    }

    public function updateCategory(Categories $categories, array $data)
    {

        $categories->update([
            'name'=>$data['name']
        ]);
    }
}
