<tbody id="expense-table-body">
    @foreach ($exps as $exp)
    <tr id="row-{{ $exp->id }}">
        <td class="date-col">{{ $exp->date }}</td>
        <td class="title-col">
            <span class="display">{{ $exp->title }}</span>
            <input type="text" class="form-control edit" value="{{ $exp->title }}" style="display:none;">
        </td>
        <td class="amount-col">
            <span class="display">{{ $exp->amount }}</span>
            <input type="number" class="form-control edit" value="{{ $exp->amount }}" style="display:none;">
        </td>
        <td class="note-col">
            <span class="display">{{ $exp->note }}</span>
            <input type="text" class="form-control edit" value="{{ $exp->note }}" style="display:none;">
        </td>
        <td class="action-col">
            <button class="btn btn-primary btn-sm edit-btn">Edit</button>
            <button class="btn btn-success btn-sm save-btn" style="display:none;">Save</button>
            <button class="btn btn-danger btn-sm cancel-btn" style="display:none;">Cancel</button>
            <form action="{{ url('/admin/dashboard/exp/delete/'. $exp->id) }}" method="get" style="display:inline;">
                @csrf
                @method('GET')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
<div id="pagination-links">
    {{ $exps->links('pagination::bootstrap-5') }}
</div>
