<?php

namespace App\Models;
use App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Course extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'price',
        'is_published',
    ];
    

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
    public function quiz()
    {
        return $this->hasOne(\App\Models\Quiz::class);
    }

}