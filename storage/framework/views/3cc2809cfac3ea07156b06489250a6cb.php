

<?php $__env->startSection('content'); ?>
<style>
:root {
  --primary: #4361ee;
  --primary-light: #eef2ff;
  --primary-dark: #3a56d4;
  --secondary: #6c757d;
  --success: #10b981;
  --info: #06b6d4;
  --warning: #f59e0b;
  --danger: #ef4444;
  --light: #f8fafc;
  --dark: #1e293b;
  --gray-100: #f1f5f9;
  --gray-200: #e2e8f0;
  --gray-300: #cbd5e1;
  --gray-400: #94a3b8;
  --gray-500: #64748b;
  --gray-600: #475569;
  --gray-700: #334155;
  --gray-800: #1e293b;
  --gray-900: #0f172a;
  --border-radius: 16px;
  --border-radius-sm: 8px;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
  --shadow-lg: 0 10px 30px rgba(0,0,0,0.1);
  --transition: all 0.3s ease;
}

/* Header and Toolbar */
.page-header {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 2rem;
  padding: 2rem;
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
}

.page-title-section {
  flex: 1;
  min-width: 300px;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.page-title i {
  color: var(--primary);
  font-size: 1.5rem;
}

.page-subtitle {
  color: var(--gray-600);
  font-size: 0.95rem;
  max-width: 600px;
}

.toolbar {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 1rem;
  margin-top: 1rem;
}

/* Stats Overview */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: var(--border-radius);
  padding: 1.5rem;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.stat-icon.primary {
  background: rgba(67, 97, 238, 0.1);
  color: var(--primary);
}

.stat-icon.success {
  background: rgba(16, 185, 129, 0.1);
  color: var(--success);
}

.stat-icon.warning {
  background: rgba(245, 158, 11, 0.1);
  color: var(--warning);
}

.stat-icon.info {
  background: rgba(6, 182, 212, 0.1);
  color: var(--info);
}

.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--gray-900);
  line-height: 1.2;
}

.stat-label {
  font-size: 0.85rem;
  color: var(--gray-600);
}

/* Search Box */
.search-box {
  position: relative;
  min-width: 280px;
}

.search-box .form-control {
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  border-radius: 50px;
  border: 1px solid var(--gray-300);
  transition: var(--transition);
  font-size: 0.9rem;
  width: 100%;
}

.search-box .form-control:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
  outline: none;
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-500);
  z-index: 5;
}

/* Filter Toggle */
.filter-toggle {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: white;
  border: 1px solid var(--gray-300);
  border-radius: 50px;
  color: var(--gray-700);
  font-weight: 500;
  transition: var(--transition);
  cursor: pointer;
  font-size: 0.9rem;
}

.filter-toggle:hover {
  background: var(--gray-100);
  border-color: var(--gray-400);
}

.filter-toggle.active {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
  border-radius: 50px;
  padding: 0.75rem 1.5rem;
  transition: var(--transition);
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
  text-decoration: none;
}

.btn-primary {
  background: var(--primary);
  color: white;
}

.btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-outline-secondary {
  background: white;
  color: var(--gray-700);
  border: 1px solid var(--gray-400);
}

.btn-outline-secondary:hover {
  background: var(--gray-100);
  border-color: var(--gray-500);
  transform: translateY(-2px);
}

/* Category Tabs */
.category-tabs-container {
  margin-bottom: 2rem;
  background: white;
  border-radius: var(--border-radius);
  padding: 1rem 1.5rem;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
}

.category-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.category-tab {
  padding: 0.7rem 1.25rem;
  background: white;
  border: 1px solid var(--gray-300);
  border-radius: 50px;
  color: var(--gray-700);
  font-weight: 500;
  font-size: 0.9rem;
  transition: var(--transition);
  cursor: pointer;
}

.category-tab:hover {
  background: var(--gray-100);
  border-color: var(--gray-400);
}

