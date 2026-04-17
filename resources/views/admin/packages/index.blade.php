@extends('admin.layout.layout')

@section('content')
<div class="container-fluid">
  <!-- Stats Overview -->
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon primary">
        <i class="fas fa-box"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ $packages->count() }}</div>
        <div class="stat-label">Total Packages</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon success">
        <i class="fas fa-star"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ $packages->where('is_popular', true)->count() }}</div>
        <div class="stat-label">Popular Packages</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon warning">
        <i class="fas fa-sync"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ $packages->max('synced_at') ? \Carbon\Carbon::parse($packages->max('synced_at'))->diffForHumans() : 'Never' }}</div>
        <div class="stat-label">Last Sync</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon info">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value">{{ $packages->where('active', true)->count() }}</div>
        <div class="stat-label">Active Packages</div>
      </div>
    </div>
  </div>

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title-section">
      <h1 class="page-title">
        <i class="fas fa-boxes"></i>
        Package Management
      </h1>
      <div class="page-subtitle">Manage your service packages, including strategy, ad copy, creative optimization, targeting, and reporting</div>
    </div>
    
    <div class="toolbar">
      <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="pkgSearch" class="form-control" placeholder="Search packages...">
      </div>
      
      <div class="filter-toggle" id="popularToggle">
        <i class="fas fa-star"></i>
        <span>Popular Only</span>
      </div>
      
      <form id="syncForm" method="POST" action="/admin/packages/sync-now">
        @csrf
        <button id="syncBtn" class="btn btn-primary">
          <i class="fas fa-sync-alt"></i>
          <span>Sync Now</span>
        </button>
      </form>
    </div>
  </div>

  @php
    $assignToCustomerId = request('customer_id');
  @endphp

  @if($assignToCustomerId)
    <div id="assignBar" class="assign-bar">
      <div class="assign-info">Assigning to Customer ID: <strong>{{ $assignToCustomerId }}</strong></div>
      <div class="assign-controls">
        <div class="form-group">
          <label for="assignStart">Start Date</label>
          <input type="date" id="assignStart" class="form-control">
        </div>
        <div class="form-group">
          <label for="assignEnd">End Date</label>
          <input type="date" id="assignEnd" class="form-control">
        </div>
        <div class="form-group">
          <label for="assignStatus">Status</label>
          <select id="assignStatus" class="form-control">
            <option value="active">Active</option>
            <option value="paused">Paused</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <button id="assignSelectedBtn" class="btn btn-primary">
          <i class="fas fa-check-double"></i>
          <span>Assign Selected</span>
        </button>
        <a href="{{ route('customer.details', $assignToCustomerId) }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left"></i>
          <span>Back to Customer</span>
        </a>
      </div>
    </div>
  @endif
  
  <!-- Category Tabs -->
  <div class="category-tabs-container">
    <div class="category-tabs" id="categoryTabs">
      <button class="category-tab active" data-category="all">All Packages</button>
      <button class="category-tab" data-category="meta">Meta Advertising</button>
      <button class="category-tab" data-category="education">Education & Consultancy</button>
      <button class="category-tab" data-category="manpower">Manpower</button>
      <button class="category-tab" data-category="real-estate">Real Estate</button>
      <button class="category-tab" data-category="graphics">Graphics Design</button>
      <button class="category-tab" data-category="creative">Creative & Animation</button>
    </div>
  </div>
  
  <!-- Package Grid -->
  <div class="package-grid" id="packageGrid">
    @foreach($packages as $p)
      @php
        $features = is_array($p->features) ? $p->features : [];
        $featuresCount = count($features);
        $isPopular = (bool) $p->is_popular;
        $name = $p->name ?? 'Untitled Package';
        $code = trim((string) $p->code);
        $price = is_numeric($p->price) ? number_format($p->price) : (string) $p->price;
        
        // Determine category
        $category = 'meta';
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'real estate')) $category = 'real-estate';
        elseif (str_contains($nameLower, 'manpower')) $category = 'manpower';
        elseif (str_contains($nameLower, 'education') || str_contains($nameLower, 'consultancy')) $category = 'education';
        elseif (str_contains($nameLower, 'animation') || str_contains($nameLower, 'video') || str_contains($nameLower, 'gif')) $category = 'creative';
        elseif (str_contains($nameLower, 'logo') || str_contains($nameLower, 'banner') || str_contains($nameLower, 'flyer') || 
                str_contains($nameLower, 'graphics') || str_contains($nameLower, 'design')) $category = 'graphics';
        
        $featureLabel = function($f) {
          if (is_string($f)) return $f;
          if (is_array($f)) {
            if (!empty($f['name'])) return $f['name'];
            if (!empty($f['label'])) return $f['label'];
            if (!empty($f['description'])) return $f['description'];
            if (!empty($f['feature_id'])) return 'Feature #'.substr($f['feature_id'],0,6);
          }
          return 'Feature';
        };
        
        $firstFeatures = array_slice($features, 0, 4);
        $extraFeatures = array_slice($features, 4);
      @endphp
      
      <div class="package-card" 
           data-name="{{ Str::lower($name) }}"
           data-code="{{ Str::lower($code) }}"
           data-popular="{{ $isPopular ? '1' : '0' }}"
           data-category="{{ $category }}">
        @if($isPopular)
          <div class="package-popular">
            <i class="fas fa-star"></i>
            <span>Popular</span>
          </div>
        @endif
        
        @if($assignToCustomerId)
          <label class="package-select">
            <input type="checkbox" class="pkg-select" value="{{ $p->id }}"> Select
          </label>
        @endif

        <div class="package-header">
          <h3 class="package-title">{{ $name }}</h3>
          @if($code)
            <span class="package-code">{{ $code }}</span>
          @endif
          
          <div class="package-price">
            @if(!is_numeric($p->price) || (float)$p->price <= 0)
              Custom Package
            @else
              Rs. {{ $price }}
            @endif
          </div>
          
          <div class="package-meta">
            <span class="meta-pill primary">
              <i class="fas fa-list-alt"></i>
              <span>{{ $featuresCount }} Features</span>
            </span>
            <span class="meta-pill secondary">
              <i class="fas fa-folder"></i>
              <span>{{ ucfirst(str_replace('-', ' ', $category)) }}</span>
            </span>
          </div>
        </div>
        
        <div class="package-body">
          @if($featuresCount)
            <ul class="feature-list collapsed">
              @foreach($firstFeatures as $f)
                <li class="feature-item">
                  <i class="fas fa-check feature-icon"></i>
                  <span>{{ $featureLabel($f) }}</span>
                </li>
              @endforeach
              
              @if(count($extraFeatures))
                <div class="feature-extra">
                  @foreach($extraFeatures as $f)
                    <li class="feature-item">
                      <i class="fas fa-check feature-icon"></i>
                      <span>{{ $featureLabel($f) }}</span>
                    </li>
                  @endforeach
                </div>
                
                <button class="toggle-features">
                  <span>Show {{ count($extraFeatures) }} more</span>
                  <i class="fas fa-chevron-down"></i>
                </button>
              @endif
            </ul>
          @else
            <div class="text-muted text-center py-3">
              <i class="fas fa-info-circle mb-2"></i>
              <div>No features listed</div>
            </div>
          @endif
        </div>
        
        <div class="package-footer">
          <div class="package-status">
            <span class="status-badge {{ $p->active ? 'active' : 'inactive' }}">
              {{ $p->active ? 'Active' : 'Inactive' }}
            </span>
          </div>
          <div class="sync-time">
            <i class="far fa-clock"></i>
            <span>{{ \Carbon\Carbon::parse($p->synced_at)->diffForHumans() }}</span>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  
  <!-- Empty State (hidden by default) -->
  <div class="empty-state" id="emptyState" style="display: none;">
    <div class="empty-state-icon">
      <i class="fas fa-box-open"></i>
    </div>
    <h3 class="empty-state-title">No packages found</h3>
    <p class="empty-state-text">Try adjusting your search or filter to find what you're looking for.</p>
    <button class="btn btn-outline-secondary" id="resetFilters">
      <i class="fas fa-refresh"></i>
      Reset Filters
    </button>
  </div>
  
  <!-- Debug Panel -->
  <details class="debug-panel">
    <summary class="debug-header">
      <span>Show raw table data (debug)</span>
      <i class="fas fa-chevron-down"></i>
    </summary>
    <div class="debug-content">
      <div class="table-responsive tbl-cards">
        <table class="table debug-table">
          <thead>
            <tr>
              <th>Popular</th>
              <th>Name</th>
              <th>Code</th>
              <th>Price</th>
              <th>Features Count</th>
              <th>Synced At</th>
            </tr>
          </thead>
          <tbody>
            @foreach($packages as $p)
              <tr>
                <td>@if($p->is_popular) ⭐ Popular @endif</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->code }}</td>
                <td>{{ (is_numeric($p->price) && $p->price>0) ? 'Rs. '.number_format($p->price) : 'Custom Pack' }}</td>
                <td>{{ is_array($p->features) ? count($p->features) : 0 }}</td>
                <td>{{ $p->synced_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </details>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Elements
  const packageGrid = document.getElementById('packageGrid');
  const packageCards = Array.from(document.querySelectorAll('.package-card'));
  const searchInput = document.getElementById('pkgSearch');
  const popularToggle = document.getElementById('popularToggle');
  const categoryTabs = document.querySelectorAll('.category-tab');
  const emptyState = document.getElementById('emptyState');
  const resetFiltersBtn = document.getElementById('resetFilters');
  const syncBtn = document.getElementById('syncBtn');
  const syncForm = document.getElementById('syncForm');
  
  // State
  let activeCategory = 'all';
  let showPopularOnly = false;
  let searchQuery = '';
  
  // Sync button loading state
  if (syncForm && syncBtn) {
    syncForm.addEventListener('submit', function() {
      syncBtn.disabled = true;
      syncBtn.classList.add('btn-loading');
      syncBtn.innerHTML = '<i class="fas fa-sync-alt"></i> <span>Syncing...</span>';
    });
  }
  
  // Search functionality
  searchInput.addEventListener('input', function() {
    searchQuery = this.value.trim().toLowerCase();
    filterPackages();
  });
  
  // Popular toggle
  popularToggle.addEventListener('click', function() {
    showPopularOnly = !showPopularOnly;
    this.classList.toggle('active', showPopularOnly);
    filterPackages();
  });
  
  // Category tabs
  categoryTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      // Update active tab
      categoryTabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      
      // Update active category
      activeCategory = this.getAttribute('data-category');
      filterPackages();
    });
  });
  
  // Reset filters
  resetFiltersBtn.addEventListener('click', function() {
    searchInput.value = '';
    searchQuery = '';
    
    popularToggle.classList.remove('active');
    showPopularOnly = false;
    
    categoryTabs.forEach(tab => tab.classList.remove('active'));
    categoryTabs[0].classList.add('active');
    activeCategory = 'all';
    
    filterPackages();
  });
  
  // Toggle feature visibility
  document.querySelectorAll('.toggle-features').forEach(button => {
    button.addEventListener('click', function() {
      const featureList = this.closest('.feature-list');
      const isExpanded = featureList.classList.contains('expanded');
      
      if (isExpanded) {
        featureList.classList.remove('expanded');
        featureList.classList.add('collapsed');
        this.innerHTML = '<span>Show ' + (featureList.querySelectorAll('.feature-extra .feature-item').length) + ' more</span><i class="fas fa-chevron-down"></i>';
      } else {
        featureList.classList.remove('collapsed');
        featureList.classList.add('expanded');
        this.innerHTML = '<span>Show less</span><i class="fas fa-chevron-up"></i>';
      }
    });
  });
  
  // Filter packages based on current filters
  function filterPackages() {
    let visibleCount = 0;
    
    packageCards.forEach(card => {
      const name = card.getAttribute('data-name') || '';
      const code = card.getAttribute('data-code') || '';
      const isPopular = card.getAttribute('data-popular') === '1';
      const category = card.getAttribute('data-category') || '';
      
      // Check search match
      const searchMatch = !searchQuery || 
                         name.includes(searchQuery) || 
                         code.includes(searchQuery);
      
      // Check popular filter
      const popularMatch = !showPopularOnly || isPopular;
      
      // Check category filter
      const categoryMatch = activeCategory === 'all' || category === activeCategory;
      
      // Show/hide card based on filters
      const shouldShow = searchMatch && popularMatch && categoryMatch;
      card.style.display = shouldShow ? '' : 'none';
      
      if (shouldShow) visibleCount++;
    });
    
    // Show/hide empty state
    if (visibleCount === 0) {
      emptyState.style.display = 'block';
      packageGrid.style.display = 'none';
    } else {
      emptyState.style.display = 'none';
      packageGrid.style.display = 'grid';
    }
  }
  
  // Initialize
  filterPackages();
});
</script>
<script>
    // ====== Selection + Assign (only when customer_id present) ======
