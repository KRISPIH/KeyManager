<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncryptionKey extends Model
{
    use HasFactory;

    protected $table = 'encryption_keys';

    protected $fillable =
    [
        'file_name',
        'key',
        'telegram_code',   
    ];
}
