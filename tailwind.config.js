import preset from './../../vendor/filament/support/tailwind.config.preset'

module.exports = {
    darkMode: 'class',
    presets: [preset],
    mode: 'jit',
    content: [
        // './app/Filament/**/*.php',
        './resources/views/partials/*.blade.php',
        './resources/views/livewire/*.blade.php',
        './resources/views/components/*.blade.php',
        './resources/views/*.blade.php',
        // './vendor/filament/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],
    safelist: [
        'bg-orange-500',
        'hover:bg-orange-600',
    ],
    plugins: [
        require('flowbite/plugin'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('tailwindcss-textshadow'),
        require('flowbite/plugin')
    ],
}
