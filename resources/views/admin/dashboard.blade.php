<?php

use App\Models\UserPrivilege;

$is_super_admin = UserPrivilege::select('full_or_partial','option')->where('user_id', auth('admin')->user()->id)->first();
if (!$is_super_admin) {
    // No privilege record → no UI menu access until assigned by super admin
    $userPrivileges = [];
} elseif (!$is_super_admin['full_or_partial']) {
    $userPrivileges = $is_super_admin['option']
        ? array_map('intval', explode(',', $is_super_admin['option']))
        : [];
} else {
    $userPrivileges = [1, 2, 3, 4, 5, 6, 7];
}
?>

@extends('admin.layout.layout')
@section('title', 'Dashboard | MPG Solution')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    background-color: #f8f9fa;
    color: #646564;
    font-family: 'Roboto', sans-serif;
}

.page-header h2 {
    color: #ffffff;
    font-size: 1.2em;
    font-weight: bold;
}
.summary-crd {
    background: linear-gradient(135deg, #fff, #e0e7ff);
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    padding: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 20px;
}

.summary-crd:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.summary-crd h6 {
    margin: 0;
    color: #0d3569;
    font-size: 1.1em;
}

.summary-crd .amount {
    font-size: 1.5em;
    font-weight: bold;
}

.summary-crd .amount .npr {
    font-size: 20px;
    font-weight: 600;
}

.summary-crd i {
    font-size: 2em;
    color: #ffffff;
    background: #646564;
    padding: 10px;
    border-radius: 50%;
    margin-left: 27px;
}
.summary-card {
    background: linear-gradient(135deg, #fff, #e0e7ff);
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    padding: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 20px;
}

.summary-card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.summary-card h6 {
    margin: 0;
    color: #0d3569;
    font-size: 1.1em;
}

.summary-card .amount {
    font-size: 1.5em;
    font-weight: bold;
}

.summary-card .amount .usd {
    color: #646564;
    font-size: 25px;
    font-weight: 600;
}

.summary-card .amount .npr {
    color: #007bff;
    font-size: 20px;
    font-weight: 600;
}

.summary-card i {
    font-size: 2em;
    color: #ffffff;
    background: #646564;
    padding: 10px;
    border-radius: 50%;
}

.chart-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 20px;
}

.chart-container h6 {
    color: #0d3569;
    margin-bottom: 20px;
    font-size: 1.3em;
    font-weight: bold;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 10px 25px;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.2s, box-shadow 0.2s;
}

.btn-primary:hover {
    background-color: #0056b3;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.icon {
    color: #007bff;
    margin-right: 10px;
}

.page-title {
    color: #0d3569;
    margin-bottom: 20px;
    font-size: 1.5em;
    font-weight: bold;
}

.card-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.card-row {
    display: flex;
    gap: 20px;
}

.summary-card {
    flex: 1;
}

.chart-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.table-responsive {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 20px;
}

.table thead th {
    background-color: #007bff;
    color: #fff;
    border: none;
}

.table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.table tbody tr:hover {
    background-color: #e0e7ff;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .summary-card, .chart-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .summary-card i, .chart-container canvas {
        margin-top: 20px;
    }

    .card-row {
        flex-direction: column;
    }

    .chart-grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 992px) {
    .chart-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1200px) {
    .chart-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    20%, 60% {
        transform: translateX(-10px);
    }
    40%, 80% {
        transform: translateX(10px);
    }
}

.fa-rotate {
    animation: rotate 2s linear infinite;
}

.fa-shake {
    animation: shake 1s ease-in-out infinite;
}
    
</style>
@php
    // तपाईंको logic अनुसार admin चिन्ने flag
    // तपाईंले पहिले प्रयोग गर्दै आउनुभएको $is_super_admin को आधारमा:
    $isAdmin = (bool) ($is_super_admin['full_or_partial'] ?? false);

    // (role प्रयोग गर्नुहुन्छ भने:)
    // $admin = auth('admin')->user();
    // $isAdmin = $admin && in_array($admin->role ?? null, ['admin','super_admin']);
@endphp

   <section class="content">
       @if(!$isAdmin)
<style>
  /* blank overlay: पेज पुरै ढाक्ने */
  #blankLock {
    position: fixed; inset: 0;
    /* Dashboard को background र tone मिल्दोजुल्दो */
    background: #3d536e;  /* चाहनु भयो भने #ffffff पनि राख्न सक्नुहुन्छ */
    z-index: 999999;
  }
  /* Unlock हुँदा body मा class थप्छौं र overlay हट्छ */
  body.unlocked #blankLock { display: none; }
