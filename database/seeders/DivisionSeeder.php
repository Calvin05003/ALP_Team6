<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = Division::create([
            'name' => 'Marketing',
            'description' => 'Handles campaign messaging and promotional strategies.'
        ]);

        $marketing->skills()->createMany([
            ['skill_name' => 'Public Speaking', 'importance_level' => 5],
            ['skill_name' => 'Copywriting', 'importance_level' => 4],
            ['skill_name' => 'Social Media Management', 'importance_level' => 4],
        ]);

        $sponsorship = Division::create([
            'name' => 'Sponsorship',
            'description' => 'Builds and maintains relationships with external partners.'
        ]);

        $sponsorship->skills()->createMany([
            ['skill_name' => 'Negotiation', 'importance_level' => 5],
            ['skill_name' => 'Business Communication', 'importance_level' => 4],
        ]);
        $this->call([
            DivisionSeeder::class,
        ]);
    }
}