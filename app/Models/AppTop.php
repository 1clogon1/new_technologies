<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTop extends Model
{
    use HasFactory;

    protected $table = 'app_top';

    protected $fillable = [
        'category',
        'position',
        'date',
    ];

    protected $hidden = [];

    protected $dates = [
        'date',
    ];

}
