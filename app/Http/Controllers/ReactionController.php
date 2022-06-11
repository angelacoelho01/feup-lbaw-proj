<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class ReactionController extends Controller
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
    public function create(Request $request)
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
        $post = Post::findOrFail($post_id);
        $current_reactions = $post->reactions()->where('post_id', '=', $post_id)
        ->where('user_id', '=', $request->user()->id);
        $reaction = new Reaction();

        if ($current_reactions->count() === 0) {
            if ($request->input('type') == 'positive') {
                $reaction->is_positive = '1';
            } else {
                $reaction->is_positive = '0';
            }
        } else {
            if ($current_reactions->get()->first()->is_positive && $request->input('type') == 'positive'
                || !$current_reactions->get()->first()->is_positive && $request->input('type') != 'positive') {
                    $this->destroy($current_reactions->get()->first());
                    $positive_total = $post->reactions()->where('is_positive', '1')->count();
                    $negative_total = $post->reactions()->where('is_positive', '0')->count();
                    return response([
                        "reaction" => "none",
                        "positive_count" => $positive_total,
                        "negative_count" => $negative_total
                    ], 200);
            } else {
                $this->destroy($current_reactions->get()->first());
                if ($request->input('type') == 'positive') {
                    $reaction->is_positive = '1';
                } else {
                    $reaction->is_positive = '0';
                }
            }
        }

        $reaction->user_id = $request->user()->id;
        $reaction->post_id = $post_id;
        $request->user()->reactions()->save($reaction);
        $post->reactions()->save($reaction);
        $positive_total = $post->reactions()->where('is_positive', '1')->count();
        $negative_total = $post->reactions()->where('is_positive', '0')->count();
        return response([
            "reaction" => $request->input('type'),
            "positive_count" => $positive_total,
            "negative_count" => $negative_total
        ], 200);

    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        $reaction = new Reaction();

        $current_reactions = $comment->reactions()->where('comment_id', '=', $comment_id)
            ->where('user_id', '=', $request->user()->id);

        if ($current_reactions->count() == 0) {
            if ($request->input('type') == 'positive') {
                $reaction->is_positive = '1';
            } else {
                $reaction->is_positive = '0';
            }
        } else {
            if ($current_reactions->get()->first()->is_positive && $request->input('type') == 'positive'
                || !$current_reactions->get()->first()->is_positive && $request->input('type') != 'positive') {
                    $this->destroy($current_reactions->get()->first());
                    $positive_total = $comment->reactions()->where('is_positive', '1')->count();
                    $negative_total = $comment->reactions()->where('is_positive', '0')->count();
                    return response([
                        "reaction" => "none",
                        "positive_count" => $positive_total,
                        "negative_count" => $negative_total
                    ], 200);
            } else {
                $this->destroy($current_reactions->get()->first());
                if ($request->input('type') == 'positive') {
                    $reaction->is_positive = '1';
                } else {
                    $reaction->is_positive = '0';
                } 
            }
        }

        $reaction->user_id = $request->user()->id;
        $reaction->comment_id = $comment->id;
        $request->user()->reactions()->save($reaction);
        $comment->reactions()->save($reaction);

        $positive_total = $comment->reactions()->where('is_positive', '1')->count();
        $negative_total = $comment->reactions()->where('is_positive', '0')->count();
        return response([
            "reaction" => $request->input('type'),
            "positive_count" => $positive_total,
            "negative_count" => $negative_total
        ], 200);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Http\Response
     */
    public function show(Reaction $reaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Reaction $reaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reaction $reaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reaction $reaction)
    {
        //
        $reaction->delete();
        return redirect()->route('home');
    }
}