</style>

<div id="blankLock" aria-hidden="true"></div>

<script>
(function(){
  const STORAGE_KEY = 'mpg_soft_unlock_until';
  const DURATION_MS = 2 * 60 * 1000; // 2 minutes

  // restore: पटकै खोल्दा समय बाकी छ भने सिधै unlock
  try {
    const until = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);
    if (until && Date.now() < until) {
      document.body.classList.add('unlocked');
      scheduleRelock(until - Date.now());
    }
  } catch(e){}

  // Ctrl + Shift + Q → unlock
  document.addEventListener('keydown', function(e){
    if (e.ctrlKey && e.shiftKey && (e.key === 'Q' || e.key === 'q')) {
      unlockNow();
    }
  });

  function unlockNow(){
    const until = Date.now() + DURATION_MS;
    try { localStorage.setItem(STORAGE_KEY, String(until)); } catch(e){}
    document.body.classList.add('unlocked');
    scheduleRelock(DURATION_MS);
  }

  function scheduleRelock(ms){
    setTimeout(function(){
      try { localStorage.removeItem(STORAGE_KEY); } catch(e){}
      document.body.classList.remove('unlocked');
      // माथिबाट blank देखियोस्
      try { document.scrollingElement.scrollTop = 0; } catch(e){}
    }, Math.max(500, ms));
  }
})();
</script>
@endif

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between">
            <h2>MPG Solution Dashboard</h2>
            
            @php
// Ensure all values are numeric
$openingBalance = floatval($totalOpeningBalance ?? 0);
$totalIncome = floatval(($monthlyAdIncomeSummaries->totalNRP ?? 0) + ($totalOtherIncome ?? 0));
$totalExpenses = floatval(($monthlyClientSummaries->totalNRP ?? 0) + ($monthlyExp->totalAmt ?? 0) + ($currentMonthExpenses->total_amount ?? 0));
$formattedTotalToBeReceived = floatval(str_replace(',', '', $formattedTotalToBeReceived ?? 0)); // Remove commas if present

// Calculate account balance
$accountBalance = $openingBalance + $totalIncome - $totalExpenses - $formattedTotalToBeReceived;
@endphp

            <!-- Account Balance -->
<div class="account-balance d-flex align-items-center">
    <span class="amount revealed-number" style="background: linear-gradient(to right, #4caf50, #81c784); color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 1.2em; font-weight: bold;">
        Account Balance: Rs {{ number_format($accountBalance, 2) }}
    </span>
</div>


            <!-- Dropdowns -->
            <div class="select-container d-flex align-items-center">
                <select id="month-select" class="form-select mx-2">
                    <!-- Month options will be dynamically populated -->
                </select>
                <select id="year-select" class="form-select mx-2">
                    <!-- Year options will be dynamically populated -->
                </select>
                <i class="fa fa-search mx-2" id="search-icon"></i>
                <i class="fa fa-eye mx-2 eye-icon" id="eye-icon"></i>
            </div>
        </div>
    </div>
</section>


            <div class="card-grid">
                <!-- First Row -->
                <div class="card-row">
                    <div class="summary-card">
                        <div>
                            <h6>Account Payables (USD)</h6>
                            <span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;"><span class="usd">$ {{ number_format($monthlyAdIncomeSummaries->totalUSD ?? 0, 2) }}</span> 
                        </div>
                        <i class="fa fa-chart-line"></i>
                    </div>
                    <div class="summary-card">
                        
                        <div>
                            <h6>Total Purchases (USD)</h6>
                            
                            <span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;"><span class="usd">$ {{ number_format($monthlyClientSummaries->totalUSD ?? 0, 2) }}</span> 
                        </div>
                        <i class="fa fa-money-bill-wave"></i>
                    </div>
                    @php
    $pendingUSD = ($monthlyAdIncomeSummaries->totalUSD ?? 0) - ($monthlyClientSummaries->totalUSD ?? 0);
    $usdLoadText = $pendingUSD < 0 ? 'Excessive USD Loads' : 'Pending USD Loads';
@endphp

<div class="summary-card">
    <div>
        <h6>{{ $usdLoadText }}</h6>
        <span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">
            <span class="usd">$ {{ number_format($pendingUSD, 2) }}</span>
            <div></div>
        </span>
    </div>
    <i class="fa fa-retweet"></i>
