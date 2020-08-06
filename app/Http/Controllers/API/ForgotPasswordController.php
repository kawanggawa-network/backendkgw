<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\ForgotPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper\ResponseFormatter;

class ForgotPasswordController extends Controller
{
    /**
     * Request for reset password code via email.
     *
     * @return Response
     */
    public function requestResetPassword()
    {
        $validator = \Validator::make(request()->all(), [
            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        $user = User::whereEmail(request()->email)->first();
        
        // Check last request password reset
        $minSecond = 60;
        $check = ForgotPassword::where('user_id', $user->id)->first();
        if (!is_null($check)) {
            if ($check->created_at->diffInSeconds() < $minSecond) {
                $wait = $minSecond - $check->created_at->diffInSeconds();

                return ResponseFormatter::error(400, 'Wait ' . $wait . ' seconds to request again.');
            }
        }

        // Generate reset password
        ForgotPassword::generate($user);

        return ResponseFormatter::success([
            'is_sent' => true
        ]);
    }

    /**
     * Update password by sending Code & New Password.
     *
     * @return Response
     */
    public function updatePassword()
    {
        $validator = \Validator::make(request()->all(), [
            'code'          => 'required|exists:forgot_passwords,code',
            'password'      => 'required',
            'c_password'    => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        $check = ForgotPassword::check(request()->code);
        if (!$check) {
            return ResponseFormatter::error(400, 'Code not found or has expired');
        }

        $reset = ForgotPassword::updatePassword(request()->code, request()->password);
        if (!$reset) {
            return ResponseFormatter::error(400, 'There is a problem while updating password');
        }

        return ResponseFormatter::success([
            'is_updated' => true
        ]);
    }
}
