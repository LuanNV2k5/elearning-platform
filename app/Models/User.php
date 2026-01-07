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

    /* ================= ROLE CHECK (ĐÚNG) ================= */

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role?->name === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role?->name === 'student';
    }

    /* ================= RELATION ================= */

    // Giáo viên tạo khóa học
    public function teachingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Học viên đăng ký khóa học (DÙNG BẢNG course_user)
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user');
    }

    // Nếu bạn có bảng enrollments riêng (TUỲ CHỌN)
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Các bài học do user tạo (nếu có)
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class)
            ->withPivot('completed', 'completed_at')
            ->withTimestamps();
    }

}
