<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
     * Các cột được phép ghi vào DB
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'youtube_id',
        'pdf_path',   // ✅ BẮT BUỘC – để lưu file PDF
        'order',
    ];

    /**
     * Quan hệ: Lesson thuộc về Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Quan hệ: Sinh viên học bài học
     */
    public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('completed', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Accessor: lấy URL đầy đủ của file PDF
     * Dùng: $lesson->pdf_url
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path
            ? asset('storage/' . $this->pdf_path)
            : null;
    }
}
