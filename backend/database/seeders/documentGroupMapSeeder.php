<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentGroupMapSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('document_groups_map')->insert([
            // groupe_allocation (id = 1)
            [
                'document_group_id' => 1,
                'document_type_id' => 7,
               
            ],
            [
                'document_group_id' => 1,
                'document_type_id' => 8,
               
            ],
            [
                'document_group_id' => 1,
                'document_type_id' => 3,
               
            ],

            // groupe_mariage (id = 2)
            [
                'document_group_id' => 2,
                'document_type_id' => 8,
               
            ],
            [
                'document_group_id' => 2,
                'document_type_id' => 5,
               
            ],

            // groupe_naissance (id = 3)
            [
                'document_group_id' => 3,
                'document_type_id' => 8,
            
            ],
            [
                'document_group_id' => 3,
                'document_type_id' => 6,
               
            ],
        ]);
    }
}
