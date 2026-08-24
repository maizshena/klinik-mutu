<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    protected $table = 'content_pages';
    protected $primaryKey = 'slug';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = ['slug'];
}