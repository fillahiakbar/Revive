@if ($errors->any())
    <div {{ $attributes }} class="text-center">
        <div class="font-medium text-red-600">{{ __('المعذرة! حدث خطأ') }}</div>

        <div class="mt-1 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif
