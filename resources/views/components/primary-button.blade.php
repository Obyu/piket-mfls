<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-brand-300 border border-transparent rounded-xl font-semibold text-xs text-navy-900 uppercase tracking-widest hover:bg-brand-200 focus:bg-brand-400 active:bg-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-300 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
