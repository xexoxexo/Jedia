<select {{ $attributes->merge(['class' => $selectClasses()]) }}>
    {{ $slot }}
</select>
