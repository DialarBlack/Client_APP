<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public function loggable()
    {
        return $this->morphTo();
    }

    protected $table = 'client_apps';

    protected $fillable = [
        'message',
        'type',
        'loggable_id',
        'loggable_type',
        'created_at',
        'updated_at'
    ];
}
