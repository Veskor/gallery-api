<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\User;

class AuthorsGalleriesController extends Controller
{
    /**
     * @SWG\Get(
     *   tags={"Author Gallery"},
     *   path="/authors-galleries/{id}/{page}/{term}",
     *   summary="Get author galleries",
     *   operationId="allAuthorGalleries",
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
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="gallery id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}},
     * )
     */
    public function index($id, $page = 1, $term = '')
    {
        $user = User::find($id);

        return Gallery::search(($page - 1) * 10, 10, $term, $user);

    }

}

