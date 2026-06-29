<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleDocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypeIds = range(1, 10);

        $roles = Role::all();

        foreach ($roles as $role) {
            $role->documentTypes()->attach($documentTypeIds);
        }
    }
}
