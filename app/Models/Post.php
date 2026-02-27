<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
    'user_id', 
    'title', 
    'slug', 
    'content', 
    'image', 
    'is_published'
];

// protected $fillable = [
//     'user_id',
//     'blog_category_id',
//     'title',
//     'slug',
//     'excerpt',
//     'content',
//     'featured_image',
//     'status',
//     'published_at',
// ];

// Link back to the Admin/User
public function user()
{
    return $this->belongsTo(User::class);
}
}