.category-tab.active {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

/* Package Grid */
.package-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.package-card {
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
  transition: var(--transition);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  height: 100%;
  position: relative;
}

.package-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
  border-color: var(--gray-300);
}

.package-popular {
  position: absolute;
  top: 12px;
  left: 12px;
  background: linear-gradient(135deg, #FFD75E, #FFB03A);
  color: #0f172a;
  font-weight: 700;
  font-size: 0.75rem;
  padding: 6px 12px;
  border-radius: 50px;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 14px rgba(255, 183, 3, 0.25);
  z-index: 2;
}

.package-header {
  padding: 1.5rem 1.5rem 1rem;
  border-bottom: 1px solid var(--gray-200);
  position: relative;
}

.package-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: 0.5rem;
  line-height: 1.4;
  padding-right: 30px;
}

.package-code {
  display: inline-block;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--gray-600);
  background: var(--gray-100);
  border: 1px solid var(--gray-300);
  border-radius: 6px;
  padding: 4px 10px;
}

.package-price {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary);
  margin: 0.75rem 0;
}

.package-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.meta-pill {
  background: var(--gray-100);
  border: 1px solid var(--gray-300);
  color: var(--gray-700);
  font-size: 0.75rem;
  padding: 4px 10px;
  border-radius: 50px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 4px;
}

.meta-pill.primary {
  background: rgba(67, 97, 238, 0.1);
  color: var(--primary);
  border-color: rgba(67, 97, 238, 0.2);
}

.meta-pill.secondary {
  background: rgba(108, 117, 125, 0.1);
  color: var(--secondary);
  border-color: rgba(108, 117, 125, 0.2);
}

.package-body {
  padding: 1.25rem 1.5rem;
  flex-grow: 1;
}

.feature-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  padding: 0.5rem 0;
  color: var(--gray-700);
  font-size: 0.9rem;
  line-height: 1.4;
}

.feature-item:not(:last-child) {
  border-bottom: 1px solid var(--gray-100);
}

.feature-icon {
  color: var(--success);
  font-size: 0.8rem;
  margin-top: 0.2rem;
  flex-shrink: 0;
}

.feature-extra {
  display: none;
}

.feature-list.collapsed .feature-extra {
  display: none;
}

.feature-list.expanded .feature-extra {
  display: block;
}

.toggle-features {
  background: none;
  border: none;
  color: var(--primary);
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.5rem 0;
  margin-top: 0.5rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.25rem;
  transition: var(--transition);
}

.toggle-features:hover {
  color: var(--primary-dark);
}

.package-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--gray-200);
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--gray-50);
}

.package-status {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
}

.status-badge {
  padding: 4px 10px;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-badge.active {
  background: rgba(16, 185, 129, 0.1);
  color: var(--success);
}

.status-badge.inactive {
  background: rgba(239, 68, 68, 0.1);
  color: var(--danger);
}

.sync-time {
  color: var(--gray-600);
  font-size: 0.8rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

/* Empty State */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 3rem 1rem;
  color: var(--gray-600);
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--gray-200);
}

.empty-state-icon {
  font-size: 3rem;
  color: var(--gray-400);
  margin-bottom: 1rem;
}

.empty-state-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--gray-700);
}

.empty-state-text {
  max-width: 500px;
  margin: 0 auto 1.5rem;
}

/* Assign Bar */
.assign-bar {
  border: 1px solid var(--gray-200);
  border-radius: var(--border-radius);
  padding: 1.25rem 1.5rem;
  margin-bottom: 1.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  justify-content: space-between;
  background: var(--primary-light);
  box-shadow: var(--shadow-sm);
}

.assign-info {
  font-weight: 600;
  color: var(--gray-800);
}

.assign-controls {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.form-control {
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--gray-300);
  border-radius: var(--border-radius-sm);
  transition: var(--transition);
  font-size: 0.9rem;
}

.form-control:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
  outline: none;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.form-group label {
  font-size: 0.8rem;
  color: var(--gray-600);
  font-weight: 500;
}

