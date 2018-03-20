<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $guarded = ['id'];
    protected $table = 'galleries';
    protected $fillable = ['name','description','published','thumbnail'];
    
    public function images(){
        return $this->hasMany('App\Image');
    }

    public function scopeAllRecordsOrderByUpdateAt($query){
        return $query->orderBy('updated_at', 'DESC');
    }

}
