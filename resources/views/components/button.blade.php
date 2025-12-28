@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses() . ' inline-flex']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $buttonClasses()])->merge(['type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
