<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RecpCourse extends Model
{
    protected $fillable = ['title', 'description', 'duration', 'fee_standard'];

    public function batches() { return $this->hasMany(RecpBatch::class, 'course_id'); }
}