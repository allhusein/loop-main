<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function exercise()
    // {
    //     return $this->belongsTo(Exercise::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }
}
