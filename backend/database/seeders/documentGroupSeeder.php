<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentGroup;
class documentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group=[
            ['name'=>'groupe_allocation'],
            ['name'=>'groupe_mariage'],
            ['name'=>'groupe_naissance'],

        ];
               foreach ($group as $data) {
    DocumentGroup::create($data);
}
    }
}
