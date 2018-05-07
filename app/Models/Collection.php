<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected  $fillable=['user_id','idiom_ids'];
    public function user(){
        return $this->belongsTo(User::class);
    }

}
