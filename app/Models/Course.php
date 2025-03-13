<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'content',
        'video_url',
        'duration',
        'difficulty_level',
        'status',
        'category_id',
        'mentor_id'
    ];

    public function category()
   {
    return $this->belongsTo(Category::class);
   } 

    public function mentor(){
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}