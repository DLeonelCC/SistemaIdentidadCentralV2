<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

    protected $fillable = [
        'siglas',
        'nombre',
    ];

    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;
}
