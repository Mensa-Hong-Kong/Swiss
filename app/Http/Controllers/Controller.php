<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;
    public static function __callStatic( $method, $parameters ) {
        return (new static)->$method(...$parameters);
    }
}
