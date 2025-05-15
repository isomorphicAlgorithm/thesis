export default function themeSwitcher() {
    return {
        isDark: localStorage.getItem('theme') === 'dark',

        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },

        init() {
           this.isDark = localStorage.getItem('theme') === 'dark';
        }
    }
}