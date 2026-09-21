<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-dict-blue border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-dict-accent focus:bg-dict-accent active:bg-dict-accent focus:outline-none focus:ring-2 focus:ring-dict-blue focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>