<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get formatted ID.
     *
     * @return string
     */
    public function getFormattedIdAttribute()
    {
        return '#' . $this->id;
    }

    /**
     * Get photo url attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        if (!is_null($this->getAttribute('photo'))) {
            return asset(\Storage::url($this->getAttribute('photo')));
        } else {
            return \Gravatar::get($this->getAttribute('email'), ['size'   => 40, 'secure' => true]);
        }
    }

    /**
     * Get response attribute.
     *
     * @return array
     */
    public function getResponseAttribute()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'phone_number' => $this->phone_number,
            'profile_percent' => $this->profile_progress_percent,
        ];
    }

    /**
     * Get progress profile in percent based on filled field.
     *
     * @return double
     */
    public function getProfileProgressPercentAttribute()
    {
        $attributes = [
            'name',
            'email',
            'phone_number',
            'photo'
        ];

        $grade = 0;
        foreach ($attributes as $attribute) {
            if (!is_null($this->getOriginal($attribute))) {
                $grade += 100;
            }
        }

        $average = number_format($grade / count($attributes), 2, '.', '');

        return (float) $average;
    }

    /**
     * Send notification.
     *
     * @param string $title     Message title.
     * @param string $content   Message content.
     * @param array  $data      Data content.
     * @param string $url       Launch URL.
     *
     * @return void
     */
    public function sendNotification($title = '', $content = '', $data = [], $url = null)
    {
        \DB::transaction(function() use($title, $content, $data, $url) {
            $oneSignal = new OneSignal('');
            $notification = new Notification(
                $oneSignal,
                config('services.onesignal.app_id'),
                config('services.onesignal.api_key')
            );
            $notificationData = [
                'contents' => [
                    'en' => $content,
                ],
                'headings' => [
                    'en' => $title,
                ],
                'filters' => [
                    [
                        'field' => 'tag',
                        'key' => 'user_id',
                        'relation' => '=',
                        'value' => $this->attributes['id'],
                    ],
                ],
                // 'data' => $data
            ];
            if (count($data) > 0) {
                $notificationData['data'] = $data;
            }
            $notification->create($notificationData);
        });
    }
}
