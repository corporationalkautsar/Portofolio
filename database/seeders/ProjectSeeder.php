<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $categoryNames = [
            'Portfolio', 'Landing Page', 'Company Profile',
            'Web App', 'E-Commerce', 'Dashboard',
        ];

        foreach ($categoryNames as $name) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        $categories = Category::query()->pluck('id', 'name');

        $projects = [
            [
                'title' => 'Odara',
                'category' => 'Company Profile',
                'stack' => 'HTML, CSS, JavaScript, Vue.js',
                'description' => 'A responsive company profile website for Odara with a bold visual identity, smooth interactions and a clear content structure.',
                'image' => '09',
                'year' => 2026,
                'featured' => true,
            ],
            [
                'title' => 'Libraria',
                'category' => 'Web App',
                'stack' => 'Vue.js, Laravel, PHP, JavaScript',
                'description' => 'A library web app for exploring and managing book collections, with a searchable catalog, borrowing flow and a clean admin area.',
                'image' => '10',
                'year' => 2026,
                'featured' => true,
            ],
            [
                'title' => 'Personal Portfolio',
                'category' => 'Portfolio',
                'stack' => 'HTML, CSS, JavaScript, Vue.js, Laravel',
                'description' => 'A responsive personal portfolio with a dark editorial theme, smooth motion and language switching.',
                'image' => '01',
                'year' => 2026,
                'featured' => false,
            ],
            [
                'title' => 'Landing Page — Kopi Senja',
                'category' => 'Landing Page',
                'stack' => 'HTML, CSS, JavaScript',
                'description' => 'A one-page landing site with a bold hero, menu highlights and a working contact section.',
                'image' => '02',
                'year' => 2026,
                'featured' => false,
            ],
            [
                'title' => 'Company Profile — Kreatif Studio',
                'category' => 'Company Profile',
                'stack' => 'Laravel, PHP, JavaScript',
                'description' => 'A company profile website with a simple content manager and a clean, readable layout.',
                'image' => '03',
                'year' => 2026,
                'featured' => false,
            ],
            [
                'title' => 'School News Portal',
                'category' => 'Web App',
                'stack' => 'Laravel, PHP, JavaScript',
                'description' => 'A news portal where admins can publish, edit and organize school announcements.',
                'image' => '04',
                'year' => 2026,
                'featured' => false,
            ],
            [
                'title' => 'Simple E-Commerce',
                'category' => 'E-Commerce',
                'stack' => 'Vue.js, Laravel, PHP',
                'description' => 'A small online store with a product catalog, cart flow and order handling.',
                'image' => '05',
                'year' => 2025,
                'featured' => false,
            ],
            [
                'title' => 'Analytics Dashboard',
                'category' => 'Dashboard',
                'stack' => 'Vue.js, JavaScript, CSS',
                'description' => 'A dashboard with charts, stat cards and filterable tables built as a single-page app.',
                'image' => '06',
                'year' => 2025,
                'featured' => false,
            ],
            [
                'title' => 'Task Manager',
                'category' => 'Web App',
                'stack' => 'Vue.js, Laravel, PHP',
                'description' => 'A task manager with create, update, delete and status filtering through a REST API.',
                'image' => '07',
                'year' => 2025,
                'featured' => false,
            ],
            [
                'title' => 'Interactive Quiz',
                'category' => 'Web App',
                'stack' => 'HTML, CSS, JavaScript',
                'description' => 'An interactive quiz with timers, instant scoring and a responsive result screen.',
                'image' => '08',
                'year' => 2025,
                'featured' => false,
            ],
        ];

        $slugs = [];

        foreach ($projects as $project) {
            $slug = Str::slug($project['title']);
            $slugs[] = $slug;

            Project::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$project['category']],
                    'title' => $project['title'],
                    'description' => $project['description'],
                    'stack' => $project['stack'],
                    'thumbnail' => "images/placeholders/web-{$project['image']}.svg",
                    'project_image' => "images/placeholders/web-{$project['image']}.svg",
                    'project_url' => null,
                    'year' => $project['year'],
                    'featured' => $project['featured'],
                    'status' => 'published',
                ],
            );
        }

        Project::query()->whereNotIn('slug', $slugs)->delete();
        Category::query()->whereNotIn('name', $categoryNames)->delete();
    }
}
