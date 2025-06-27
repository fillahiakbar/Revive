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

    // ✅ Fix: Use array to store form data like in SliderResource
    public ?array $data = [];

    // ✅ Add mount method to initialize form data
    public function mount(): void
    {
        $this->form->fill();
    }

    // ✅ Form schema similar to SliderResource pattern
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
            ->statePath('data'); // ✅ Important: bind form to data property
    }

    // ✅ Header actions for generate button
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate RSS')
                ->action('generate')
                ->color('success')
                ->icon('heroicon-o-document-plus'),
            
            Action::make('viewFeeds')
                ->label('View All Feeds')
                ->action('viewAllFeeds')
                ->color('info')
                ->icon('heroicon-o-list-bullet'),
        ];
    }

    public function generate(): void
    {
        // ✅ Validate and get form data
        $data = $this->form->getState();

        // ✅ Generate filename based on batch name (sanitize for filesystem)
        $filename = $this->sanitizeFilename($data['batchName']) . '.xml';

        $rssContent = view('rss.custom', [
            'animeName' => $data['animeName'],
            'batchName' => $data['batchName'],
            'poster' => $data['poster'],
            'link' => $data['link'],
            'filename' => $filename, // Pass filename to view for self-reference
        ])->render();

        File::ensureDirectoryExists(public_path('rss'));
        File::put(public_path('rss/' . $filename), $rssContent);

        // ✅ Also create/update index file with all feeds
        $this->updateFeedIndex($filename, $data);

        Notification::make()
            ->title('RSS Feed berhasil dibuat!')
            ->body("File: {$filename}")
            ->success()
            ->send();

        // ✅ Optional: Reset form after successful generation
        $this->form->fill();
    }

    /**
     * Sanitize filename for filesystem
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove or replace invalid characters
        $filename = preg_replace('/[^a-zA-Z0-9\-_\s]/', '', $filename);
        $filename = preg_replace('/\s+/', '-', $filename);
        $filename = trim($filename, '-');
        $filename = strtolower($filename);
        
        return $filename ?: 'feed-' . time();
    }

    /**
     * Update or create index file with all available feeds
     */
    private function updateFeedIndex(string $filename, array $data): void
    {
        $indexPath = public_path('rss/index.json');
        
        // Load existing index or create new
        $feeds = [];
        if (File::exists($indexPath)) {
            $feeds = json_decode(File::get($indexPath), true) ?? [];
        }

        // Add or update current feed
        $feeds[$filename] = [
            'anime_name' => $data['animeName'],
            'batch_name' => $data['batchName'],
            'link' => $data['link'],
            'poster' => $data['poster'],
            'created_at' => now()->toISOString(),
            'rss_url' => url('rss/' . $filename),
        ];

        // Save updated index
        File::put($indexPath, json_encode($feeds, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Show all available RSS feeds
     */
    public function viewAllFeeds(): void
    {
        $indexPath = public_path('rss/index.json');
        
        if (!File::exists($indexPath)) {
            Notification::make()
                ->title('Tidak ada RSS feeds')
                ->warning()
                ->send();
            return;
        }

        $feeds = json_decode(File::get($indexPath), true) ?? [];
        
        if (empty($feeds)) {
            Notification::make()
                ->title('Tidak ada RSS feeds tersedia')
                ->warning()
                ->send();
            return;
        }

        $feedList = collect($feeds)->map(function ($feed, $filename) {
            return "• {$feed['anime_name']} - {$feed['batch_name']} → {$filename}";
        })->join("\n");

        Notification::make()
            ->title('RSS Feeds Tersedia (' . count($feeds) . ')')
            ->body($feedList)
            ->info()
            ->duration(10000) // Show for 10 seconds
            ->send();
    }
}