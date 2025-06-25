export function searchDropdown() {
  return {
    query: '',
    results: [],
    open: false,
    selectedIndex: -1,

    init() {
      this.$watch('query', () => this.fetchResults());
    },

    fetchResults() {
      if (this.query.length < 2) {
        this.results = [];
        this.open = false;
        return;
      }

      fetch(`/autocomplete?q=${encodeURIComponent(this.query)}`)
        .then(res => res.json())
        .then(data => {
          this.results = data;
          this.open = true;
          this.selectedIndex = 0;
        })
        .catch((err) => console.error("Search fetch error:", err));
    },

    highlightNext() {
      if (this.selectedIndex < this.results.length - 1) this.selectedIndex++;
    },

    highlightPrev() {
      if (this.selectedIndex > 0) this.selectedIndex--;
    },

    goToSelected() {
      if (this.results.length === 0) return;
      const item = this.results[this.selectedIndex] ?? this.results[0];
      this.navigate(item);
    },

    navigate(item) {
      let path;
      switch(item.type) {
        case 'Band': path = `/bands/${item.id}-${item.slug}`; break;
        case 'Musician': path = `/musicians/${item.id}-${item.slug}`; break;
        case 'Album': path = `/albums/${item.id}-${item.slug}`; break;
        case 'Song': path = `/songs/${item.id}-${item.slug}`; break;
        default: path = '/';
      }
      window.location.href = path;
    }
  };
}