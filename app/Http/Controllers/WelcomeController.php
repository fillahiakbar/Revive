<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\AnimeLink;
use App\Models\Slider;
use App\Models\SocialMedia;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        // ====== SETTINGS ======
        $period = SiteSetting::getMostVisitedPeriod(); // 'weekly' | 'monthly' | 'all_time'

        // Atur jumlah per halaman "Latest Releases"
        $LATEST_RELEASES_PER_PAGE = (int) request('lr_per', 5);
        $LATEST_RELEASES_PAGE_NAME = 'lr';

        // ====== SLIDER & SOSMED ======
        $sliders = Slider::where('is_active', true)
            ->orderBy('order')
            ->get([
                'image', 'choice', 'title', 'type', 'duration', 'duration_ms',
                'year', 'quality', 'episodes', 'description', 'mal_id'
            ]);

        $socialMedias = SocialMedia::where('is_active', true)->get();

        // ====== ANIME SECTIONS (work_in_progress, recommendation) ======
        $animes = Anime::query()
            ->orderByRaw("FIELD(type, 'work_in_progress', 'recommendation')")
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($anime) => $anime->mal_id . '-' . $anime->type)
            ->map(function ($anime) {
                $anime->genres = is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : (is_array($anime->genres) ? $anime->genres : []);
                return $anime;
            })
            ->values();

        // ====== LATEST RELEASES (Paginated by latest batch) ======
        // Gunakan withMax('batches', 'created_at') untuk dapat waktu batch terbaru,
        // urutkan desc, lalu paginate dengan pageName 'lr'
        $latestLinks = AnimeLink::query()
            ->whereNotNull('mal_id')
            ->whereHas('batches') // hanya yang punya batch
            ->with(['batches' => fn ($q) => $q->orderByDesc('created_at')->limit(1)])
            ->withMax('batches', 'created_at') // alias: batches_created_at_max
            ->orderByDesc('batches_max_created_at')
            ->paginate(
                $LATEST_RELEASES_PER_PAGE,
                ['*'],
                $LATEST_RELEASES_PAGE_NAME
            )
            ->appends(request()->query());

        // Ubah item paginator ke struktur array yang dipakai Blade,
        // TANPA menghilangkan tipe paginator (pakai setCollection)
        $latestReleases = $latestLinks->setCollection(
            $latestLinks->getCollection()->map(function (AnimeLink $anime) {
                $batch    = $anime->batches->first();
                $episodes = $anime->episodes ?? '؟';

                return [
                    'mal_id'            => $anime->mal_id,
                    'title'             => $anime->title,
                    'title_english'     => $anime->title_english ?? $anime->title,
                    'score'             => $anime->mal_score ?? 'N/A',
                    'imdb_score'        => $anime->imdb_score ?? 'N/A',
                    'type'              => $anime->type ?? '-',
                    'episodes'          => $episodes,
                    'synopsis'          => $batch?->name ?? '-',
                    'latest_batch_name' => $batch?->name ?? '-',
                    'images' => [
                        'jpg' => ['image_url' => $anime->poster ?? '/img/default-poster.jpg']
                    ],
                    'genres' => is_string($anime->genres)
                        ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                        : (is_array($anime->genres) ? $anime->genres : []),
                    'created_at'  => $batch?->created_at ?? $anime->created_at,
                    'click_count' => (int) ($anime->click_count ?? 0),
                ];
            })
        );

        // ====== MOST VISITED (by period) ======
        $MOST_VISITED_LIMIT = 5;

        if ($period === 'all_time') {
            $mostVisitedLinks = AnimeLink::query()
                ->with(['batches' => fn ($q) => $q->orderByDesc('created_at')])
                ->whereNotNull('mal_id')
                ->orderByDesc('click_count')
                ->limit($MOST_VISITED_LIMIT)
                ->get();
        } else {
            $mostVisitedLinks = AnimeLink::query()
                ->with(['batches' => fn ($q) => $q->orderByDesc('created_at')])
                ->whereNotNull('mal_id')
                ->whereHasVisitsInPeriod($period)
                ->withPeriodClicks($period)
                ->orderByDesc('period_clicks')
                ->limit($MOST_VISITED_LIMIT)
                ->get();
        }

        $mostVisited = $mostVisitedLinks->map(function (AnimeLink $anime) {
            $batch    = $anime->batches->first();
            $episodes = $anime->episodes ?? '؟';

            return [
                'mal_id'            => $anime->mal_id,
                'title'             => $anime->title,
                'title_english'     => $anime->title_english ?? $anime->title,
                'score'             => $anime->mal_score ?? 'N/A',
                'imdb_score'        => $anime->imdb_score ?? 'N/A',
                'type'              => $anime->type ?? '-',
                'episodes'          => $episodes,
                'synopsis'          => $batch?->name ?? '-',
                'latest_batch_name' => $batch?->name ?? '-',
                'images' => [
                    'jpg' => ['image_url' => $anime->poster ?? '/img/default-poster.jpg']
                ],
                'genres' => is_string($anime->genres)
                    ? array_map(fn ($g) => ['name' => trim($g)], explode(',', $anime->genres))
                    : [],
                'created_at'  => $batch?->created_at ?? $anime->created_at,
                'click_count' => (int) ($anime->period_clicks ?? $anime->click_count ?? 0),
            ];
        })->values();

        return view('welcome', [
            'animes'         => $animes,
            'latestReleases' => $latestReleases, // <- sekarang paginator
            'mostVisited'    => $mostVisited,
            'sliders'        => $sliders,
            'socialMedias'   => $socialMedias,
            'period'         => $period,
        ]);
    }

    public function about()   { return view('about'); }
    public function terms()   { return view('terms'); }
    public function cookies() { return view('cookies'); }
    public function privacy() { return view('privacy'); }
}
