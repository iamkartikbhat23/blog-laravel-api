<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class PostController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($offset=0,$id=null)
    {
        if($id == null) {
            $posts = Post::with("author","likes")->limit(50)->offset($offset*50)->get();
        } else {
            $posts = Post::with("author","likes")->where(['author_id'=>$id])->limit(50)->offset($offset*50)->get();
        }

        return $this->sendResponse(PostResource::collection($posts), 'Post retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post = [
            'title' => $request->title,
            'body' => $request->body,
            'slug' => Str::slug($request->title),
            'image_url' => "https://picsum.photos/seed/lion".rand(1000, 5000)."/400/210",
            'author_id'=> $request->author_id
        ] ;

        $postData = Post::create($post);

        return $this->sendResponse(new PostResource($postData), 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key,$showAuthorPosts = false,$userId = null)
    {
        Log::info("UserId") ;
        Log::info($userId) ;
        $post = Post::where(['slug'=>$key])->orWhere(['id'=>$key])->first();

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        if($userId!=null) {
            $userLikeStatusOnPost = Like::where(['user_id' => $userId,'post_id' => $post->id])->count() ;
        } else {
            $userLikeStatusOnPost = null ;
        }
        $post->user_like_status = $userLikeStatusOnPost ;

        $response = [
            'post' =>  new PostResource($post) ,
        ] ;
        if($showAuthorPosts) {
            $authorPosts = Post::where(['author_id' => $post->author_id])->latest()->take(6)->get() ;
            $response['author_posts'] = PostResource::collection($authorPosts);
        }


        return $this->sendResponse($response, 'Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post->title = $input['title'];
        $post->body = $input['body'];
        $post->save();

        return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Post::where(['id' => $id])->delete()) {
            $status = true ;
        } else {
            $status = false ;
        }

        return $this->sendResponse(['status'=>$status], 'Post deleted successfully.');
    }

    public function likeStatus(Request $request) {
        $likeStatus = $request->status ;
        Log::info("likeStatus") ;
        Log::info($request) ;
        $likeInfo = [
            'user_id' => $request->user_id ,
            'post_id' => $request->post_id
        ] ;
        if($likeStatus) {
            Like::insert($likeInfo) ;
        } else {
            Like::where($likeInfo)->delete();
        }

        return $this->sendResponse([], $likeStatus ? 'Post liked!' : 'Post Unliked.');

    }
}