/* Package Selection */
.package-select {
  position: absolute;
  right: 16px;
  top: 16px;
  z-index: 3;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.8rem;
  background: white;
  padding: 4px 8px;
  border-radius: 6px;
  box-shadow: var(--shadow-sm);
}

/* Debug Panel */
.debug-panel {
  margin-top: 2rem;
  border: 1px solid var(--gray-300);
  border-radius: var(--border-radius);
  overflow: hidden;
  background: white;
}

.debug-header {
  padding: 0.75rem 1rem;
  background: var(--gray-100);
  border-bottom: 1px solid var(--gray-300);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-weight: 500;
}

.debug-header:hover {
  background: var(--gray-200);
}

.debug-content {
  padding: 0;
  display: none;
}

.debug-panel[open] .debug-content {
  display: block;
}

.debug-table {
  margin: 0;
  font-size: 0.85rem;
  width: 100%;
}

.debug-table th {
  background: var(--gray-100);
  font-weight: 600;
  padding: 0.75rem;
}

.debug-table td {
  padding: 0.75rem;
  border-top: 1px solid var(--gray-200);
}

/* Responsive */
@media (max-width: 768px) {
  .package-grid {
    grid-template-columns: 1fr;
  }
  
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    padding: 1.5rem;
  }
  
  .toolbar {
    width: 100%;
    justify-content: space-between;
  }
  
  .search-box {
    max-width: 100%;
    flex-grow: 1;
  }

  .assign-bar {
    flex-direction: column;
    align-items: flex-start;
  }

  .assign-controls {
    width: 100%;
    justify-content: space-between;
  }

  .category-tabs {
    justify-content: center;
  }
}

@media (min-width: 769px) and (max-width: 1024px) {
  .package-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1025px) and (max-width: 1400px) {
  .package-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1401px) {
  .package-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Animation for loading */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.package-card {
  animation: fadeIn 0.5s ease forwards;
}

/* Loading state for sync button */
.btn-loading {
  position: relative;
  pointer-events: none;
}

.btn-loading::after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  border: 2px solid transparent;
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: button-loading-spinner 1s ease infinite;
}

@keyframes button-loading-spinner {
  from { transform: rotate(0turn); }
  to { transform: rotate(1turn); }
}
</style>

