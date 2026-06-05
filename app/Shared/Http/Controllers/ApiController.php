<?php

namespace App\Shared\Http\Controllers;

use App\Shared\Responses\ApiResponse;

abstract class ApiController extends Controller
{
    use ApiResponse;
}
