<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programming = Category::create([
            'name' => 'Programming'
        ]);

        $business = Category::create([
            'name' => 'Business'
        ]);

        Category::create([
            'name' => 'Web Development',
            'parent_id' => $programming->id
        ]);

        Category::create([
            'name' => 'Mobile Development',
            'parent_id' => $programming->id
        ]);

        Category::create([
            'name' => 'Digital Marketing',
            'parent_id' => $business->id
        ]);
    }
}