<?php

namespace Database\Seeders;

use App\Models\DocumentGroup;
use Illuminate\Database\Seeder;

class DocumentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'groupe_allocation'],
            ['name' => 'groupe_mariage'],
            ['name' => 'groupe_naissance'],
        ];

        foreach ($groups as $group) {
            DocumentGroup::updateOrCreate(
                ['name' => $group['name']],
                $group
            );
        }
    }
}