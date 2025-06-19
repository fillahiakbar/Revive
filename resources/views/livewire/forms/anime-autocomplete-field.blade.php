<div x-data="{
    query: '',
    results: [],
    async searchAnime() {
        if (this.query.length < 3) {
            this.results = [];
            return;
        }
        const response = await fetch(`https://api.jikan.moe/v4/anime?q=${this.query}&limit=10`);
        const data = await response.json();
        this.results = data.data.map(item => ({
            title: item.title,
            mal_id: item.mal_id,
            image_url: item.images.jpg.image_url
        }));
    },
    selectAnime(anime) {
        this.query = anime.title;
        this.results = [];

        const form = document.querySelector('form');
        const titleInput = form.querySelector('input[name=title]');
        const malInput = form.querySelector('input[name=mal_id]');

        if (titleInput && malInput) {
            titleInput.value = anime.title;
            malInput.value = anime.mal_id;

            titleInput.dispatchEvent(new Event('input'));
            malInput.dispatchEvent(new Event('input'));
        }
    }
}" class="relative">
    <input
        type="text"
        x-model="query"
        @input.debounce.500ms="searchAnime"
        placeholder="Cari judul anime..."
        class="w-full px-4 py-2 border border-gray-300 rounded shadow text-black bg-white"
    />

    <template x-if="results.length > 0">
        <ul class="absolute z-10 bg-white border border-gray-300 mt-1 w-full max-h-60 overflow-y-auto rounded shadow">
            <template x-for="anime in results" :key="anime.mal_id">
                <li @click="selectAnime(anime)" class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <img :src="anime.image_url" class="w-8 h-10 mr-3 object-cover rounded" />
                    <span x-text="anime.title" class="text-sm text-black"></span>
                </li>
            </template>
        </ul>
    </template>
</div>
