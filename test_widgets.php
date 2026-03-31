<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Widget Queries ===" . PHP_EOL;

// Test 1: Anime count
try {
    echo "Anime count: " . \App\Models\Anime::count() . PHP_EOL;
} catch (\Throwable $e) {
    echo "Anime ERROR: " . $e->getMessage() . PHP_EOL;
}

// Test 2: AnimeLink by type
try {
    $rows = \App\Models\AnimeLink::selectRaw('type, COUNT(*) as total')
        ->whereNotNull('type')
        ->where('type', '!=', '')
        ->groupBy('type')
        ->get();
    echo "AnimeLink by type: " . $rows->count() . " types found" . PHP_EOL;
    foreach ($rows as $r) {
        echo "  - {$r->type}: {$r->total}" . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "AnimeLink type ERROR: " . $e->getMessage() . PHP_EOL;
}

// Test 3: AnimeLink by status
try {
    $rows = \App\Models\AnimeLink::selectRaw('status, COUNT(*) as total')
        ->whereNotNull('status')
        ->where('status', '!=', '')
        ->groupBy('status')
        ->get();
    echo "AnimeLink by status: " . $rows->count() . " statuses found" . PHP_EOL;
    foreach ($rows as $r) {
        echo "  - {$r->status}: {$r->total}" . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "AnimeLink status ERROR: " . $e->getMessage() . PHP_EOL;
}

// Test 4: Comment daily
try {
    echo "Comment count: " . \App\Models\Comment::count() . PHP_EOL;
} catch (\Throwable $e) {
    echo "Comment ERROR: " . $e->getMessage() . PHP_EOL;
}

// Test 5: Check if widget classes load correctly
$widgets = [
    'ContentReleaseChart',
    'UserGrowthChart',
    'CommentsActivityChart',
    'AnimeByTypePieChart',
    'AnimeByStatusPieChart',
];

foreach ($widgets as $w) {
    $class = "App\\Filament\\Widgets\\{$w}";
    try {
        $ref = new ReflectionClass($class);
        $parent = $ref->getParentClass()->getName();
        echo "{$w}: OK (extends {$parent})" . PHP_EOL;
    } catch (\Throwable $e) {
        echo "{$w}: FAILED - " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "=== Done ===" . PHP_EOL;
