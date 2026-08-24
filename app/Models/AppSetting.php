<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = ['setting_key'];
}