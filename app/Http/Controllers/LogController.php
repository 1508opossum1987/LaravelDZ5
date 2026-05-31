<?php

namespace App\Http\Controllers;

use Cassandra\Exception\ServerException;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        file_put_contents('test.png', base64_decode($request->image));
    }
}
