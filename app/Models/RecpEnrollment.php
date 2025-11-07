<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecpEnrollment extends Model
{
    use SoftDeletes;
    protected $fillable = ['student_id', 'batch_id', 'enroll_date', 'fee_agreed', 'discount', 'status'];
    protected $casts = ['enroll_date' => 'date'];

    public function student() { return $this->belongsTo(RecpStudent::class); }
    public function batch() { return $this->belongsTo(RecpBatch::class); }
    public function payments() { return $this->hasMany(RecpPayment::class, 'enrollment_id'); }
    public function getDueAmountAttribute() { return ($this->fee_agreed - $this->discount) - $this->payments->sum('amount'); }
}