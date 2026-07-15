@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-navy-100 focus:border-brand-300 focus:ring-brand-300 rounded-xl shadow-sm']) }}>