</div>

                    <div class="summary-card">
                        <div>
                            <h6>Current USD Balance</h6>
                            <span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;"><span class="usd">$ {{ number_format($Cardsummary->totalUSD ?? 0, 2) }}</span></span>
                        </div>
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="summary-card">
    <div>
        <h6>Total Bonus (Active Season)</h6>

        <span class="amount hidden-number">xxxx.xx</span>
        <span class="amount revealed-number usd" style="display: none;">
            @if(!empty($activeBonusSeason))
                <span class="usd">
                    $ {{ number_format($totalBonusCredit ?? 0, 2) }}
                </span>
            @else
                <span class="usd">
                    $ 0.00
                </span>
            @endif
        </span>
    </div>
    <i class="fa fa-gift"></i>
</div>

                </div>
                <!-- Second Row -->
                <div class="card-row">
                    <div class="summary-card">
                        
                        <div>
    <h6>Gross Ads Sales</h6>
    <span class="amount hidden-number">xxxx.xx</span>
    <span class="amount revealed-number usd" style="display: none;">
        @php
            // Calculate the total value
            $totalGrossValue = ($monthlyAdIncomeSummaries->totalNRP ?? 0) + ($totalOtherIncome ?? 0);
        @endphp
        <div class="tfund">
            <strong></strong> Rs {{ number_format($totalGrossValue, 2) }}
        </div>
    </span>
</div>
                        <i class="fa fa-chart-line"></i>
                    </div>
                    
                    <div class="summary-card">
    <div>
        <h6>Total Expenses</h6>
        <span class="amount hidden-number">xxxx.xx</span> 
<span class="amount revealed-number usd" style="display: none;">
    <span class="npr">
        Rs {{ number_format(($monthlyClientSummaries->totalNRP ?? 0) + ($monthlyExp->totalAmt ?? 0) + ($currentMonthExpenses->total_amount ?? 0), 2) }}
    </span>
</span>
    </div>
    <i class="fa fa-wallet"></i>
</div>
                    <div class="summary-card">
                        <div>
                           <h6>Account Receivables</h6>
<span class="amount hidden-number">xxxx.xx</span>
<span class="amount revealed-number usd" style="display: none;">
    <span class="npr">Rs {{ number_format(floatval(str_replace(',', '', $formattedTotalToBeReceived ?? 0)), 2) }}</span>
</span>

                           
                            </div>
                        <i class="fa fa-flag"></i>
                    </div>
                    <div class="summary-card">
    <div>
        <h6>Income Summary</h6>
        <span class="amount revealed-number usd" style="display: none;">
    @php
        // Calculate the total value by summing $monthlyAdIncomeSummaries->totalNRP, $totalOtherIncome and $totalOpeningBalance
        $IncomeSummary = ($monthlyAdIncomeSummaries->totalNRP ?? 0) + ($totalOpeningBalance ?? 0);
    @endphp
    <div class="tfund">
        <strong></strong> Rs {{ number_format($IncomeSummary, 2) }}
    </div>
</span>

    </div>
    <i class="fa fa-balance-scale"></i>
</div>
                   @php
    $currentMonthName = \Carbon\Carbon::now()->format('F Y');

    // Remove Total Other Income from the current month's profit calculation
    $currentMonthProfitNRP = ($monthlyAdIncomeSummaries->totalNRP ?? 0) 
                             - (($monthlyClientSummaries->totalNRP ?? 0) + ($monthlyExp->totalAmt ?? 0));

    $isLoss     = $currentMonthProfitNRP < 0;
    $fontColor  = $isLoss ? '#f43f5e' : '#10b981';
    $iconClass  = $isLoss ? 'fa-rotate' : 'fa-shake';
    $profitText = $isLoss ? 'Loss' : 'Profit';
@endphp


<div class="summary-crd" data-loss="{{ $isLoss ? 'true' : 'false' }}">
    <div>
        <h6>{{ $currentMonthName }} {{ $profitText }}</h6>
        <span class="amount revealed-number usd" style="color: {{ $fontColor }};">
            Rs {{ number_format($currentMonthProfitNRP, 2) }}
        </span>
    </div>
    <i class="fa fa-piggy-bank {{ $iconClass }}"></i>
</div>



                </div>
            </div>
 
            <?php
$previousMonthName = \Carbon\Carbon::now()->subMonth()->format('F Y');
?>

