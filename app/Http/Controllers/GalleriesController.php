<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Gallery;
use App\User;
use App\Image;
use App\Comment;

class GalleriesController extends Controller
{
    /**
     * @SWG\Get(
     *   tags={"Gallery"},
     *   path="/all-galleries/{page}/{term}",
     *   summary="Get all galleries",
     *   operationId="allGalleries",
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
    public function index($page,$term='')
    {
        return Gallery::search(($page - 1) * 10, 10, $term);

    }


    /**
     * @SWG\Post(
     *   tags={"Gallery"},
     *   path="/galleries",
     *   summary="Create a new gallery",
     *   operationId="createGallery",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              property="name",
     *              description="Gallery name",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="description",
     *              description="Gallery description",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="images",
     *              description="Array of image URLs",
     *              type="array",
     *              items={"type":"string"}
     *          ),
     *      ),
     *   ),
     *   @SWG\Response(response=201, description="Resource created"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     */
    public function store(GalleryRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $gallery = new Gallery();

        $gallery->name = $request->input('name');
        $gallery->description = $request->input('description');
        $gallery->owner_id = $user->id;

        $gallery->save();

        $imagesArray = $request->input('images');
        $images = [];

        foreach($imagesArray as $image){
            $newImage = new Image($image);

            $images[] = $newImage;
        }

        $gallery->images()->saveMany($images);

        return $gallery;

    }

    /**
     * @SWG\Get(
     *   tags={"Gallery"},
     *   path="/galleries/{id}",
     *   summary="Get a single gallery",
     *   operationId="singleGallery",
     *   produces={"application/json"},
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
     *   @SWG\Response(response=404, description="Not found"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}},
     * )
     */
    public function show($id)
    {
        $galleries =  Gallery::with([
            'images' => function($query){
                $query->orderBy('order');
            },
            'comments',
            'owner'
        ])->find($id);

        if(!isset($galleries)){
            abort(404, "Gallery doesn't exist!!!");
        }

        return $galleries;

    }

    /**
     * @SWG\Put(
     *   tags={"Gallery"},
     *   path="/galleries/{id}",
     *   summary="Update a new gallery",
     *   operationId="updateGallery",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              property="name",
     *              description="Gallery name",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="description",
     *              description="Gallery description",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="images",
     *              description="Array of image URLs",
     *              type="array",
     *              items={"type":"string"}
     *          ),
     *      ),
     *   ),
     * @SWG\Parameter(
     *              name="id",
     *              in="path",
     *              description="gallery id",
     *              required=true,
     *              type="integer"
     *   ),
     *   @SWG\Response(response=201, description="Resource created"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     */
    public function update(GalleryRequest $request, $id)
    {
        $gallery = Gallery::find($id);

        $gallery->name = $request->input('name');
        $gallery->description = $request->input('description');

        $gallery->save();

        $gallery->images()->delete();

        $imagesArray = $request->input('images');
        $images = [];

        foreach($imagesArray as $image) {
            $newImage = new Image($image);

            $images[] = $newImage;
        }

        $gallery->images()->saveMany($images);

        return $gallery;

    }

    /**
     * @SWG\Delete(
     *   tags={"Gallery"},
     *   path="/galleries/{id}",
     *   summary="Delete a gallery",
     *   operationId="deleteGallery",
     *   produces={"application/json"},
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
     *   @SWG\Response(response=404, description="Not found"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     */
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        $gallery->delete();

        return ['success'=>true];

    }
}
