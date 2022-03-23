<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use App\Models\User;

class UserServices
{
    // index
    public function fetchAll()
    {
        return User::get();
    }

    // show user by id
    public function fetchById($id)
    {
        return User::whereId($id)->first();
    }

    // store user
    public function store($data)
    {
        try {
            $user = User::create([
                'username' => $data->username,
                'name' => $data->name,
                'password' => md5($data->password),
                'email' => $data->email,
            ]);

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // update user
    public function update($user, $data)
    {
        try {

            $user->username = $data->username;
            $user->name = $data->name;

            if ($data->password != null && $data->password != '')
                $user->password = md5($data->password);

            $user->email = $data->email;

            $user->save();

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generateJWTToken($data) 
        {    
            try {
                $token = JWTAuth::fromUser($data);

                if ($token)
                    return ['user' => $data, 'access_token' => $token];

                return false;
                
            } catch (Exception $e) {
                throw $e;
            }
        }
}