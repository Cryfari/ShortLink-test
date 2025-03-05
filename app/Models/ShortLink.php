<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $table = "short_links";
    protected $prymaryKey = "id";
    protected $keyType = "string";

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user',
        'url',
        'short',
    ];

    public $incrementing = false;

    public function user(){
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
