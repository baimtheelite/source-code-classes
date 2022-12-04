<?php

namespace App\Repositories;

use App\Mail\MailAccountVerification;
use App\Mail\MailChangeEmailVerification;
use App\Models\Account;
use App\Models\AccountVerification;
use App\Models\News;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;

class EmailVerification {
    public function sendVerification(Account $account, $newEmail = false)
    {
        $token = Str::random(64);
        $expired = now()->addMinutes(60);

        AccountVerification::where('account_id', $account->id)->delete();

        AccountVerification::create([
            'account_id' => $account->id,
            'token' => $token,
            'expired_at' => $expired,
        ]);

        Mail::to($account->email)->queue(new MailAccountVerification($token));
    }

    public function sendChangeEmailVerification(Account $account)
    {
        $token = Str::random(64);
        $expired = now()->addMinutes(60);

        AccountVerification::where('account_id', $account->id)->delete();

        AccountVerification::create([
            'account_id' => $account->id,
            'token' => $token,
            'expired_at' => $expired,
        ]);

        Mail::to($account->new_email)->queue(new MailChangeEmailVerification($token));
    }
}
