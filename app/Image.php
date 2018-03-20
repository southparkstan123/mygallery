<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = ['id'];
    protected $table = 'images';
    
    protected $fillable = array('gallery_id','description','image','published');

    public function gallery()
    {
        return $this->belongsTo('App\Gallery','gallery_id');
    }
}
