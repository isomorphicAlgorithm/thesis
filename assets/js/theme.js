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