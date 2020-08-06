<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\Helper\ResponseFormatter;
use App\Http\Controllers\Helper\OneSignalAdministration;

class UserController extends Controller
{
    /**
     * Get profile.
     *
     * @return \App\Models\User
     */
    public function getProfile()
    {
        return ResponseFormatter::success(request()->user()->response);
    }

    /**
     * Update profile.
     *
     * @return \App\Models\User
     */
    public function updateProfile()
    {
        // Validate Input
        $requestClass = (new UserUpdateRequest);
        $validator = \Validator::make(request()->all(), $requestClass->rules());
        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        // Get input
        $payload = request()->only(array_keys($requestClass->rules()));
        $payload = $this->prepareData($payload);

        // Update Data
        request()->user()->update($payload);

        return $this->getProfile();
    }

    /**
     * Prepare Data.
     *
     * @param array $payload
     * 
     * @return array
     */
    public function prepareData($payload = [])
    {
        // Upload File
        if (isset($payload['photo']) && !is_null($payload['photo'])) {
            $payload['photo'] = request()->file('photo')->store(
                'assets/users', 'public'
            );
        }

        // Hash Password
        if (isset($payload['password']) && !is_null($payload['password'])) {
            $payload['password'] = \Hash::make($payload['password']);
        }

        // Clean data
        foreach ($payload as $key => $value) {
            if (is_null($value)) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }

    /**
     * Set notification (register and unregister).
     *
     * @param Request $request
     * 
     * @return Response
     */
    public function setNotification(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'player_id' => 'required',
                'type' => 'required|in:subscribe,unsubscribe',
            ]
        );

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        $oneSignal = new OneSignalAdministration();
        if ($request->type == 'subscribe') {
            $oneSignal->addTags($request->player_id);
            $request->user()->sendNotification('Hi!', 'Welcome to ' . config('app.name') . ' App');
        } else {
            $oneSignal->removeTags($request->player_id);
        }
        
        return ResponseFormatter::success();
    }
}
