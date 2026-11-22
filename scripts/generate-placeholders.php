<?php
/**
 * Generates local, abstract placeholder artwork for the seeded portfolio content.
 * Run: php scripts/generate-placeholders.php
 */

$directory = __DIR__.'/../public/images/placeholders';

if (! is_dir($directory)) {
    mkdir($directory, 0755, true);
}

$labels = [
    'UNTITLED / 01', 'FORM & SPACE', 'SIGNAL / 03', 'MONO STUDY',
    'CHROME DREAMS', 'GRID SYSTEM', 'AFTER HOURS', 'ABSTRACT 08',
];

foreach ($labels as $i => $label) {
    $n = $i + 1;
    $angle = 18 + ($i * 23) % 120;
    $cx = 700 + (($i % 3) - 1) * 120;
    $cy = 500 + (($i % 2) ? 90 : -70);
    $radius = 210 + ($i % 4) * 45;
    $top = $cy - $radius;
    $right = $cx + $radius;
    $bottom = $cy + $radius;
    $left = $cx - $radius;

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1400" height="1000" viewBox="0 0 1400 1000" fill="none">
  <defs>
    <linearGradient id="bg$n" x1="0" y1="0" x2="1400" y2="1000" gradientUnits="userSpaceOnUse">
      <stop stop-color="#141414"/><stop offset="0.55" stop-color="#0b0b0b"/><stop offset="1" stop-color="#10120d"/>
    </linearGradient>
    <linearGradient id="metal$n" x1="0" y1="0" x2="1" y2="1">
      <stop stop-color="#f4f4f4" stop-opacity="0.95"/><stop offset="0.35" stop-color="#7f7f7f" stop-opacity="0.35"/>
      <stop offset="0.7" stop-color="#b5ff3c" stop-opacity="0.75"/><stop offset="1" stop-color="#1e1e1e" stop-opacity="0.9"/>
    </linearGradient>
    <radialGradient id="glow$n" cx="0.5" cy="0.5" r="0.5">
      <stop stop-color="#b5ff3c" stop-opacity="0.22"/><stop offset="1" stop-color="#b5ff3c" stop-opacity="0"/>
    </radialGradient>
    <pattern id="grid$n" width="80" height="80" patternUnits="userSpaceOnUse">
      <path d="M80 0H0V80" stroke="#ffffff" stroke-opacity="0.045" stroke-width="1"/>
    </pattern>
    <filter id="blur$n"><feGaussianBlur stdDeviation="60"/></filter>
  </defs>
  <rect width="1400" height="1000" fill="url(#bg$n)"/>
  <rect width="1400" height="1000" fill="url(#grid$n)"/>
  <ellipse cx="$cx" cy="$cy" rx="520" ry="420" fill="url(#glow$n)" filter="url(#blur$n)"/>
  <g transform="rotate($angle $cx $cy)">
    <circle cx="$cx" cy="$cy" r="$radius" stroke="url(#metal$n)" stroke-width="2"/>
    <circle cx="$cx" cy="$cy" r="$radius" stroke="#b5ff3c" stroke-opacity="0.25" stroke-width="18" stroke-dasharray="4 44"/>
    <rect x="$cx" y="$cy" width="620" height="420" rx="210" transform="translate(-310 -210)" stroke="#ffffff" stroke-opacity="0.22" stroke-width="1.5"/>
    <path d="M$cx $top L$right $cy L$cx $bottom L$left $cy Z" stroke="#ffffff" stroke-opacity="0.16" stroke-width="1.5"/>
  </g>
  <text x="90" y="200" font-family="Arial, Helvetica, sans-serif" font-size="210" font-weight="700" fill="#ffffff" fill-opacity="0.045">0$n</text>
  <path d="M90 890H1310" stroke="#ffffff" stroke-opacity="0.12"/>
  <text x="90" y="935" font-family="Arial, Helvetica, sans-serif" font-size="24" letter-spacing="8" fill="#ffffff" fill-opacity="0.65">$label</text>
  <text x="1310" y="935" text-anchor="end" font-family="Arial, Helvetica, sans-serif" font-size="20" letter-spacing="5" fill="#b5ff3c" fill-opacity="0.8">PLACEHOLDER</text>
</svg>
SVG;

    file_put_contents($directory."/project-0{$n}.svg", $svg);
}

$profile = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="1000" viewBox="0 0 800 1000" fill="none">
  <defs>
    <linearGradient id="pg" x1="0" y1="0" x2="800" y2="1000" gradientUnits="userSpaceOnUse">
      <stop stop-color="#181818"/><stop offset="1" stop-color="#0a0a0a"/>
    </linearGradient>
    <linearGradient id="pp" x1="200" y1="180" x2="650" y2="800" gradientUnits="userSpaceOnUse">
      <stop stop-color="#b5ff3c" stop-opacity="0.65"/><stop offset="0.55" stop-color="#5b5b5b" stop-opacity="0.6"/><stop offset="1" stop-color="#ffffff" stop-opacity="0.1"/>
    </linearGradient>
    <pattern id="pgrid" width="60" height="60" patternUnits="userSpaceOnUse">
      <path d="M60 0H0V60" stroke="#ffffff" stroke-opacity="0.05"/>
    </pattern>
  </defs>
  <rect width="800" height="1000" fill="url(#pg)"/>
  <rect width="800" height="1000" fill="url(#pgrid)"/>
  <circle cx="400" cy="380" r="170" stroke="url(#pp)" stroke-width="2"/>
  <path d="M130 1000c0-190 121-330 270-330s270 140 270 330" stroke="url(#pp)" stroke-width="2"/>
  <circle cx="400" cy="380" r="120" fill="url(#pp)" fill-opacity="0.12"/>
  <text x="60" y="930" font-family="Arial, Helvetica, sans-serif" font-size="26" letter-spacing="9" fill="#ffffff" fill-opacity="0.55">YOUR PHOTO</text>
  <text x="740" y="80" text-anchor="end" font-family="Arial, Helvetica, sans-serif" font-size="20" letter-spacing="6" fill="#b5ff3c" fill-opacity="0.8">PLACEHOLDER</text>
</svg>
SVG;

file_put_contents($directory.'/profile.svg', $profile);

echo 'Generated '.count($labels).' project placeholders and 1 profile placeholder.'.PHP_EOL;
