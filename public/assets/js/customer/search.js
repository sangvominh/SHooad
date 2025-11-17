(function(){
  const input = document.getElementById('searchInput');
  const dropdown = document.getElementById('searchDropdown');
  const wrapper = document.getElementById('searchWrapper');
  if (!input || !dropdown || !wrapper) return;

  let debounceTimer = null;
  let abortController = null;
  let results = [];
  let activeIndex = -1;

  function clearResults() {
    results = [];
    activeIndex = -1;
    dropdown.innerHTML = '';
    dropdown.classList.add('hidden');
  }

  function showLoading(){
    dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-gray-600">Đang tìm...</div>';
    dropdown.classList.remove('hidden');
  }

  function renderResults(items) {
    dropdown.innerHTML = '';
    if (!items || items.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'px-3 py-2 text-sm text-gray-600';
      empty.textContent = 'Không có kết quả';
      dropdown.appendChild(empty);
      dropdown.classList.remove('hidden');
      results = [];
      activeIndex = -1;
      return;
    }
    const frag = document.createDocumentFragment();

    items.forEach((item, idx) => {
      const a = document.createElement('a');
      a.href = item.url;
      a.className = 'flex items-center gap-3 px-3 py-2 hover:bg-gray-50 cursor-pointer';

      const img = document.createElement('img');
      img.src = item.image || '/SHooad/public/assets/logo/default-avatar.png';
      img.alt = item.name;
      img.className = 'w-10 h-10 object-cover rounded';

      const info = document.createElement('div');
      info.className = 'flex-1 min-w-0';

      const name = document.createElement('div');
      name.className = 'text-sm text-gray-900 truncate';
      name.textContent = item.name;

      const price = document.createElement('div');
      price.className = 'text-xs text-gray-600';
      price.textContent = formatPrice(item.price);

      info.appendChild(name);
      info.appendChild(price);
      a.appendChild(img);
      a.appendChild(info);

      a.addEventListener('mouseenter', () => {
        setActiveIndex(idx);
      });

      frag.appendChild(a);
    });

    dropdown.appendChild(frag);
    dropdown.classList.remove('hidden');
  }

  function setActiveIndex(idx){
    const links = dropdown.querySelectorAll('a');
    links.forEach((el, i) => {
      if (i === idx) {
        el.classList.add('bg-gray-50');
      } else {
        el.classList.remove('bg-gray-50');
      }
    });
    activeIndex = idx;
  }

  function formatPrice(value){
    if (typeof value !== 'number' || isNaN(value)) return '';
    try {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(value);
    } catch(e) {
      return value + ' VND';
    }
  }

  function fetchResults(q){
    if (abortController) abortController.abort();
    abortController = new AbortController();

    showLoading();
    fetch('/SHooad/public/customer/search-products?q=' + encodeURIComponent(q), {
      signal: abortController.signal
    })
      .then(r => r.ok ? r.json() : { items: [] })
      .then(data => {
        results = data.items || [];
        renderResults(results);
      })
      .catch(err => {
        if (err.name === 'AbortError') return;
        clearResults();
      });
  }

  input.addEventListener('input', () => {
    const q = input.value.trim();
    if (q.length < 1) {
      clearResults();
      return;
    }
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchResults(q), 250);
  });

  input.addEventListener('focus', () => {
    const q = input.value.trim();
    if (q.length >= 1) {
      if (results.length === 0) {
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchResults(q), 0);
      } else {
        dropdown.classList.remove('hidden');
      }
    }
  });

  input.addEventListener('keydown', (e) => {
    const visible = !dropdown.classList.contains('hidden');
    if (!visible) return;
    const links = dropdown.querySelectorAll('a');
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setActiveIndex((activeIndex + 1) % links.length);
      links[activeIndex].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setActiveIndex((activeIndex - 1 + links.length) % links.length);
      links[activeIndex].scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && links[activeIndex]) {
        e.preventDefault();
        window.location.href = links[activeIndex].href;
      }
    } else if (e.key === 'Escape') {
      clearResults();
    }
  });

  document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) {
      clearResults();
    }
  });
})();
