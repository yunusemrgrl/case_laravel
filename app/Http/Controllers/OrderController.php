<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = json_decode($request->getContent(), true);
        return ['order' => $order];
    }
}
