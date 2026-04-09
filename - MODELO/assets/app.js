(function () {
  const forms = document.querySelectorAll('[data-search-form]');
  forms.forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const input = form.querySelector('input[name="q"]');
      const query = input ? input.value.trim() : '';
      if (!query) return;
      window.location.href = `search.html?q=${encodeURIComponent(query)}`;
    });
  });

  const mobileTrigger = document.querySelector('[data-js="open-search"]');
  if (mobileTrigger) {
    mobileTrigger.addEventListener('click', () => {
      const target = document.querySelector('[data-js="hero-search"] input[name="q"]');
      if (target) target.focus();
    });
  }

  const searchPageInput = document.querySelector('[data-js="search-page-query"]');
  if (searchPageInput) {
    const params = new URLSearchParams(window.location.search);
    const q = params.get('q');
    if (q) searchPageInput.value = q;
  }
})();
