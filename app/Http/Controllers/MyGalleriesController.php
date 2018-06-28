<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use App\Gallery;

class MyGalleriesController extends Controller
{

    /**
     * @SWG\Get(
     *   tags={"Gallery"},
     *   path="/my-galleries/{page}/{term}",
     *   summary="Get my galleries",
     *   operationId="galleries",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     type="string",
     *     name="page",
     *     in="path",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     type="string",
     *     name="term",
     *     in="path",
     *     required=false
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}},
     * )
     */
    public function index($page = 1, $term = '')
    {
        $user = \JWTAuth::parseToken()->authenticate();

        return Gallery::search(($page - 1) * 10, 10, $term, $user);
    }

}

