<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'HTML', 'percentage' => 95, 'icon' => 'html5'],
            ['name' => 'CSS', 'percentage' => 92, 'icon' => 'css'],
            ['name' => 'JavaScript', 'percentage' => 90, 'icon' => 'javascript'],
            ['name' => 'Vue.js', 'percentage' => 88, 'icon' => 'vuejs'],
            ['name' => 'Laravel', 'percentage' => 86, 'icon' => 'laravel'],
            ['name' => 'PHP', 'percentage' => 84, 'icon' => 'php'],
        ];

        foreach ($skills as $skill) {
            Skill::query()->updateOrCreate(
                ['name' => $skill['name']],
                $skill,
            );
        }

        Skill::query()
            ->whereNotIn('name', array_column($skills, 'name'))
            ->delete();
    }
}
