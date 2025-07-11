<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Str;

class GenerateCustomRss extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-rss';
    protected static string $view = 'filament.pages.generate-custom-rss';
    protected static ?string $title = 'Generate RSS Feed Manual';

    // Single RSS file name
    private string $rssFileName = 'custom-feeds.xml';
    
    // Base URL for your website
    private string $baseUrl = 'https://revivesubs.com';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'useAutoLink' => true, // Default to auto-generate links
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('animeName')
                    ->label('Anime Name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $get('useAutoLink') ? $this->generateSlug($state, $set) : null
                    ),

                TextInput::make('batchName')
                    ->label('Batch Name')
                    ->required(),

                FileUpload::make('poster')
                    ->label('Picture')
                    ->image()
                    ->directory('custom-rss')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->required(),

                Toggle::make('useAutoLink')
                    ->label('Auto Generate Link')
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $state ? $this->generateSlug($get('animeName'), $set) : null
                    ),

                TextInput::make('animeId')
                    ->label('MAL ID (MyAnimeList ID)')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('useAutoLink'))
                    ->required(fn (callable $get) => $get('useAutoLink'))
                    ->helperText('Enter the MAL ID from MyAnimeList (e.g., 2569 for Jungle Book)')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $this->generateSlug($get('animeName'), $set)
                    ),

                TextInput::make('link')
                    ->label('Custom Link')
                    ->visible(fn (callable $get) => !$get('useAutoLink'))
                    ->required(fn (callable $get) => !$get('useAutoLink'))
                    ->url()
                    ->helperText('Enter the full URL if not using auto-generated link'),

                TextInput::make('generatedLink')
                    ->label('Generated Link (Preview)')
                    ->visible(fn (callable $get) => $get('useAutoLink'))
                    ->disabled()
                    ->helperText('Format: https://revivesubs.com/anime/mal/{MAL_ID}'),
            ])
            ->statePath('data')
            ->live();
    }

    /**
     * Generate slug and update link preview
     */
    private function generateSlug(?string $animeName, callable $set): void
    {
        if (!$animeName) {
            $set('generatedLink', '');
            return;
        }

        $animeId = $this->data['animeId'] ?? null;
        if ($animeId) {
            $generatedLink = $this->baseUrl . '/anime/mal/' . $animeId;
            $set('generatedLink', $generatedLink);
        }
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

            Action::make('validateLinks')
                ->label('Validate All Links')
                ->action('validateAllLinks')
                ->color('warning')
                ->icon('heroicon-o-link'),
        ];
    }

    public function generate(): void
    {
        // Validate and get form data
        $data = $this->form->getState();

        // Determine the final link to use
        $finalLink = $this->getFinalLink($data);

        // Validate the link format
        if (!$this->isValidLink($finalLink)) {
            Notification::make()
                ->title('Invalid Link Format')
                ->body('Please check the link format. It should match your website structure.')
                ->danger()
                ->send();
            return;
        }

        // Ensure RSS directory exists
        File::ensureDirectoryExists(public_path('rss'));

        // Load existing feeds or create new array
        $feeds = $this->loadExistingFeeds();

        // Check for duplicate links
        $existingLinks = collect($feeds)->pluck('link')->toArray();
        if (in_array($finalLink, $existingLinks)) {
            Notification::make()
                ->title('Duplicate Link Detected')
                ->body('This link already exists in the RSS feed.')
                ->warning()
                ->send();
            return;
        }

        // Add new feed item
        $newFeed = [
            'anime_name' => $data['animeName'],
            'batch_name' => $data['batchName'],
            'poster' => $data['poster'],
            'link' => $finalLink,
            'created_at' => now(),
            'id' => uniqid(),
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
            ->body("Link: {$finalLink}")
            ->success()
            ->send();

        // Reset form after successful generation
        $this->form->fill([
            'useAutoLink' => true,
        ]);
    }

    /**
     * Get the final link based on user preference
     */
    private function getFinalLink(array $data): string
    {
        if ($data['useAutoLink']) {
            return $this->baseUrl . '/anime/mal/' . $data['animeId'];
        }
        
        return $data['link'];
    }

    /**
     * Validate link format
     */
    private function isValidLink(string $link): bool
    {
        // Check if it's a valid URL
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if it matches your website domain and expected format
        $parsedUrl = parse_url($link);
        $expectedDomain = parse_url($this->baseUrl, PHP_URL_HOST);
        
        // Check domain
        if ($parsedUrl['host'] !== $expectedDomain) {
            return false;
        }

        // Check if it follows the expected pattern: /anime/mal/{id}
        $path = $parsedUrl['path'] ?? '';
        return preg_match('/^\/anime\/mal\/\d+$/', $path);
    }

    /**
     * Validate all existing links in the feed
     */
    public function validateAllLinks(): void
    {
        $feeds = $this->loadExistingFeeds();
        $invalidLinks = [];
        $validCount = 0;

        foreach ($feeds as $feed) {
            if (!$this->isValidLink($feed['link'])) {
                $invalidLinks[] = $feed['anime_name'] . ' - ' . $feed['link'];
            } else {
                $validCount++;
            }
        }

        if (empty($invalidLinks)) {
            Notification::make()
                ->title('All Links Valid!')
                ->body("All {$validCount} links are properly formatted.")
                ->success()
                ->send();
        } else {
            $invalidList = implode("\n", array_slice($invalidLinks, 0, 5));
            $moreCount = count($invalidLinks) - 5;
            
            Notification::make()
                ->title('Invalid Links Found')
                ->body("Found " . count($invalidLinks) . " invalid links:\n{$invalidList}" . 
                      ($moreCount > 0 ? "\n...and {$moreCount} more" : ""))
                ->warning()
                ->duration(10000)
                ->send();
        }
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
        <item>
            <title><![CDATA[{$feed['anime_name']} - {$feed['batch_name']}]]></title>
            <link>{$feed['link']}</link>
            <description><![CDATA[
                " . ($posterUrl ? "<img src=\"{$posterUrl}\" alt=\"{$feed['anime_name']}\" style=\"max-width: 300px; height: auto;\"><br>" : "") . "
                <strong>Anime:</strong> {$feed['anime_name']}<br>
                <strong>Batch:</strong> {$feed['batch_name']}<br>
                <strong>Link:</strong> <a href=\"{$feed['link']}\" target=\"_blank\">{$feed['link']}</a>
            ]]></description>
            <pubDate>{$pubDate}</pubDate>
            <guid isPermaLink=\"false\">{$guid}</guid>
            " . ($posterUrl ? "<enclosure url=\"{$posterUrl}\" type=\"image/jpeg\" length=\"0\" />" : "") . "
        </item>";
        }

        $channelTitle = 'ReviveSubs - Custom Anime RSS Feed';
        $channelDescription = 'RSS Feed untuk update anime batch terbaru dari ReviveSubs';
        $channelLink = $this->baseUrl;
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
        <generator>ReviveSubs Custom RSS Generator</generator>
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
            return "• {$feed['anime_name']} - {$feed['batch_name']} ({$feed['link']})";
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