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
use App\Services\RssGeneratorService;

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
                    ->label('Website Link')
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

        // Load existing feeds
        $feeds = RssGeneratorService::loadExistingFeeds();

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

        // Generate complete RSS XML and save
        RssGeneratorService::updateFromFeeds($feeds);

        Notification::make()
            ->title('RSS Feed item successfully added!')
            ->body("Added to: {$this->rssFileName}")
            ->success()
            ->send();

        // Reset form after successful generation
        $this->form->fill();
    }

    /**
     * Show RSS feed URL and stats
     */
    public function viewRssFeed(): void
    {
        $feeds = RssGeneratorService::loadExistingFeeds();
        $feedCount = count($feeds);
        $rssUrl = url('rss/' . $this->rssFileName);
        
        if ($feedCount === 0) {
            Notification::make()
                ->title('RSS Feed Empty')
                ->body('No items in the RSS feed yet. Add your first item!')
                ->warning()
                ->send();
            return;
        }

        $latestFeeds = collect($feeds)->take(5)->map(function ($feed) {
            return "• {$feed['anime_name']} - {$feed['batch_name']}";
        })->join("\n");

        Notification::make()
            ->title("RSS Feed Ready! ({$feedCount} items)")
            ->body("URL: {$rssUrl}\n\nLatest items:\n{$latestFeeds}")
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
            ->title('All RSS feeds successfully deleted!')
            ->success()
            ->send();
    }
}