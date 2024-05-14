<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Nilai extends Model
{

    protected $table = 'nilais';
    protected $fillable = [
        'user_id',
        'question_id',
        'category_id',
        'attemp_id',
        'nilai',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
}
