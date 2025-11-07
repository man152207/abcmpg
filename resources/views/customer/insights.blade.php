@extends('admin.layout.layout')
@php use Illuminate\Support\Str; @endphp
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Reuse existing styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    body {
        background: #f5f6f5;
        min-height: 100vh;
    }

    .insight-container {
        margin: 0px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .insight-container h3 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1877f2;
        margin-bottom: 20px;
        text-align: center;
    }

    .insight-form {
        background: #f0f2f5;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .insight-form label {
        font-size: 1rem;
        font-weight: 600;
        color: #1c2526;
        margin-bottom: 8px;
        display: block;
    }

    .insight-form input[type="text"] {
        border: 1px solid #ccd0d5;
        border-radius: 6px;
        padding: 10px;
        font-size: 0.95rem;
        width: 100%;
    }

    .insight-form input[type="text"]:focus {
        border-color: #1877f2;
        outline: none;
        box-shadow: 0 0 5px rgba(24, 119, 242, 0.3);
    }

    .insight-form button {
        background: #1877f2;
        border: none;
        padding: 10px 20px;
        font-size: 0.95rem;
        color: #fff;
        border-radius: 6px;
        cursor: pointer;
    }

    .insight-form button:hover {
        background: #166fe5;
    }

    .insight-form button i {
        margin-right: 6px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    th {
        background: #1877f2;
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 12px;
        text-align: center;
    }

    td {
        padding: 12px;
        font-size: 0.9rem;
        color: #1c2526;
        border-bottom: 1px solid #ccd0d5;
        text-align: center;
    }

    tr:hover td {
        background: #f0f2f5;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff;
    }

    .badge-success { background: #42b72a; }
    .badge-primary { background: #1877f2; }
    .badge-warning { background: #f7b928; }
    .badge-danger { background: #e41e3f; }
    .badge-info { background: #17a2b8; }
    .badge-secondary { background: #606770; }

    .left-align {
        text-align: left !important;
    }

    .btn-insight {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 4px;
        color: #fff;
        border: none;
    }

    .btn-warning {
        background: #f7b928;
    }

    .btn-warning:hover {
        background: #e0a824;
    }

    .btn-danger {
        background: #e41e3f;
    }

    .btn-danger:hover {
        background: #c81b36;
    }

    .editable-td input {
        border: 1px solid #ccd0d5;
        border-radius: 4px;
        padding: 6px;
        font-size: 0.9rem;
        width: 100%;
        max-width: 200px;
    }

    .editable-td input:focus {
        border-color: #1877f2;
        outline: none;
    }

    .alert-warning {
        background: #fff8dd;
        border-radius: 6px;
        padding: 15px;
        font-size: 0.95rem;
        color: #664d03;
        text-align: center;
        margin-top: 20px;
    }

    .fetch-notification {
        display: none;
        background: #e7f3fe;
        color: #1877f2;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 0.9rem;
    }

    .fetch-notification.error {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .insight-container {
            margin: 10px;
            padding: 15px;
        }

        .insight-container h3 {
            font-size: 1.5rem;
        }

        th, td {
            font-size: 0.8rem;
            padding: 8px;
        }

        .btn-insight {
            padding: 5px 10px;
            font-size: 0.75rem;
        }
    }

    /* New styles for tabs */
    .nav-tabs {
        border-bottom: 2px solid #ccd0d5;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        font-size: 1rem;
        font-weight: 600;
        color: #606770;
        padding: 10px 20px;
        border: none;
        border-bottom: 2px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: #1877f2;
        border-bottom: 2px solid #1877f2;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        color: #1877f2;
    }

    .tab-content {
        background: #fff;
        border-radius: 8px;
    }
</style>

<div class="container-fluid insight-container">
    <h3 class="mb-4">
        @php
        use App\Models\Customer;
        $customer = Customer::find($customerId);
        @endphp
        {{ $customer->display_name ?? $customer->name }}'s Meta Ad Insights
    </h3>

    <!-- Fetch Notification -->
    <div class="fetch-notification" id="fetchNotification"></div>

    <!-- Manual Campaign ID Input Form -->
<form action="{{ route('insights.fetchFromApi', $customerId ?? 1) }}" method="POST" class="insight-form" id="insightForm">
    @csrf
    <input type="hidden" name="customer_id" value="{{ $customerId }}">
    <label for="campaign_ids">Campaign IDs (comma separated):</label>
    <input type="text" name="campaign_ids" class="form-control" placeholder="12022222...,12023333...">
    <label for="adset_ids">Ad Set IDs (comma separated, optional):</label>
    <input type="text" name="adset_ids" class="form-control" placeholder="12345...,67890...">
    <label for="ad_ids">Ad IDs (comma separated, optional):</label>
    <input type="text" name="ad_ids" class="form-control" placeholder="11111...,22222...">
    <button type="submit" class="btn btn-primary mt-3">
        <i class="fas fa-cloud-download-alt"></i> Fetch Insights
    </button>
</form>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="insightsTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="campaigns-tab" data-toggle="tab" href="#campaigns" role="tab">Campaigns</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="adsets-tab" data-toggle="tab" href="#adsets" role="tab">Ad Sets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ads-tab" data-toggle="tab" href="#ads" role="tab">Ads</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="insightsTabContent">
        <!-- Campaigns Tab -->
        <div class="tab-pane fade show active" id="campaigns" role="tabpanel">
            @if($campaigns && count($campaigns) > 0)
            <table class="table table-bordered table-hover" id="campaignsTable">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Campaign Name</th>
                        <th>Delivery</th>
                        <th>Budget</th>
                        <th>Results</th>
                        <th>Reach</th>
                        <th>Impressions</th>
                        <th>Cost/Result</th>
                        <th>Spent</th>
                        <th>Ends</th>
                        <th>Schedule</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="left-align editable-td" data-id="{{ $row->id }}" data-type="campaign" ondblclick="makeEditable(this)">
                            {{ $row->campaign_name }}
                        </td>
                        <td>
                            <span class="badge 
                                @if(strtolower($row->delivery) === 'active') badge-success
                                @elseif(strtolower($row->delivery) === 'completed') badge-primary
                                @elseif(strtolower($row->delivery) === 'paused') badge-warning
                                @elseif(strtolower($row->delivery) === 'in_review') badge-info
                                @elseif(strtolower($row->delivery) === 'rejected') badge-danger
                                @else badge-secondary @endif">
                                {{ ucfirst(strtolower($row->delivery)) }}
                            </span>
                        </td>
                        <td>{{ $row->budget }}</td>
                        <td>{{ $row->results }}</td>
                        <td>{{ $row->reach }}</td>
                        <td>{{ $row->impressions }}</td>
                        <td>${{ $row->cost_per_result ?? '0.00' }}</td>
                        <td>${{ $row->spend }}</td>
                        <td>{{ $row->ends }}</td>
                        <td>{{ $row->schedule }}</td>
                        <td>{{ $row->duration }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <form action="{{ route('insights.refetch', ['id' => $row->id, 'type' => 'campaign']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning btn-insight">Fetch</button>
                                </form>
                                <form action="{{ route('insights.delete', ['id' => $row->id, 'type' => 'campaign']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-insight">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="alert alert-warning mt-4">No campaign insights found for now.</div>
            @endif
        </div>

        <!-- Ad Sets Tab -->
        <div class="tab-pane fade" id="adsets" role="tabpanel">
            @if($adsets && count($adsets) > 0)
            <table class="table table-bordered table-hover" id="adsetsTable">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Ad Set Name</th>
                        <th>Delivery</th>
                        <th>Budget</th>
                        <th>Results</th>
                        <th>Reach</th>
                        <th>Impressions</th>
                        <th>Cost/Result</th>
                        <th>Spent</th>
                        <th>Ends</th>
                        <th>Schedule</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adsets as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="left-align editable-td" data-id="{{ $row->id }}" data-type="adset" ondblclick="makeEditable(this)">
                            {{ $row->adset_name }}
                        </td>
                        <td>
                            <span class="badge 
                                @if(strtolower($row->delivery) === 'active') badge-success
                                @elseif(strtolower($row->delivery) === 'completed') badge-primary
                                @elseif(strtolower($row->delivery) === 'paused') badge-warning
                                @elseif(strtolower($row->delivery) === 'in_review') badge-info
                                @elseif(strtolower($row->delivery) === 'rejected') badge-danger
                                @else badge-secondary @endif">
                                {{ ucfirst(strtolower($row->delivery)) }}
                            </span>
                        </td>
                        <td>{{ $row->budget }}</td>
                        <td>{{ $row->results }}</td>
                        <td>{{ $row->reach }}</td>
                        <td>{{ $row->impressions }}</td>
                        <td>${{ $row->cost_per_result ?? '0.00' }}</td>
                        <td>${{ $row->spend }}</td>
                        <td>{{ $row->ends }}</td>
                        <td>{{ $row->schedule }}</td>
                        <td>{{ $row->duration }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <form action="{{ route('insights.refetch', ['id' => $row->id, 'type' => 'adset']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning btn-insight">Fetch</button>
                                </form>
                                <form action="{{ route('insights.delete', ['id' => $row->id, 'type' => 'adset']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ad set?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-insight">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="alert alert-warning mt-4">No ad set insights found.</div>
            @endif
        </div>

        <!-- Ads Tab -->
        <div class="tab-pane fade" id="ads" role="tabpanel">
            @if($ads && count($ads) > 0)
            <table class="table table-bordered table-hover" id="adsTable">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Ad Name</th>
                        <th>Delivery</th>
                        <th>Ad Set Name</th>
                        <th>Results</th>
                        <th>Reach</th>
                        <th>Impressions</th>
                        <th>Cost/Result</th>
                        <th>Spent</th>
                        <th>Ends</th>
                        <th>Quality Rank</th>
                        <th>Engagement Rank</th>
                        <th>Conversion Rank</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="left-align editable-td" data-id="{{ $row->id }}" data-type="ad" ondblclick="makeEditable(this)">
                            {{ $row->ad_name }}
                        </td>
                        <td>
                            <span class="badge 
                                @if(strtolower($row->delivery) === 'active') badge-success
                                @elseif(strtolower($row->delivery) === 'completed') badge-primary
                                @elseif(strtolower($row->delivery) === 'paused') badge-warning
                                @elseif(strtolower($row->delivery) === 'in_review') badge-info
                                @elseif(strtolower($row->delivery) === 'rejected') badge-danger
                                @else badge-secondary @endif">
                                {{ ucfirst(strtolower($row->delivery)) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $adset = App\Models\AdSetInsight::where('adset_id', $row->adset_id)->first();
                            @endphp
                            {{ $adset->adset_name ?? 'N/A' }}
                        </td>
                        <td>{{ $row->results }}</td>
                        <td>{{ $row->reach }}</td>
                        <td>{{ $row->impressions }}</td>
                        <td>${{ $row->cost_per_result ?? '0.00' }}</td>
                        <td>${{ $row->spend }}</td>
                        <td>{{ $row->ends }}</td>
                        <td>{{ $row->quality_rank }}</td>
                        <td>{{ $row->engagement_rank }}</td>
                        <td>{{ $row->conversion_rank }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <form action="{{ route('insights.refetch', ['id' => $row->id, 'type' => 'ad']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning btn-insight">Fetch</button>
                                </form>
                                <form action="{{ route('insights.delete', ['id' => $row->id, 'type' => 'ad']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ad?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-insight">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="alert alert-warning mt-4">No ad insights found.</div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function makeEditable(td) {
    const oldValue = td.innerText.trim();
    const id = td.dataset.id;
    const type = td.dataset.type;

    const input = document.createElement('input');
    input.type = 'text';
    input.value = oldValue;
    input.className = 'form-control form-control-sm';
    input.style = 'max-width: 250px;';

    td.innerHTML = '';
    td.appendChild(input);
    input.focus();

    input.addEventListener('blur', function () {
        const newValue = this.value.trim();
        const updateRoute = type === 'campaign' ? '/admin/insights/update' :
                           type === 'adset' ? '/admin/insights/update/adset' : '/admin/insights/update/ad';

        if (newValue && newValue !== oldValue) {
            const csrfToken = '{{ csrf_token() }}';
            fetch(`${updateRoute}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ [`${type}_name`]: newValue })
            })
            .then(response => response.ok ? newValue : oldValue)
            .then(result => {
                td.innerHTML = result;
                showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} name updated successfully!`);
            })
            .catch(() => {
                td.innerHTML = oldValue;
                showNotification('Failed to update name.', true);
            });
        } else {
            td.innerHTML = oldValue;
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            input.blur();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('insightForm');
    const campaignIdsInput = form.querySelector('input[name="campaign_ids"]');
    const customerId = form.querySelector('input[name="customer_id"]').value;
    const notification = document.getElementById('fetchNotification');
    let isFetching = false;

    function showNotification(message, isError = false) {
        notification.textContent = message;
        notification.classList.toggle('error', isError);
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    async function autoFetchInsights() {
        if (isFetching || !campaignIdsInput.value.trim()) return;

        isFetching = true;
        showNotification('Fetching insights in the background...');

        try {
            const response = await fetch('{{ route('insights.fetchFromApi', $customerId ?? 1) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                customer_id: customerId,
                campaign_ids: form.querySelector('input[name="campaign_ids"]').value.trim(),
                adset_ids: form.querySelector('input[name="adset_ids"]').value.trim(),
                ad_ids: form.querySelector('input[name="ad_ids"]').value.trim()
            })
            });

            if (!response.ok) throw new Error('Fetch failed');

            showNotification('Insights updated successfully!');
            location.reload(); // Reload to update all tabs
        } catch (error) {
            showNotification('Failed to fetch insights. Please try again.', true);
        } finally {
            isFetching = false;
        }
    }

    setInterval(autoFetchInsights, 300000);

    if (campaignIdsInput.value.trim()) {
        autoFetchInsights();
    }
});
</script>
@endsection