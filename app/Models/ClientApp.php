<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientApp extends Model
{
    use HasFactory;

    protected $table = 'client_apps';

    protected $fillable = [
        'name',
        'return_url',
        'public_key',
        'secret_key'
    ];
}
