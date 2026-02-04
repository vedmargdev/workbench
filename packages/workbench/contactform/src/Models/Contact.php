<?php

namespace Workbench\ContactForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'fname',
        'lname',
        'company',
        'email',
        'phone',
        'message',
    ];
}