<div class="container-fluid">
  <!-- Stats Overview -->
  <div class="stats-container">
    <div class="stat-card">
      <div class="stat-icon primary">
        <i class="fas fa-box"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value"><?php echo e($packages->count()); ?></div>
        <div class="stat-label">Total Packages</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon success">
        <i class="fas fa-star"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value"><?php echo e($packages->where('is_popular', true)->count()); ?></div>
        <div class="stat-label">Popular Packages</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon warning">
        <i class="fas fa-sync"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value"><?php echo e($packages->max('synced_at') ? \Carbon\Carbon::parse($packages->max('synced_at'))->diffForHumans() : 'Never'); ?></div>
        <div class="stat-label">Last Sync</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon info">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-content">
        <div class="stat-value"><?php echo e($packages->where('active', true)->count()); ?></div>
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
        <?php echo csrf_field(); ?>
        <button id="syncBtn" class="btn btn-primary">
          <i class="fas fa-sync-alt"></i>
          <span>Sync Now</span>
        </button>
      </form>
    </div>
  </div>

  <?php
    $assignToCustomerId = request('customer_id');
  ?>

  <?php if($assignToCustomerId): ?>
    <div id="assignBar" class="assign-bar">
      <div class="assign-info">Assigning to Customer ID: <strong><?php echo e($assignToCustomerId); ?></strong></div>
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
        <a href="<?php echo e(route('customer.details', $assignToCustomerId)); ?>" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left"></i>
          <span>Back to Customer</span>
        </a>
      </div>
    </div>
  <?php endif; ?>
  
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
    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
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
      ?>
      
      <div class="package-card" 
           data-name="<?php echo e(Str::lower($name)); ?>"
           data-code="<?php echo e(Str::lower($code)); ?>"
           data-popular="<?php echo e($isPopular ? '1' : '0'); ?>"
           data-category="<?php echo e($category); ?>">
        <?php if($isPopular): ?>
          <div class="package-popular">
            <i class="fas fa-star"></i>
            <span>Popular</span>
          </div>
        <?php endif; ?>
        
        <?php if($assignToCustomerId): ?>
          <label class="package-select">
            <input type="checkbox" class="pkg-select" value="<?php echo e($p->id); ?>"> Select
          </label>
        <?php endif; ?>

        <div class="package-header">
          <h3 class="package-title"><?php echo e($name); ?></h3>
          <?php if($code): ?>
            <span class="package-code"><?php echo e($code); ?></span>
          <?php endif; ?>
          
          <div class="package-price">
            <?php if(!is_numeric($p->price) || (float)$p->price <= 0): ?>
              Custom Package
            <?php else: ?>
              Rs. <?php echo e($price); ?>

            <?php endif; ?>
          </div>
          
          <div class="package-meta">
            <span class="meta-pill primary">
              <i class="fas fa-list-alt"></i>
              <span><?php echo e($featuresCount); ?> Features</span>
            </span>
            <span class="meta-pill secondary">
              <i class="fas fa-folder"></i>
              <span><?php echo e(ucfirst(str_replace('-', ' ', $category))); ?></span>
            </span>
          </div>
        </div>
        
        <div class="package-body">
          <?php if($featuresCount): ?>
            <ul class="feature-list collapsed">
              <?php $__currentLoopData = $firstFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="feature-item">
                  <i class="fas fa-check feature-icon"></i>
                  <span><?php echo e($featureLabel($f)); ?></span>
                </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
              <?php if(count($extraFeatures)): ?>
                <div class="feature-extra">
                  <?php $__currentLoopData = $extraFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="feature-item">
                      <i class="fas fa-check feature-icon"></i>
                      <span><?php echo e($featureLabel($f)); ?></span>
                    </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <button class="toggle-features">
                  <span>Show <?php echo e(count($extraFeatures)); ?> more</span>
                  <i class="fas fa-chevron-down"></i>
                </button>
              <?php endif; ?>
            </ul>
          <?php else: ?>
            <div class="text-muted text-center py-3">
              <i class="fas fa-info-circle mb-2"></i>
              <div>No features listed</div>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="package-footer">
          <div class="package-status">
            <span class="status-badge <?php echo e($p->active ? 'active' : 'inactive'); ?>">
              <?php echo e($p->active ? 'Active' : 'Inactive'); ?>

            </span>
          </div>
          <div class="sync-time">
            <i class="far fa-clock"></i>
            <span><?php echo e(\Carbon\Carbon::parse($p->synced_at)->diffForHumans()); ?></span>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
      <div class="table-responsive">
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
            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php if($p->is_popular): ?> ⭐ Popular <?php endif; ?></td>
                <td><?php echo e($p->name); ?></td>
                <td><?php echo e($p->code); ?></td>
                <td><?php echo e((is_numeric($p->price) && $p->price>0) ? 'Rs. '.number_format($p->price) : 'Custom Pack'); ?></td>
                <td><?php echo e(is_array($p->features) ? count($p->features) : 0); ?></td>
                <td><?php echo e($p->synced_at); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
  const customerId  = <?php echo e($assignToCustomerId ?? 'null'); ?>;

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

    fetch(`<?php echo e(route('admin.customers.packages.assign', 0)); ?>`.replace('/0/','/'+customerId+'/'), {
      method:'POST',
      headers: {
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept':'application/json',
        'Content-Type':'application/json'
      },
      body: JSON.stringify(payload)
    }).then(r => r.json())
      .then(() => {
        window.location.href = `<?php echo e(route('customer.details', 0)); ?>`.replace('/0','/'+customerId);
      })
      .catch(() => alert('Failed to assign.'));
  });
}

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/packages/index.blade.php ENDPATH**/ ?>