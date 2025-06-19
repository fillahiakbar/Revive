<x-admin-layout>
<h1 class="text-2xl font-bold mb-4">Manage Download Links for: {{ $anime['title'] ?? 'Unknown' }}</h1>

<h2 class="text-lg font-semibold mb-2">Existing Links</h2>
<ul>
    @foreach ($links as $link)
        <li class="mb-1">
            <a href="{{ $link->url }}" target="_blank">{{ $link->quality }}</a>
            <form action="{{ route('admin.download.delete', $link) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 ml-2">Delete</button>
            </form>
        </li>
    @endforeach
</ul>

<h2 class="text-lg font-semibold mt-4">Add New Download Links</h2>
<form action="{{ route('admin.download.store', $anime['mal_id']) }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
        <input type="url" name="torrent_url" placeholder="Torrent URL" class="border p-2 w-full">
        <input type="url" name="mp4upload_url" placeholder="Mp4upload URL" class="border p-2 w-full">
        <input type="url" name="gdrive_url" placeholder="Google Drive URL" class="border p-2 w-full">
        <input type="url" name="subtitle_url" placeholder="MP4 with Subtitle URL" class="border p-2 w-full">
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2">Save Download Links</button>
</form>

</x-admin-layout>
