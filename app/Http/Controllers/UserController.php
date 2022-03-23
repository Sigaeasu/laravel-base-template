<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userServices;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserServices $userServices)
        {
            $this->middleware('auth:api');
            $this->userServices = $userServices;
        }

    // Get all user data
    public function index()
        {

            $users = $this->userServices->fetchAll();

            if ($users)
                return $this->successRes($users, msgFetch(), 200);

            return $this->errorRes(msgNotFound('Users'), 404);

        }

    // Get one user data by ID
    public function show($id)
        {
            $user = $this->userServices->fetchById($id);

            if (!$user)
                return $this->errorRes(msgNotFound('User'), 404);

            return $this->successRes($user, msgFetch(), 200);
        }

    public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:40',
                'name' => 'required|max:150',
                'password' => 'required|string|max:120',
                'email' => 'required|email|max:50',
            ]);

            if ($validator->fails()) {
                return $this->errorRes($validator->getMessageBag()->toArray());
            }
            try {
                // Store User
                $store = $this->userServices->store($request);

                if($store){
                    return $this->successRes($store, msgStored());
                }else{
                    return $this->errorRes(msgNotStored());
                }
            } catch(\Exception $e){
                return $this->errorRes($e);
            }
        }

    public function update(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'username' => 'required|string|max:40',
                'name' => 'required|max:150',
                'password' => 'required|string|max:120',
                'email' => 'required|email|max:50',
            ]);

            if ($validator->fails()) {
                return $this->errorRes($validator->getMessageBag()->toArray());
            }
            try {
                // Check if user exist
                $user = $this->userServices->fetchById($request->id);

                if (!$user)
                    return $this->errorRes(msgNotFound('User'), 404);

                // Update
                $update = $this->userServices->update($user, $request);

                if($update){
                    return $this->successRes($update, msgUpdated());
                }else{
                    return $this->errorRes(msgNotUpdated());
                }
            } catch(\Exception $e){
                return $this->errorRes($e);
            }
        }
}