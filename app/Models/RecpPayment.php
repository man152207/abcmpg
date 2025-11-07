<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecpPayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['enrollment_id', 'amount', 'method', 'source_account', 'reference', 'paid_at', 'note', 'received_by_admin_id'];
    protected $casts = ['paid_at' => 'datetime'];

    public function enrollment() { return $this->belongsTo(RecpEnrollment::class); }
    public function receiver() { return $this->belongsTo(Admin::class, 'received_by_admin_id'); }
}