const assignBar = document.getElementById('assignBar');
if (assignBar) {
  const selected = new Set();
  const assignBtn   = document.getElementById('assignSelectedBtn');
  const startEl     = document.getElementById('assignStart');
  const endEl       = document.getElementById('assignEnd');
  const statusEl    = document.getElementById('assignStatus');
  const customerId  = {{ $assignToCustomerId ?? 'null' }};

  // collect all checkboxes
  Array.from(document.querySelectorAll('.pkg-select')).forEach(chk => {
    chk.addEventListener('change', (e) => {
      const id = parseInt(e.target.value,10);
      if (e.target.checked) selected.add(id); else selected.delete(id);
    });
  });

  assignBtn.addEventListener('click', () => {
    if (!selected.size) {
      alert('Select at least one package');
      return;
    }
    const payload = {
      package_ids: Array.from(selected),
      start_date: startEl.value || null,
      end_date: endEl.value || null,
      status: statusEl.value || 'active',
    };

    fetch(`{{ route('admin.customers.packages.assign', 0) }}`.replace('/0/','/'+customerId+'/'), {
      method:'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
      body: JSON.stringify(payload)
    }).then(r => r.json())
      .then(() => {
        window.location.href = `{{ route('customer.details', 0) }}`.replace('/0','/'+customerId);
      })
      .catch(() => alert('Failed to assign.'));
  });
}

</script>
@endsection