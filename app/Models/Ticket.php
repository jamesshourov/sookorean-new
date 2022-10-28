<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_email',
        'receiver_email',
        'sender_name',
        'title',
        'description',
        'type',
        'status',
        'ticket_number'
    ];
}
