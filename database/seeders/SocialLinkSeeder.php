<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        SocialLink::query()->updateOrCreate(
            ['platform' => 'Instagram'],
            ['url' => 'https://instagram.com/balllxy_'],
        );

        SocialLink::query()->whereNotIn('platform', ['Instagram'])->delete();
    }
}
