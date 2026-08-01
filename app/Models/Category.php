<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'created_by'];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}