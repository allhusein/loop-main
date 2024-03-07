<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Confidence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'confidence_tags'; // specify the correct table name

    protected $fillable = [
        'user_id',
        'question_id',
        'confidence',

    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    // public function categori()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}
