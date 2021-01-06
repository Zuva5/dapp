<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'title', 'description', 'url', 'image',
    ];

    public function profileImage(){

        $imagePath= ($this->image) ? $this->image : 'profile/GAuUASELNunmtX1LokiVaaxgUgkVnq8R6eqHmB3f.jpeg';
        return '/storage/' . $imagePath;
    }

    public function followers()
    {
        return $this->belongsToMany(User::class);
    }

    public function user(){

        return $this->belongsTo(User::class);
    }
}
