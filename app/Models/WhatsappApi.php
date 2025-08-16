<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappApi extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_apis';
    protected $fillable = ['access_token'];
}