<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;

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
        'pdf_path',
        'order',
    ];

    /* ================= RELATION ================= */

    /**
     * Lesson thuộc về Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * User học Lesson (bảng lesson_user)
     * Lưu tiến độ theo từng bài
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot([
                'progress',     // % tiến độ
                'completed',    // true / false
            ])
            ->withTimestamps();
    }

    /* ================= ACCESSOR ================= */

    /**
     * Lấy tiến độ (%) của lesson theo user hiện tại
     * Dùng: $lesson->progress
     */
    public function getProgressAttribute(): int
    {
        $user = auth()->user();

        if (!$user) {
            return 0;
        }

        // đảm bảo students đã load
        if (!$this->relationLoaded('students')) {
            $this->load('students');
        }

        return $this->students
            ->where('id', $user->id)
            ->first()?->pivot->progress ?? 0;
    }

    /**
     * Kiểm tra user hiện tại đã hoàn thành lesson chưa
     * Dùng: $lesson->is_completed
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->progress === 100;
    }

    /**
     * URL đầy đủ của file PDF
     * Dùng: $lesson->pdf_url
     */
    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path
            ? asset('storage/' . $this->pdf_path)
            : null;
    }
}
