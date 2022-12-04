<?php

namespace App\Repositories;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;

class Token {

    public function hash($device_id) {
        $timestamp = now()->timestamp;
        $token = md5($device_id . '-' . $timestamp);
        $refreshToken = md5($token);

        return [
            'token'         => $token,
            'refresh_token' => $refreshToken,
        ];
    }

    public function getDevice()
    {
        $device = Device::where('token', request()->header('Next-Token'))->first();

        return $device;
    }
}
