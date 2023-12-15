<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'excerpt', 'image_path', 'is_published', 'min_to_read', 'body'
    ];
 
    public function user(){
        return $this->belongTo(User::class);
    }

    public function category(){
        return $this->belongTo(Category::class);
    }

    use HasFactory;
}
