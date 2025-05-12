import type { Config } from 'tailwindcss'

const config: Config = {
  darkMode: 'class',
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.{js,ts,vue}',
  ],
  theme: {
    extend: {
      colors: {
        imdbYellow: '#F5C518',
      },
    },
  },
  plugins: [],
};

export default config;