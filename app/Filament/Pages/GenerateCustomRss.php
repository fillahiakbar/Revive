<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class GenerateCustomRss extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rss';
    protected static string $view = 'filament.pages.generate-custom-rss';
    protected static ?string $title = 'Generate RSS Feed Manual';

    // Single RSS file name
    private string $rssFileName = 'custom-feeds.xml';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('animeName')
                    ->label('Anime Name')
                    ->required(),

                TextInput::make('batchName')
                    ->label('Batch Name')
                    ->required(),

                FileUpload::make('poster')
                    ->label('Picture')
                    ->image()
                    ->directory('custom-rss')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->required(),

                TextInput::make('link')
                    ->label('Link ke Website')
                    ->required()
                    ->url(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Add to RSS Feed')
                ->action('generate')
                ->color('success')
                ->icon('heroicon-o-document-plus'),
            
            Action::make('viewFeeds')
                ->label('View RSS Feed')
                ->action('viewRssFeed')
                ->color('info')
                ->icon('heroicon-o-list-bullet'),

            Action::make('clearFeeds')
                ->label('Clear All Feeds')
                ->action('clearAllFeeds')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }

    public function generate(): void
    {
        // Validate and get form data
        $data = $this->form->getState();

        // Ensure RSS directory exists
        File::ensureDirectoryExists(public_path('rss'));

        // Load existing feeds or create new array
        $feeds = $this->loadExistingFeeds();

        // Add new feed item
        $newFeed = [
            'anime_name' => $data['animeName'],
            'batch_name' => $data['batchName'],
            'poster' => $data['poster'],
            'link' => $data['link'],
            'created_at' => now(),
            'id' => uniqid(), // Unique identifier for each item
        ];

        // Add to beginning of array (newest first)
        array_unshift($feeds, $newFeed);

        // Generate complete RSS XML
        $rssContent = $this->generateRssXml($feeds);

        // Save RSS file
        File::put(public_path('rss/' . $this->rssFileName), $rssContent);

        // Update index for tracking
        $this->updateFeedIndex($feeds);

        Notification::make()
            ->title('RSS Feed item berhasil ditambahkan!')
            ->body("Ditambahkan ke: {$this->rssFileName}")
            ->success()
            ->send();

        // Reset form after successful generation
        $this->form->fill();
    }

    /**
     * Generate complete RSS XML with all feed items
     */
    private function generateRssXml(array $feeds): string
    {
        $rssItems = '';
        
        foreach ($feeds as $feed) {
            $pubDate = $feed['created_at']->format('D, d M Y H:i:s T');
            $posterUrl = $feed['poster'] ? asset('storage/' . $feed['poster']) : '';
            
            // Ensure 'id' exists for GUID
            $guid = isset($feed['id']) ? $feed['id'] : uniqid('feed_');
            
            $rssItems .= "
        <item>+
            <title><![CDATA[{$feed['anime_name']} - {$feed['batch_name']}]]></title>
            <link>{$feed['link']}</link>
            <description><![CDATA[
                " . ($posterUrl ? "<img src=\"{$posterUrl}\" alt=\"{$feed['anime_name']}\" style=\"max-width: 300px; height: auto;\"><br>" : "") . "
                {$feed['batch_name']}<br>
                <a href=\"{$feed['link']}\" target=\"_blank\">{$feed['link']}</a>
            ]]></description>
            <pubDate>{$pubDate}</pubDate>
            <guid isPermaLink=\"false\">{$guid}</guid>
            " . ($posterUrl ? "<enclosure url=\"{$posterUrl}\" type=\"image/jpeg\" length=\"0\" />" : "") . "
        </item>";
        }

        $channelTitle = 'Custom Anime RSS Feed';
        $channelDescription = 'RSS Feed untuk update anime batch terbaru';
        $channelLink = url('/');
        $lastBuildDate = now()->format('D, d M Y H:i:s T');

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
    <channel>
        <title><![CDATA[{$channelTitle}]]></title>
        <description><![CDATA[{$channelDescription}]]></description>
        <link>{$channelLink}</link>
        <atom:link href=\"" . url('rss/' . $this->rssFileName) . "\" rel=\"self\" type=\"application/rss+xml\" />
        <language>id-ID</language>
        <lastBuildDate>{$lastBuildDate}</lastBuildDate>
        <generator>Laravel Filament Custom RSS Generator</generator>
        {$rssItems}
    </channel>
</rss>";
    }

    /**
     * Load existing feeds from index file
     */
    private function loadExistingFeeds(): array
    {
        $indexPath = public_path('rss/index.json');
        
        if (!File::exists($indexPath)) {
            return [];
        }

        $feedsData = json_decode(File::get($indexPath), true) ?? [];
        
        // Convert back to objects with Carbon dates and ensure 'id' exists
        return collect($feedsData)->map(function ($feed) {
            $feed['created_at'] = \Carbon\Carbon::parse($feed['created_at']);
            // Add 'id' if it doesn't exist (for backward compatibility)
            if (!isset($feed['id'])) {
                $feed['id'] = uniqid('feed_');
            }
            return $feed;
        })->toArray();
    }

    /**
     * Update index file with all feeds data
     */
    private function updateFeedIndex(array $feeds): void
    {
        $indexPath = public_path('rss/index.json');
        
        // Convert feeds to storable format
        $feedsData = collect($feeds)->map(function ($feed) {
            return [
                'anime_name' => $feed['anime_name'],
                'batch_name' => $feed['batch_name'],
                'link' => $feed['link'],
                'poster' => $feed['poster'],
                'created_at' => $feed['created_at']->toISOString(),
                'id' => $feed['id'],
            ];
        })->toArray();

        File::put($indexPath, json_encode($feedsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Show RSS feed URL and stats
     */
    public function viewRssFeed(): void
    {
        $feeds = $this->loadExistingFeeds();
        $feedCount = count($feeds);
        $rssUrl = url('rss/' . $this->rssFileName);
        
        if ($feedCount === 0) {
            Notification::make()
                ->title('RSS Feed Kosong')
                ->body('Belum ada item dalam RSS feed. Tambahkan item pertama!')
                ->warning()
                ->send();
            return;
        }

        $latestFeeds = collect($feeds)->take(5)->map(function ($feed) {
            return "• {$feed['anime_name']} - {$feed['batch_name']}";
        })->join("\n");

        Notification::make()
            ->title("RSS Feed Ready! ({$feedCount} items)")
            ->body("URL: {$rssUrl}\n\nItems terbaru:\n{$latestFeeds}")
            ->info()
            ->duration(15000)
            ->send();
    }

    /**
     * Clear all RSS feeds
     */
    public function clearAllFeeds(): void
    {
        $rssPath = public_path('rss/' . $this->rssFileName);
        $indexPath = public_path('rss/index.json');

        if (File::exists($rssPath)) {
            File::delete($rssPath);
        }

        if (File::exists($indexPath)) {
            File::delete($indexPath);
        }

        Notification::make()
            ->title('Semua RSS feeds berhasil dihapus!')
            ->success()
            ->send();
    }
}