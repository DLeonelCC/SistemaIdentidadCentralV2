<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    use HasFactory;

    protected $table = 'sistemas';

    protected $fillable = [
        'codigo',
        'color',
        'nombre',
        'descripcion',
        'url',
    ];
    
    protected $primaryKey = 'id';
    protected $guarded = 'id';
    public $timestamps = true;
}
