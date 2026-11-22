<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $biographyId = <<<'TEXT'
## Tentang Saya

Halo! Saya adalah siswa SMK jurusan **Rekayasa Perangkat Lunak (RPL)** yang memiliki ketertarikan besar terhadap dunia teknologi, khususnya pengembangan website.

Saya senang mempelajari dan membangun website yang modern, responsif, serta mudah digunakan. Dalam proses pengembangan, saya terbiasa menggunakan berbagai teknologi seperti **HTML, CSS, JavaScript, Vue.js, PHP, dan Laravel**.

Saya memiliki semangat untuk terus belajar, mengeksplorasi teknologi baru, dan mengembangkan kemampuan dalam menciptakan solusi digital yang kreatif dan fungsional.

Bagi saya, setiap proyek bukan hanya tentang menulis kode, tetapi juga tentang proses belajar, berkembang, dan menciptakan sesuatu yang bermanfaat.
TEXT;

        $biographyEn = <<<'TEXT'
## About Me

Hi, I'm a Software Engineering student and aspiring Web Developer.

I'm passionate about building modern, responsive, and interactive websites. I enjoy turning ideas into digital experiences through clean code and creative problem-solving.

Currently, I'm exploring and developing my skills in **HTML, CSS, JavaScript, Vue.js, PHP, and Laravel**. I love learning new technologies, experimenting with different ideas, and continuously improving my development skills.

I'm always excited to take on new challenges, build meaningful projects, and grow as a developer.

**Code. Create. Innovate. Repeat.**
TEXT;

        Profile::query()->updateOrCreate([], [
            'name' => 'Muhammad Iqbal Fadholi',
            'profession' => 'SISWA SMK REKAYASA PERANGKAT LUNAK (RPL)',
            'biography' => $biographyId,
            'biography_id' => $biographyId,
            'biography_en' => $biographyEn,
            'photo' => 'images/profile/muhammad-iqbal-fadholi.png',
            'email' => 'akunmlbb223434@gmail.com',
            'whatsapp' => 'https://wa.me/6281515274402',
            'location' => 'Besuki, Situbondo, Jawa Timur, Indonesia',
        ]);
    }
}
