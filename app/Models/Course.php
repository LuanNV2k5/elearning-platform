<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'price',
        'status',     // ✅ dùng status
        'thumbnail',  // nếu DB có thì giữ, không có thì xóa dòng này
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function firstLesson()
    {
        return $this->hasOne(Lesson::class)->orderBy('order');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['progress'])
            ->withTimestamps();
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    // ✅ lấy thumbnail từ youtube của bài học đầu tiên
    public function getThumbnailUrlAttribute(): ?string
    {
        $lesson = $this->relationLoaded('firstLesson')
            ? $this->firstLesson
            : $this->firstLesson()->first();

        if ($lesson && !empty($lesson->youtube_id)) {
            return "https://img.youtube.com/vi/{$lesson->youtube_id}/hqdefault.jpg";
        }

        return null;
    }
}
