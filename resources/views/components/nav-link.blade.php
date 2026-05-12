@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-medium text-indigo-700 bg-indigo-50 border-l-4 border-indigo-400 transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-l-4 border-transparent hover:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
