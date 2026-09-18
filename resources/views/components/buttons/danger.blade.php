<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-sm']) }}>
    {{ $slot }}
</button>
