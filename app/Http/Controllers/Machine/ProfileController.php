<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use App\Http\Resources\Machine\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        return ProfileResource::make($request->user());
    }
}
