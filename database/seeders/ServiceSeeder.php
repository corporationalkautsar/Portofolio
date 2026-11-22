<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Graphic Design', 'Visual systems, layouts and artwork built with clarity, rhythm and purpose.', 'palette'],
            ['Branding & Identity', 'Distinctive brand worlds — from strategy and marks to full visual guidelines.', 'sparkles'],
            ['Social Media Design', 'Scroll-stopping content systems designed for consistent, recognizable presence.', 'instagram'],
            ['Poster & Advertising', 'Editorial compositions that communicate a single idea with lasting impact.', 'image'],
            ['Digital Creative Design', 'Experimental visuals, art direction and digital campaigns for modern audiences.', 'wand-sparkles'],
            ['Web Design', 'High-end interface design with thoughtful interaction and responsive craft.', 'monitor'],
        ] as [$title, $description, $icon]) {
            Service::query()->updateOrCreate(
                ['title' => $title],
                ['description' => $description, 'icon' => $icon],
            );
        }
    }
}
