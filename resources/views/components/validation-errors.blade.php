@if ($errors->any())
    <div {{ $attributes }}>
        @foreach ($errors->all() as $error)
            <div class="text-sm text-red-600 mb-2">{{ $error }}</div>
        @endforeach
    </div>
@endif
