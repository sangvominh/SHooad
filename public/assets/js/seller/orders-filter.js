// Orders search, filter and sort functionality
class OrdersFilter {
  constructor() {
    this.searchInput = document.getElementById('orders-search');
    this.filterBtn = document.getElementById('orders-filter-btn');
    this.sortBtn = document.getElementById('orders-sort-btn');
    this.filterDropdown = document.getElementById('orders-filter-dropdown');
    this.sortDropdown = document.getElementById('orders-sort-dropdown');
    this.tableBody = document.getElementById('orders-table-body');
    this.appliedFiltersContainer = document.getElementById('orders-applied-filters');
    
    this.orders = [];
    this.currentFilters = {
      search: '',
      status: 'all',
      sortBy: 'date-desc'
    };
    
    this.init();
  }
  
  init() {
    // Store original orders data from table
    this.storeOriginalData();
    
    // Event listeners
    if (this.searchInput) {
      this.searchInput.addEventListener('input', (e) => {
        this.currentFilters.search = e.target.value.toLowerCase();
        this.applyFilters();
      });
    }
    
    if (this.filterBtn) {
      this.filterBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggleDropdown(this.filterDropdown);
        if (this.sortDropdown) this.sortDropdown.classList.add('hidden');
      });
    }
    
    if (this.sortBtn) {
      this.sortBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggleDropdown(this.sortDropdown);
        if (this.filterDropdown) this.filterDropdown.classList.add('hidden');
      });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.filter-container') && !e.target.closest('.sort-container')) {
        if (this.filterDropdown) this.filterDropdown.classList.add('hidden');
        if (this.sortDropdown) this.sortDropdown.classList.add('hidden');
      }
    });
    
    // Filter options
    document.querySelectorAll('[data-filter-status]').forEach(btn => {
      btn.addEventListener('click', () => {
        this.currentFilters.status = btn.dataset.filterStatus;
        this.applyFilters();
        this.filterDropdown.classList.add('hidden');
      });
    });
    
    // Sort options
    document.querySelectorAll('[data-sort]').forEach(btn => {
      btn.addEventListener('click', () => {
        this.currentFilters.sortBy = btn.dataset.sort;
        this.applyFilters();
        this.sortDropdown.classList.add('hidden');
      });
    });
    
    // Clear filters
    const clearBtn = document.getElementById('orders-clear-filters');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        this.clearFilters();
      });
    }
  }
  
  storeOriginalData() {
    if (!this.tableBody) return;
    
    const rows = this.tableBody.querySelectorAll('tr[data-order]');
    rows.forEach(row => {
      const order = {
        element: row,
        id: row.dataset.orderId,
        customerName: row.dataset.customerName?.toLowerCase() || '',
        customerEmail: row.dataset.customerEmail?.toLowerCase() || '',
        customerPhone: row.dataset.customerPhone?.toLowerCase() || '',
        status: row.dataset.status?.toLowerCase() || '',
        date: new Date(row.dataset.date || Date.now()),
        total: parseFloat(row.dataset.total || 0)
      };
      this.orders.push(order);
    });
  }
  
  toggleDropdown(dropdown) {
    if (!dropdown) return;
    dropdown.classList.toggle('hidden');
  }
  
  applyFilters() {
    let filtered = [...this.orders];
    
    // Search filter (phone or email)
    if (this.currentFilters.search) {
      filtered = filtered.filter(order => 
        order.customerEmail.includes(this.currentFilters.search) ||
        order.customerPhone.includes(this.currentFilters.search) ||
        order.customerName.includes(this.currentFilters.search)
      );
    }
    
    // Status filter
    if (this.currentFilters.status !== 'all') {
      filtered = filtered.filter(order => 
        order.status.toLowerCase() === this.currentFilters.status.toLowerCase()
      );
    }
    
    // Sort
    filtered = this.sortOrders(filtered);
    
    // Update UI
    this.renderOrders(filtered);
    this.updateAppliedFilters();
  }
  
  sortOrders(orders) {
    const sortBy = this.currentFilters.sortBy;
    
    return orders.sort((a, b) => {
      switch(sortBy) {
        case 'date-desc':
          return b.date - a.date;
        case 'date-asc':
          return a.date - b.date;
        case 'total-desc':
          return b.total - a.total;
        case 'total-asc':
          return a.total - b.total;
        case 'name-asc':
          return a.customerName.localeCompare(b.customerName);
        case 'name-desc':
          return b.customerName.localeCompare(a.customerName);
        default:
          return 0;
      }
    });
  }
  
  renderOrders(orders) {
    if (!this.tableBody) return;
    
    // Clear table
    this.tableBody.innerHTML = '';
    
    if (orders.length === 0) {
      const colspan = this.tableBody.parentElement.querySelector('thead tr th').parentElement.children.length;
      this.tableBody.innerHTML = `
        <tr>
          <td colspan="${colspan}" class="px-6 py-8 text-center text-gray-500">
            No orders found matching your criteria
          </td>
        </tr>
      `;
      return;
    }
    
    // Append filtered orders
    orders.forEach(order => {
      this.tableBody.appendChild(order.element);
    });
  }
  
  updateAppliedFilters() {
    if (!this.appliedFiltersContainer) return;
    
    const filters = [];
    
    if (this.currentFilters.search) {
      filters.push({
        label: `Search: "${this.currentFilters.search}"`,
        key: 'search'
      });
    }
    
    if (this.currentFilters.status !== 'all') {
      const statusLabel = this.currentFilters.status.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
      filters.push({
        label: `Status: ${statusLabel}`,
        key: 'status'
      });
    }
    
    if (this.currentFilters.sortBy !== 'date-desc') {
      const sortLabels = {
        'date-asc': 'Date: Oldest First',
        'date-desc': 'Date: Newest First',
        'total-desc': 'Total: High to Low',
        'total-asc': 'Total: Low to High',
        'name-asc': 'Name: A to Z',
        'name-desc': 'Name: Z to A'
      };
      filters.push({
        label: sortLabels[this.currentFilters.sortBy] || this.currentFilters.sortBy,
        key: 'sortBy'
      });
    }
    
    if (filters.length === 0) {
      this.appliedFiltersContainer.classList.add('hidden');
      return;
    }
    
    this.appliedFiltersContainer.classList.remove('hidden');
    const filtersHtml = filters.map(filter => `
      <span class="inline-flex items-center gap-1 px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm">
        ${filter.label}
        <button onclick="ordersFilter.removeFilter('${filter.key}')" class="hover:text-teal-900">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </span>
    `).join('');
    
    this.appliedFiltersContainer.innerHTML = `
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-sm text-gray-600">Active filters:</span>
        ${filtersHtml}
      </div>
    `;
  }
  
  removeFilter(key) {
    if (key === 'search') {
      this.currentFilters.search = '';
      if (this.searchInput) this.searchInput.value = '';
    } else if (key === 'status') {
      this.currentFilters.status = 'all';
    } else if (key === 'sortBy') {
      this.currentFilters.sortBy = 'date-desc';
    }
    this.applyFilters();
  }
  
  clearFilters() {
    this.currentFilters = {
      search: '',
      status: 'all',
      sortBy: 'date-desc'
    };
    if (this.searchInput) this.searchInput.value = '';
    this.applyFilters();
  }
  
  capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
}

// Initialize when DOM is ready
let ordersFilter;
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('orders-table-body')) {
    ordersFilter = new OrdersFilter();
  }
});
