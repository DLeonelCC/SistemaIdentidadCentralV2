<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Passport\Http\Middleware\CheckClientCredentials as PassportCheckClientCredentials;

class CheckClientCredentials extends PassportCheckClientCredentials
{
    // Hereda funcionalidad directamente
}