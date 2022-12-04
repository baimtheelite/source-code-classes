<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class Upload {
    public function __construct()
    {}

    public function upload(Request $request, $name)
    {
            $avatar = $request->file($name);
            $avatarName = time().'.'.$avatar->getClientOriginalExtension();
            $putFile = Storage::putFileAs('avatars', $avatar, $avatarName, 'public');
            $path = $putFile;
            return $path;
    }
}
