// public/js/theme.js
/*export function themeSwitcher() {

    console.log('Theme loaded');

    const initialTheme =
        localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

    // Set the class immediately on load
    document.documentElement.classList.toggle('dark', initialTheme);

    return {
        isDark: initialTheme,
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.isDark);
            console.log('Dark mode:', this.isDark);
        },
    }
}
*/
/*
export default function themeSwitcher() {
    console.log('Theme loaded');

    const initialTheme =
      localStorage.getItem('theme') === 'dark' ||
      (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.documentElement.classList.toggle('dark', initialTheme);

    return {
      isDark: initialTheme,
      toggleTheme() {
        this.isDark = !this.isDark;
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.isDark);
        console.log('Dark mode:', this.isDark);
      },
    };
  }
*/
/*
  export default function themeSwitcher() {
    console.log('Theme loaded');

    const storedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    const initialTheme = storedTheme === 'dark' || (!storedTheme && prefersDark);

    // Set initial state
    document.documentElement.classList.toggle('dark', initialTheme);

    return {
        isDark: initialTheme,
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.isDark);
            console.log('Dark mode:', this.isDark);
        }
    };
}
*/

export default function themeSwitcher() {
  return {
      isDark: localStorage.getItem('theme') === 'dark',

      toggleTheme() {
          this.isDark = !this.isDark;
          localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', this.isDark);
      },

      init() {
          document.documentElement.classList.toggle('dark', this.isDark);
      }
  };}