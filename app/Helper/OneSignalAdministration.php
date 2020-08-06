<?php

namespace App\Http\Controllers\Helper;

use Auth;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * OneSignal Administration (Add and Remove tags) Class
 */
class OneSignalAdministration
{
    /**
     * Add User ID Tags
     * @param string $playerId Player ID
     */
    public function addTags($playerId = '')
    {
        if ($playerId == '' || is_null($this->getUserId())) {
            return;
        }
        $payload = [
            'app_id' => config('services.onesignal.app_id'),
            'tags' => [
                'user_id' => $this->getUserId()
            ]
        ];
        return $this->send($payload, $playerId);
    }

    /**
     * Remove User ID Tags
     * @param string $playerId Player ID
     */
    public function removeTags($playerId = '')
    {
        if ($playerId == '' || is_null($this->getUserId())) {
            return;
        }
        $payload = [
            'app_id' => config('services.onesignal.app_id'),
            'tags' => [
                'user_id' => null
            ]
        ];
        return $this->send($payload, $playerId);
    }

    /**
     * Get User ID by Auth
     * @return integer User ID
     */
    protected function getUserId()
    {
        $user = Auth::user();
        if (is_null($user)) {
            return;
        }
        return $user->id;
    }

    /**
     * Send to One Signal
     * @param  string $payload  Payload
     * @param  string $playerId Player ID
     */
    protected function send($payload = '', $playerId = '')
    {
        $client = new Client();
        $response = $client->put('https://onesignal.com/api/v1/players/' . $playerId, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => $payload,
        ]);

        return $response->getBody();
    }
}
