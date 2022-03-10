<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'postlikes';
    protected $fillable = ['id_post', 'id_user', 'created_at'];
}
