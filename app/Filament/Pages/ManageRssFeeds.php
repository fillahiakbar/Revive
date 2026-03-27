<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\RssGeneratorService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;

class ManageRssFeeds extends Page implements HasActions
{
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static string $view = 'filament.pages.manage-rss-feeds';
    protected static ?string $title = 'Manage RSS Feeds';

    public array $feeds = [];
    public string $searchQuery = '';

    public function mount(): void
    {
        $this->loadFeeds();
    }

    public function loadFeeds(): void
    {
        $this->feeds = RssGeneratorService::loadExistingFeeds();
    }

    public function getFilteredFeedsProperty(): array
    {
        if (empty($this->searchQuery)) {
            return $this->feeds;
        }

        return array_filter($this->feeds, function ($feed) {
            $animeMatch = stripos($feed['anime_name'] ?? '', $this->searchQuery) !== false;
            $batchMatch = stripos($feed['batch_name'] ?? '', $this->searchQuery) !== false;
            return $animeMatch || $batchMatch;
        });
    }

    private function getFeedData(string $id): array
    {
        $feed = collect($this->feeds)->firstWhere('id', $id);
        if (!$feed) return [];

        return [
            'anime_name' => $feed['anime_name'] ?? '',
            'batch_name' => $feed['batch_name'] ?? '',
            'link' => $feed['link'] ?? '',
            'poster' => $feed['poster'] ?? '',
        ];
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Edit')
            ->icon('heroicon-s-pencil-square')
            ->color('warning')
            ->button()
            ->size('sm')
            ->form([
                TextInput::make('anime_name')
                    ->label('Anime Name')
                    ->required(),

                TextInput::make('batch_name')
                    ->label('Batch Name')
                    ->required(),

                FileUpload::make('poster')
                    ->label('Picture')
                    ->image()
                    ->directory('custom-rss')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),

                TextInput::make('link')
                    ->label('Link ke Website')
                    ->required()
                    ->url(),
            ])
            ->fillForm(fn (array $arguments) => $this->getFeedData($arguments['id']))
            ->action(function (array $data, array $arguments) {
                // Find and update in the array
                $id = $arguments['id'];
                $index = collect($this->feeds)->search(fn ($item) => $item['id'] === $id);
                
                if ($index !== false) {
                    $this->feeds[$index] = array_merge($this->feeds[$index], $data);
                    
                    // Save to JSON and Regenerate XML
                    RssGeneratorService::updateFromFeeds($this->feeds);

                    Notification::make()
                        ->title('RSS Feed updated successfully!')
                        ->success()
                        ->send();
                        
                    $this->loadFeeds(); // refresh correctly
                }
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Delete')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->button()
            ->size('sm')
            ->requiresConfirmation()
            ->modalHeading('Delete RSS Item')
            ->modalDescription('Are you sure you want to delete this specific RSS item? This will not delete all feeds.')
            ->modalSubmitActionLabel('Yes, delete it')
            ->action(function (array $arguments) {
                $id = $arguments['id'];
                
                // Filter out the deleted item
                $this->feeds = collect($this->feeds)->reject(function ($item) use ($id) {
                    return $item['id'] === $id;
                })->values()->toArray();

                // Save to JSON and Regenerate XML
                RssGeneratorService::updateFromFeeds($this->feeds);

                Notification::make()
                    ->title('RSS Feed specific item deleted!')
                    ->success()
                    ->send();
            });
    }
}
