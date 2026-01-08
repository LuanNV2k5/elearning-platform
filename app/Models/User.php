<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Role;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ================= BASIC ================= */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ================= ROLE ================= */

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /* ================= ROLE CHECK ================= */

    public function isAdmin(): bool
    {
        return strtolower($this->role?->name ?? '') === 'admin';
    }

    public function isTeacher(): bool
    {
        return strtolower($this->role?->name ?? '') === 'teacher';
    }

    public function isStudent(): bool
    {
        return strtolower($this->role?->name ?? '') === 'student';
    }

    /* ================= COURSE RELATION ================= */

    // Giáo viên tạo khóa học
    public function teachingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Học viên đăng ký khóa học (bảng course_user)
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withTimestamps();
    }

    // Nếu có bảng enrollments riêng (tuỳ chọn)
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /* ================= LESSON / PROGRESS ================= */

    /**
     * Các bài học user đã tham gia (bảng lesson_user)
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->withPivot([
                'progress',     // % tiến độ
                'completed',    // true / false
            ])
            ->withTimestamps();
    }

    /**
     * Chỉ các bài học đã hoàn thành
     */
    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->wherePivot('completed', true)
            ->withPivot([
                'progress',
                'completed',
            ])
            ->withTimestamps();
    }

    /* ================= HELPER ================= */

    /**
     * Lấy tiến độ (%) của user cho 1 lesson cụ thể
     */
    public function lessonProgress(Lesson $lesson): int
    {
        $pivot = $this->lessons
            ->where('id', $lesson->id)
            ->first()?->pivot;

        return $pivot?->progress ?? 0;
    }

    /**
     * Kiểm tra user đã hoàn thành lesson chưa
     */
    public function hasCompletedLesson(Lesson $lesson): bool
    {
        return $this->lessonProgress($lesson) === 100;
    }
    public function quizAttempts()
{
    return $this->hasMany(\App\Models\QuizAttempt::class);
}

}
