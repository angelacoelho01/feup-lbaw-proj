<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $post = Post::find($post_id);

        $comment = new Comment();
        $comment->content = $request->input('comment-text');
        $comment->user_id = $request->user()->id;
        $comment->post_id = $post_id;
        $request->user()->comments()->save($comment);
        $post->comments()->save($comment);
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request, $comment_id)
    {
        $comment = Comment::Find($comment_id);

        $reply = new Comment();
        $reply->content = $request->input('comment-text');
        $reply->user_id = $request->user()->id;
        $reply->comment_id = $comment->id;
        $request->user()->comments()->save($reply);
        $comment->comments()->save($reply);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        $request->validate([
            'comment-text' => 'bail|required|max:255',
        ]);
        $comment->update(['content' => $request->input('comment-text')]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $comment_id)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $comment = Comment::findOrFail($comment_id);
        if ($request->user()->cannot('delete', $comment)) {
            abort(403);
        }
        $comment->delete();
        return redirect()->back();
    }
}
