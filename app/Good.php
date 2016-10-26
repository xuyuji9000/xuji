<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable=[
        'good_picture',
        'qr_code',
        'price',
        'description'
    ];


}
