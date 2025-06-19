<?php

namespace App\Http\Livewire\Forms;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class AnimeAutocompleteField extends Component
{
    public string $query = '';
    public array $results = [];
    public ?string $title = null;
    public ?int $mal_id = null;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $response = Http::get('https://api.jikan.moe/v4/anime', [
            'q' => $this->query,
            'limit' => 10,
        ]);

        if ($response->successful()) {
            $this->results = collect($response['data'])->map(function ($item) {
                return [
                    'title' => $item['title'],
                    'mal_id' => $item['mal_id'],
                    'image_url' => $item['images']['jpg']['image_url'] ?? null,
                ];
            })->toArray();
        }
    }

    public function select($title, $malId)
    {
        $this->title = $title;
        $this->mal_id = $malId;
        $this->query = $title;
        $this->results = [];

        $this->dispatch('animeSelected', title: $title, malId: $malId);
    }

    public function render()
    {
        return view('livewire.forms.anime-autocomplete-field');
    }
}
