<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use App\Comment;
use App\Gallery;

class CommentsController extends Controller
{

    /**
     * @SWG\Post(
     *   tags={"Comment"},
     *   path="/galleries/{id}/comments",
     *   summary="Create a new comment in gallery",
     *   operationId="createComment",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              property="body",
     *              description="Comment body",
     *              type="string"
     *          ),
     *      ),
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="gallery id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=201, description="Resource created"),
     *   @SWG\Response(response=401, description="Not authorized"),
     *   @SWG\Response(response=403, description="Forbidden"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     */
    public function store(CommentRequest $request, $gallery_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $comment = new Comment();
        $gallery = Gallery::find($gallery_id);

        $comment->owner_id = $user->id;
        $comment->gallery_id = $gallery_id;
        $comment->body = $request->input('body');

        $comment->save();

        $comment->owner = $user;
        $comment->gallery = $gallery;

        $comment->with('owner');

        return $comment;
    }


    /**
     * @SWG\Delete(
     *   tags={"Comment"},
     *   path="/comments/{id}",
     *   summary="Delete a comment in gallery",
     *   operationId="deleteComment",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="comment id",
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
        $comment = Comment::find($id);

        $comment->delete();

        return['success'=>true];
    }
}

