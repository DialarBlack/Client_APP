<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    public function clientApps()
    {
        return $this->hasMany(ClientApp::class);
    }

    protected $table = 'access_tokens';

    protected $fillable = [
        'client_id',
        'user_id',
        'expires_at'
    ];
}
