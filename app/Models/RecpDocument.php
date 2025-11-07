<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecpDocument extends Model
{
    use SoftDeletes;
    protected $fillable = ['student_id', 'doc_type', 'doc_no', 'issued_at', 'fee', 'remarks', 'file_path', 'handled_by_admin_id'];
    protected $casts = ['issued_at' => 'date'];

    public function student() { return $this->belongsTo(RecpStudent::class); }
    public function handler() { return $this->belongsTo(Admin::class, 'handled_by_admin_id'); }
}