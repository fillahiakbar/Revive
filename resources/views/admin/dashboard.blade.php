<x-admin-layout>
@section('content')
<h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
<p>Total Users: <strong>{{ $total_users }}</strong></p>

<h2 class="text-xl mt-6 mb-2">Spotlight List</h2>
<ul>
    @foreach($spotlights as $spotlight)
        @php $anime = $spotlight->getAnimeData(); @endphp
        <li class="mb-2">
            <strong>{{ $spotlight->title }}</strong> —
            <a href="{{ route('anime.show', $spotlight->mal_id) }}">{{ $anime['title'] ?? 'Unknown' }}</a>
            <form action="{{ route('admin.spotlight.delete', $spotlight) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 ml-2">Delete</button>
            </form>
        </li>
    @endforeach
</ul>

<h2 class="text-xl mt-6 mb-2">Add Spotlight</h2>
<form action="{{ route('admin.spotlight.store') }}" method="POST">
    @csrf
    <input type="text" name="title" placeholder="Spotlight Title" required class="border p-1">
    <input type="number" name="mal_id" placeholder="MAL ID" required class="border p-1">
    <button type="submit" class="bg-blue-600 text-white px-4 py-1">Add</button>
</form>
</x-admin-layout>