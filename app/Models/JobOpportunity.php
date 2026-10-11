<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOpportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company_name',
        'company_logo',
        'location',
        'job_type',
        'salary_range',
        'description',
        'requirements',
        'related_course_id',
        'apply_type',
        'apply_value',
        'deadline',
        'is_featured',
        'status',
    ];

    public function relatedCourse()
    {
        return $this->belongsTo(Course::class, 'related_course_id');
    }
}
