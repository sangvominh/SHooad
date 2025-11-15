// Products search and filter functionality
class ProductsFilter {
  constructor() {
    this.searchInput = document.getElementById('products-search');
    this.filterBtn = document.getElementById('products-filter-btn');
    this.sortBtn = document.getElementById('products-sort-btn');
    this.filterDropdown = document.getElementById('products-filter-dropdown');
    this.sortDropdown = document.getElementById('products-sort-dropdown');
    this.tableBody = document.getElementById('products-table-body');
    this.appliedFiltersContainer = document.getElementById('products-applied-filters');
    
    this.products = [];
    this.currentFilters = {
      search: '',
      status: 'all',
      stock: 'all',
      sortBy: 'newest'
    };
    
    this.init();
  }
  
  init() {
    // Store original products data from table
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
    document.querySelectorAll('[data-filter-product-status]').forEach(btn => {
      btn.addEventListener('click', () => {
        this.currentFilters.status = btn.dataset.filterProductStatus;
        this.applyFilters();
        this.filterDropdown.classList.add('hidden');
      });
    });
    
    document.querySelectorAll('[data-filter-stock]').forEach(btn => {
      btn.addEventListener('click', () => {
        this.currentFilters.stock = btn.dataset.filterStock;
        this.applyFilters();
        this.filterDropdown.classList.add('hidden');
      });
    });
    
    // Sort options
    document.querySelectorAll('[data-sort-product]').forEach(btn => {
      btn.addEventListener('click', () => {
        this.currentFilters.sortBy = btn.dataset.sortProduct;
        this.applyFilters();
        this.sortDropdown.classList.add('hidden');
      });
    });
    
    // Clear filters
    const clearBtn = document.getElementById('products-clear-filters');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        this.clearFilters();
      });
    }
  }
  
  storeOriginalData() {
    if (!this.tableBody) return;
    
    const rows = this.tableBody.querySelectorAll('tr[data-product]');
    rows.forEach(row => {
      const product = {
        element: row,
        id: row.dataset.productId,
        name: row.dataset.productName?.toLowerCase() || '',
        brand: row.dataset.productBrand?.toLowerCase() || '',
        status: row.dataset.productStatus?.toLowerCase() || '',
        stock: parseInt(row.dataset.productStock || 0),
        price: parseFloat(row.dataset.productPrice || 0),
        sold: parseInt(row.dataset.productSold || 0),
        createdAt: new Date(row.dataset.productCreated || Date.now())
      };
      this.products.push(product);
    });
  }
  
  toggleDropdown(dropdown) {
    if (!dropdown) return;
    dropdown.classList.toggle('hidden');
  }
  
  applyFilters() {
    let filtered = [...this.products];
    
    // Search filter (product name)
    if (this.currentFilters.search) {
      filtered = filtered.filter(product => 
        product.name.includes(this.currentFilters.search) ||
        product.brand.includes(this.currentFilters.search)
      );
    }
    
    // Status filter
    if (this.currentFilters.status !== 'all') {
      filtered = filtered.filter(product => 
        product.status.toLowerCase() === this.currentFilters.status.toLowerCase()
      );
    }
    
    // Stock filter
    if (this.currentFilters.stock !== 'all') {
      filtered = filtered.filter(product => {
        switch(this.currentFilters.stock) {
          case 'in-stock':
            return product.stock > 0;
          case 'low-stock':
            return product.stock > 0 && product.stock <= 10;
          case 'out-of-stock':
            return product.stock === 0;
          default:
            return true;
        }
      });
    }
    
    // Sort
    filtered = this.sortProducts(filtered);
    
    // Update UI
    this.renderProducts(filtered);
    this.updateAppliedFilters();
  }
  
  sortProducts(products) {
    const sortBy = this.currentFilters.sortBy;
    
    return products.sort((a, b) => {
      switch(sortBy) {
        case 'newest':
          return new Date(b.createdAt) - new Date(a.createdAt);
        case 'oldest':
          return new Date(a.createdAt) - new Date(b.createdAt);
        case 'price-desc':
          return b.price - a.price;
        case 'price-asc':
          return a.price - b.price;
        case 'name-asc':
          return a.name.localeCompare(b.name);
        case 'name-desc':
          return b.name.localeCompare(a.name);
        case 'stock-desc':
          return b.stock - a.stock;
        case 'stock-asc':
          return a.stock - b.stock;
        case 'sold-desc':
          return b.sold - a.sold;
        case 'sold-asc':
          return a.sold - b.sold;
        default:
          return 0;
      }
    });
  }
  
  renderProducts(products) {
    if (!this.tableBody) return;
    
    // Clear table
    this.tableBody.innerHTML = '';
    
    if (products.length === 0) {
      const colspan = this.tableBody.parentElement.querySelector('thead tr th').parentElement.children.length;
      this.tableBody.innerHTML = `
        <tr>
          <td colspan="${colspan}" class="px-6 py-8 text-center text-gray-500">
            No products found matching your criteria
          </td>
        </tr>
      `;
      return;
    }
    
    // Append filtered products
    products.forEach(product => {
      this.tableBody.appendChild(product.element);
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
      filters.push({
        label: `Status: ${this.capitalize(this.currentFilters.status)}`,
        key: 'status'
      });
    }
    
    if (this.currentFilters.stock !== 'all') {
      const stockLabels = {
        'in-stock': 'In Stock',
        'low-stock': 'Low Stock',
        'out-of-stock': 'Out of Stock'
      };
      filters.push({
        label: `Stock: ${stockLabels[this.currentFilters.stock]}`,
        key: 'stock'
      });
    }
    
    if (this.currentFilters.sortBy !== 'newest') {
      const sortLabels = {
        'newest': 'Newest First',
        'oldest': 'Oldest First',
        'price-desc': 'Price: High to Low',
        'price-asc': 'Price: Low to High',
        'name-asc': 'Name: A to Z',
        'name-desc': 'Name: Z to A',
        'stock-desc': 'Stock: High to Low',
        'stock-asc': 'Stock: Low to High',
        'sold-desc': 'Most Sold',
        'sold-asc': 'Least Sold'
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
        <button onclick="productsFilter.removeFilter('${filter.key}')" class="hover:text-teal-900">
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
    } else if (key === 'stock') {
      this.currentFilters.stock = 'all';
    } else if (key === 'sortBy') {
      this.currentFilters.sortBy = 'newest';
    }
    this.applyFilters();
  }
  
  clearFilters() {
    this.currentFilters = {
      search: '',
      status: 'all',
      stock: 'all',
      sortBy: 'newest'
    };
    if (this.searchInput) this.searchInput.value = '';
    this.applyFilters();
  }
  
  capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
}

// Initialize when DOM is ready
let productsFilter;
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('products-table-body')) {
    productsFilter = new ProductsFilter();
  }
});
