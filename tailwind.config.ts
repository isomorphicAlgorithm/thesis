import type { Config } from 'tailwindcss'
interface ExtendedConfig extends Config {
    safelist?: string[];
}

const config: ExtendedConfig = {
    darkMode: 'class',
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.{js,ts,vue}'
    ],
    safelist: [
    ],
    theme: {
        extend: {
        },
    },
    plugins: [],
};

export default config;