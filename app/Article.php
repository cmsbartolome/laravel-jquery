<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'body'];

    public function getAll($search=null) {
        return Article::when(isset($search), function ($q) use($search){
            $q->where('title', 'LIKE', $search.'%');
        })->latest()->paginate(5);
    }
}
