@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-2 border-black focus:border-black focus:ring-black rounded-none shadow-sm']) !!}>
