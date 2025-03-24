<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-lg font-medium text-sm text-white dark:text-gray-100 tracking-wide hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-800 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-150 ease-in-out shadow-sm hover:shadow']) }}>
    {{ $slot }}
</button>
