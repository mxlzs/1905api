<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $primaryKey='id';
    public $table='user';
    protected $guarded = [];
    public $timestamps=false;
}
