<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'youtube_id',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('completed', 'completed_at')
            ->withTimestamps();
    }
    public function getPdfUrlAttribute()
{
    return $this->pdf_path
        ? asset('storage/' . $this->pdf_path)
        : null;
}

}

