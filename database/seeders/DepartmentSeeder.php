<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Admin','Operations','Production','Reception','Account','Others'] as $name) {
            Department::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'is_active' => true]
            );
        }
    }
}
