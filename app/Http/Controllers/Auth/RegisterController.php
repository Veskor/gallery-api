<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @SWG\Post(
     *   tags={"Authentication"},
     *   path="/register",
     *   summary="Register new user",
     *   operationId="createUser",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              property="first_name",
     *              description="user first name",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="last_name",
     *              description="user last name",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="email",
     *              description="user email",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="password",
     *              description="user password",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="password_confirmation",
     *              description="user password confirm",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="terms_and_conditions",
     *              description="accepted/declined terms and conditions",
     *              type="boolean"
     *          ),
     *      ),
     *   ),
     *   @SWG\Response(response=200, description="User registrated"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     */
    protected function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json(compact('user'));
    }

}
