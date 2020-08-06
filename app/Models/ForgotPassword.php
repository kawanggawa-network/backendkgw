<?php

namespace App\Models;

use Carbon\Carbon;
use App\Mail\SendForgotPassword;
use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    /**
     * Relation to User.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Generate forgot password.
     *
     * @param \App\Models\User $user
     * 
     * @return void
     */
    public static function generate(User $user)
    {
        if (is_null($user->email)) {
            return false;
        }

        \DB::transaction(function() use($user) {
            // Clean older reset password
            ForgotPassword::where('user_id', $user->id)->delete();

            // Generate Code
            $length = 6;
            $expireInHour = 1;

            do {
                $code = strtoupper(\Str::random($length));
                $count = ForgotPassword::whereCode($code)->count();
            } while ($count > 0);

            $payload = [
                'user_id' => $user->id,
                'code' => $code,
                'expires_at' => Carbon::now()->addHour($expireInHour)->format('Y-m-d H:i:s'),
            ];

            $object = ForgotPassword::create($payload);

            // Send Email
            \Mail::to($user->email)->send((new SendForgotPassword($object)));
        });
    }

    /**
     * Verify forgot password code.
     *
     * @param string $code
     * 
     * @return mixed
     */
    public static function check($code = '')
    {
        $check = ForgotPassword::whereCode($code)->first();
        if (is_null($check)) {
            return false;
        }

        $expireIn = Carbon::parse($check->expires_at);
        $now = Carbon::now();
        if ($now > $expireIn) {
            return false;
        }

        return true;
    }

    /**
     * Update password.
     *
     * @param string $code
     * @param string $newPassword
     * 
     * @return boolean
     */
    public static function updatePassword($code = '', $newPassword = '')
    {
        $object = ForgotPassword::whereCode($code)->first();
        if (is_null($object) || !ForgotPassword::check($code)) {
            return false;
        }

        \DB::transaction(function() use($object, $newPassword) {
            // Update Password
            $user = $object->user;
            $user->password = bcrypt($newPassword);
            $user->save();

            // Delete Forgot Password Object
            $object->delete();
        });

        return true;
    }
}
