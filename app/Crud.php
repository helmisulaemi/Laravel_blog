<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $table = 'crud';
    protected $primarykey = 'id';
    protected $fillable = ['judul','isi'];
    public $timestamps = false;
}