<div class="col-13">
    <div class="table-responsive">
        <h6><?php echo $previousMonthName; ?> Summary</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount in USD</th>
                    <th>Amount in NRP</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gross Ads Sales of <?php echo $previousMonthName; ?></td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">$ {{ number_format($previousMonthlyAdIncomeSummaries->totalUSD ?? 0, 2) }}</span></td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">Rs {{ number_format($previousMonthlyAdIncomeSummaries->totalNRP ?? 0, 2) }}</span></td>
                </tr>
                <tr>
                    <td>Expenditures of <?php echo $previousMonthName; ?></td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">$ {{ number_format($previousMonthlyClientSummaries->totalUSD ?? 0, 2) }}</span></td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">Rs {{ number_format(($previousMonthlyClientSummaries->totalNRP ?? 0) + ($previousMonthlyExp->totalAmt ?? 0), 2) }}</span></td>
                </tr>
                <tr>
                    <td>Profit of <?php echo $previousMonthName; ?></td>
                    <td>-</td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">Rs {{ number_format(($previousMonthlyAdIncomeSummaries->totalNRP ?? 0) - (($previousMonthlyClientSummaries->totalNRP ?? 0) + ($previousMonthlyExp->totalAmt ?? 0)), 2) }}</span></td>
                </tr>
                <tr>
                    <td>To be Loaded USD</td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">$ {{ number_format(($previousMonthlyAdIncomeSummaries->totalUSD ?? 0) - ($previousMonthlyClientSummaries->totalUSD ?? 0), 2) }}</span></td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">Rs {{ number_format(
                        ($previousMonthlyClientSummaries->totalUSD ?? 0) > 0 ? 
                        ($previousMonthlyClientSummaries->totalNRP ?? 0) / ($previousMonthlyClientSummaries->totalUSD ?? 0) * (($previousMonthlyAdIncomeSummaries->totalUSD ?? 0) - ($previousMonthlyClientSummaries->totalUSD ?? 0)) 
                        : 0, 2) }}</span></td>
                </tr>
                <tr>
                    <td>USD Balance</td>
                    <td><span class="amount hidden-number">xxxx.xx</span> <span class="amount revealed-number usd" style="display: none;">$ {{ number_format($previousCardsummary->totalUSD ?? 0, 2) }}</span></td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const eyeIcon = document.getElementById('eye-icon');
    const monthSelect = document.getElementById('month-select');
    const yearSelect = document.getElementById('year-select');
    let isRevealed = false;

    // Initially, always set the numbers to invisible
    toggleVisibility(false);

    // Load visibility state from localStorage
    const visibilityState = JSON.parse(localStorage.getItem('visibilityState'));
    if (visibilityState && Date.now() - visibilityState.timestamp < 100 * 600 * 100000) {
        isRevealed = visibilityState.isRevealed;
        toggleVisibility(isRevealed);
    }

    // Populate the month and year dropdowns
    populateMonthYearDropdowns();

    // Toggle visibility of the numbers
    eyeIcon.addEventListener('click', function () {
        isRevealed = !isRevealed;
        toggleVisibility(isRevealed);

        // Save the visibility state with a timestamp
        localStorage.setItem('visibilityState', JSON.stringify({
            isRevealed: isRevealed,
            timestamp: Date.now()
        }));
    });

    // Filter data based on selected month and year
    monthSelect.addEventListener('change', filterDashboardData);
    yearSelect.addEventListener('change', filterDashboardData);

    function toggleVisibility(isRevealed) {
        if (isRevealed) {
            document.querySelectorAll('.hidden-number').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.revealed-number').forEach(el => el.style.display = 'block');
        } else {
            document.querySelectorAll('.hidden-number').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.revealed-number').forEach(el => el.style.display = 'none');
        }
    }

    function populateMonthYearDropdowns() {
        const currentYear = new Date().getFullYear();
        const months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index + 1;
            option.text = month;
            monthSelect.appendChild(option);
        });

        for (let i = currentYear; i >= 2000; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            yearSelect.appendChild(option);
        }

        // Optionally, you could set the current month and year as the default selection
        monthSelect.value = new Date().getMonth() + 1;
        yearSelect.value = currentYear;
    }

    function filterDashboardData() {
        const selectedMonth = monthSelect.value;
        const selectedYear = yearSelect.value;

        // Make an AJAX request to filter the dashboard data
        fetch(`/dashboard/filter?month=${selectedMonth}&year=${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                console.log('Filtered data:', data); // Log the received data
                updateDashboardData(data);
            })
            .catch(error => console.error('Error fetching filtered data:', error));
    }

    function updateDashboardData(data) {
        if (data) {
            // Example: Update specific dashboard elements with the filtered data
            document.querySelector('.revealed-number.usd').innerText = `$ ${data.totalUSD}`;
            document.querySelector('.revealed-number.npr').innerText = `Rs ${data.totalNRP}`;
            
            // Update other parts of the dashboard as needed
        }
    }
});

</script>
@endsection
