<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\JoinRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\MultimediaContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{

    public function create(Request $request)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $post = new Post();
        $request->validate([
            'post-text' => 'bail|required|max:512',
            'post-visibility' => 'bail|required|in:public,private',
        ]);
        $post->is_public = $request->input('post-visibility') == 'public';
        $post->post_text = $request->input('post-text');
        if ($request->group_id != null) {
            $post->group_id = $request->group_id;
        }
        $request->user()->posts()->save($post);
        if ($request->hasFile('post-media')) {
            foreach ($request->file('post-media') as $file) {
                $this->saveMedia($post, $file);
            }
        }
        return redirect()->back();
    }

    public function delete(Request $request, $post_id)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $post = Post::findOrFail($post_id);
        if ($request->user()->cannot('delete', $post)) {
            abort(403);
        }
        $post->delete();
        return redirect()->back();
    }

    public function edit(Request $request, $post_id)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $post = Post::findOrFail($post_id);
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        return view('pages.posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $post_id)
    {
        if ($request->user() == null) {
            abort(403);
        }
        $post = Post::findOrFail($post_id);
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        $request->validate([
            'post-text' => 'bail|required|max:255',
            'post-visibility' => 'bail|required|in:public,private',
        ]);
        $post->update([
            'post_text' => $request->input('post-text'),
            'is_public' => $request->input('post-visibility') == 'public',
        ]);
        return redirect()->back();
    }

    public function show(Request $request, $post_id)
    {
        $friends = null;
        $groups = null;

        if (Auth::check()) {
            $friends = FriendRequest::where('receiver_id', '=', Auth::user()->id)->where('accepted', 'true')->get();
            $groups = JoinRequest::where('user_id', '=', Auth::user()->id)->where('accepted', 'true')->get();
        }

        $post = Post::findOrFail($post_id);
        if ($request->user() == null && !$post->is_public) {
            abort(403);
        } elseif ($request->user() != null && $request->user()->cannot('view', $post)) {
            abort(403);
        }
        $user = User::findOrFail($post->user_id);
        return view('pages.posts.post', [
            'post' => $post,
            'user' => $user,
            'friends' => $friends,
            'groups' => $groups
        ]);
    }

    public function index(Request $request)
    {
        $friends = null;
        $groups = null;
        $user = null;
        $posts = null;
        $friendRequests = null;


        if (Auth::check()) {
            $user = Auth::user();
            $friends = FriendRequest::where('accepted', true)
                ->where('sender_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('accepted', true)
                        ->where('receiver_id', $user->id);
                })
                ->take(3)
                ->get();
            $friendRequests = FriendRequest::where('receiver_id', '=', $user->id)->where('accepted', false)->get();
            $groups = JoinRequest::where('user_id', '=', $user->id)->where('accepted', 'true')->take(3)->get();
        }

        if ($request->input('sorting') === null || $request->input('sorting') === 'recent') {
            $posts = Post::where('group_id', null)->orderBy('date_time', 'DESC')->paginate(5);
        } else if ($request->input('sorting') === 'trending') {
            $posts = DB::table('posts')
                ->join('reactions', 'posts.id', '=', 'reactions.post_id')
                ->orderBy('date_time', 'DESC')
                ->select('posts.id', DB::raw('COUNT(*) AS total'))
                ->groupBy('posts.id')
                ->get();
            $posts = $posts
                ->where('total', '>', '3');
            $ids = array();
            foreach ($posts as $post) {
                array_push($ids, $post->id);
            }
            $posts = Post::whereIn('id', $ids)->paginate(5);
        }

        if ($request->ajax()) {
            $view = view('pages.posts.posts', [
                'posts' => $posts,
                'friends' => $friends,
                'groups' => $groups,
                'friendRequests' => $friendRequests,
            ])->render();
            return response()->json(['html' => $view]);
        }


        return  view('pages.home',  [
            'user' => $user,
            'posts' => $posts,
            'friends' => $friends,
            'groups' => $groups,
            'friendRequests' => $friendRequests,
        ]);
    }

    private function saveMedia($post, $file)
    {
        $type = $file->getClientMimeType();
        $media_content = new MultimediaContent();
        if (
            $type == 'image/jpg' || $type == 'image/png' ||
            $type == 'image/jpeg' || $type == 'image/gif' ||
            $type == 'image/svg+xml'
        ) {
            $path = Storage::putFile(
                'public/images/posts',
                $file
            );
            $media_content->content_type = 'Photo';
        } elseif (
            $type == 'video/mp4' ||
            $type == 'video/webm' || $type == 'video/ogg'
        ) {
            $path = Storage::putFile(
                'public/videos/posts',
                $file
            );
            $media_content->content_type = 'Video';
        }
        $media_content->post_id = $post->id;
        $media_content->content = $path;

        $post->multimediaContent()->save($media_content);
    }
}
