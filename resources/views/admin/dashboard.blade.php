<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white">
            <div class="bg-gray-900 p-6 rounded shadow">
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                <p>Halo, {{ Auth::user()->name }}. Anda login sebagai admin.</p>
            </div>
        </div>
    </div>
</x-app-layout>
