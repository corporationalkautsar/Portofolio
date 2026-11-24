<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'meta_title' => 'Muhammad Iqbal Fadholi — Software Engineering Student & Web Developer',
            'meta_description' => 'Portfolio of a Software Engineering student building modern, responsive websites with HTML, CSS, JavaScript, Vue.js, PHP and Laravel.',
            'hero_label' => 'SOFTWARE ENGINEERING STUDENT & WEB DEVELOPER',
            'hero_headline' => 'BUILDING MODERN WEBSITES.',
            'hero_description' => "I'm a Software Engineering student who loves turning ideas into modern, responsive websites with HTML, CSS, JavaScript, Vue.js, PHP and Laravel.",
            'hero_status' => 'LEARNING · BUILDING · REPEATING',
            'about_title' => 'ABOUT ME.',
            'contact_title' => "LET'S BUILD SOMETHING TOGETHER.",
            'footer_credits' => 'Built with Laravel & Vue. Always learning.',
            'qr_url' => 'https://github.com/corporationalkautsar/Portofolio',
            'meta_title_id' => 'Muhammad Iqbal Fadholi — Siswa RPL & Web Developer',
            'meta_description_id' => 'Portofolio siswa Rekayasa Perangkat Lunak yang membangun website modern dengan HTML, CSS, JavaScript, Vue.js, PHP, dan Laravel.',
            'hero_label_id' => 'SISWA REKAYASA PERANGKAT LUNAK & WEB DEVELOPER',
            'hero_headline_id' => 'MEMBANGUN WEBSITE MODERN.',
            'hero_description_id' => 'Saya siswa SMK jurusan RPL yang suka mengubah ide menjadi website modern, responsif, dan mudah digunakan dengan HTML, CSS, JavaScript, Vue.js, PHP, dan Laravel.',
            'hero_status_id' => 'TERUS BELAJAR · MEMBANGUN · BERKEMBANG',
            'about_title_id' => 'TENTANG SAYA.',
            'contact_title_id' => 'YUK BANGUN SESUATU BERSAMA.',
            'footer_credits_id' => 'Dibangun dengan Laravel & Vue. Terus belajar.',
        ] as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
