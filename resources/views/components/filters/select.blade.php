@props(['name' => ''])

<select name="{{ $name }}" onchange="this.form.submit()" {{ $attributes->merge(['class' => 'block w-40 pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 rounded-lg bg-white']) }}>
    {{ $slot }}
</select>
