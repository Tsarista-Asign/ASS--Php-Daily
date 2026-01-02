document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('search');
  const filtersContainer = document.getElementById('filters');
  const cards = Array.from(document.querySelectorAll('.card[data-url]'));
  const modal = document.getElementById('modal');
  const modalTitle = document.getElementById('modalTitle');
  const previewIframe = document.getElementById('preview');
  const openNewTabBtn = document.getElementById('openNewTab');
  const closeModalBtn = document.getElementById('closeModal');
  const themeToggle = document.getElementById('themeToggle');

  // Extract unique tags from cards
  const allTags = ['all', ...new Set(
    cards.flatMap(card => {
      return Array.from(card.querySelectorAll('.tags .badge'))
        .map(tag => tag.textContent.replace('#', '').trim().toLowerCase())
        .filter(tag => tag);
    })
  )];

  // Debug: Log tags to verify
  console.log('Available Tags:', allTags);

  // Create filter chips
  allTags.forEach(tag => {
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.textContent = tag === 'all' ? 'All' : `#${tag}`;
    chip.dataset.tag = tag;
    chip.addEventListener('click', () => {
      document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      console.log('Selected Tag:', tag); // Debug
      filterCards();
    });
    filtersContainer.appendChild(chip);
  });
  // Set default filter to 'all'
  filtersContainer.querySelector('.chip[data-tag="all"]').classList.add('active');

  // Debounce function for search
  function debounce(fn, delay) {
    let timeout;
    return (...args) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn(...args), delay);
    };
  }

  // Filter cards based on search and tag
  function filterCards() {
    const q = searchInput.value.trim().toLowerCase().replace(/^#/, '');
    const activeTag = filtersContainer.querySelector('.chip.active').dataset.tag.toLowerCase();
    let visibleCount = 0;
    cards.forEach(card => {
      const name = card.querySelector('.name span:not(.badge)')?.textContent.toLowerCase() || '';
      const desc = card.querySelector('.desc')?.textContent.toLowerCase() || '';
      const tags = Array.from(card.querySelectorAll('.tags .badge'))
        .map(tag => tag.textContent.replace('#', '').trim().toLowerCase());
      console.log('Card Tags:', tags); // Debug
      const matchSearch = !q || name.includes(q) || desc.includes(q) || tags.includes(q);
      const matchTag = activeTag === 'all' || tags.includes(activeTag);
      card.style.display = matchSearch && matchTag ? '' : 'none';
      if (matchSearch && matchTag) visibleCount++;
    });
    // Show no results message if needed
    const grid = document.getElementById('grid');
    const noResults = grid.querySelector('.no-results');
    if (visibleCount === 0 && !noResults) {
      const message = document.createElement('p');
      message.className = 'no-results';
      message.textContent = 'Không tìm thấy dự án nào.';
      grid.appendChild(message);
    } else if (noResults && visibleCount > 0) {
      noResults.remove();
    }
  }

  // Debounced filter
  const debouncedFilter = debounce(filterCards, 300);
  searchInput.addEventListener('input', debouncedFilter);

  // Trigger initial filter
  filterCards();

  // Card click to open modal
  cards.forEach(card => {
    card.addEventListener('click', () => {
      const url = card.getAttribute('data-url');
      const name = card.querySelector('.name span:not(.badge)')?.textContent || 'Xem trước';
      modalTitle.textContent = name;
      previewIframe.src = url;
      openNewTabBtn.href = url;
      modal.classList.add('show');
      closeModalBtn.focus(); // Accessibility
    });
    card.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  // Modal controls
  closeModalBtn.addEventListener('click', () => {
    modal.classList.remove('show');
    previewIframe.src = 'about:blank'; // Reset iframe
    searchInput.focus(); // Return focus
  });

  // Close modal on Esc key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
      closeModalBtn.click();
    }
  });

  // Load theme from localStorage or system preference with error handling
  const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
  let defaultTheme = prefersLight ? 'light' : 'dark';
  let savedTheme = defaultTheme;
  try {
    savedTheme = localStorage.getItem('theme') || defaultTheme;
  } catch (e) {
    console.warn('Không thể truy cập localStorage:', e.message);
  }
  document.documentElement.setAttribute('data-theme', savedTheme);
  updateThemeToggleIcon(savedTheme);

  // Theme toggle button
  themeToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', newTheme);
    try {
      localStorage.setItem('theme', newTheme);
    } catch (e) {
      console.warn('Không thể lưu vào localStorage:', e.message);
    }
    updateThemeToggleIcon(newTheme);
  });

  // Update theme toggle button icon
  function updateThemeToggleIcon(theme) {
    themeToggle.textContent = theme === 'dark' ? '☀️' : '🌓';
  }
});