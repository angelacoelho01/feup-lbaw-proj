<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\JoinRequest;
use App\Models\Post;

class UserGroupController extends Controller
{
    public function create(Request $request)
    {
        $group = new UserGroup();
        $group->name = $request->input('group-name');
        $group->is_public = $request->input('is-public') == "true";
        $group->save();
        $admin_id = $request->user()->id;
        $group->admins()->sync($admin_id);
        $joinRequest = new JoinRequest();
        $joinRequest->user_group_id = $group->id;
        $joinRequest->user_id = $request->user()->id;
        $joinRequest->is_request = true;
        $joinRequest->accepted = true;
        $joinRequest->save();
        return redirect()->route('group.show', $group->id);
    }

    public function createPage()
    {
        return view('pages.group.create');
    }

    public function show($group_id)
    {
        $group = UserGroup::findOrFail($group_id);
        $this->authorize('viewGroup', $group);
        $groupJoinRequests = $group->joinRequests()->where('accepted', 'true')->get();
        $members = array();
        foreach ($groupJoinRequests as $joinRequest) {
            $member = User::where('id', $joinRequest->user_id)->get()->first();
            if ($member != null) {
                array_push($members, $member);
            }
        }
        $members = array_slice($members, 0, 3, true);
        $posts = Post::where('group_id', $group->id)->orderBy('date_time', 'DESC')->paginate(5);
        $notAnsweredJoinRequests = $group->joinRequests()->where('accepted', 'false')->get();
        return view('pages.group.index', ['group' => $group, 'members' => $members, 'posts' => $posts, 'joinRequests' => $notAnsweredJoinRequests]);
    }

    public function createPostInGroup(Request $request, $group_id)
    {
        redirect()->route('/post/create/' . $group_id);
    }

    public function edit($group_id)
    {
        $group = UserGroup::findOrFail($group_id);

        $this->authorize('update', $group);

        return view(
            'pages.group.edit',
            [
                'group' => $group,
            ]
        );
    }

    public function update($group_id)
    {
        $group = UserGroup::findOrFail($group_id);

        $data = request()->validate([
            'image' => '',
            'name' => 'required',
            'description' => '',
            'is_public' => ''
        ]);
        $group->name = $data['name'];
        $group->is_public = request('is_public') ? true : false;
        if (request('image')) {
            $group->group_pic = $data['image']->store('images/groups', 'public');
        }
        if (request('description')) {
            $group->description = $data['description'];
        }
        $group->save();
        return redirect()->route('group.show', $group->id);
    }

    public function destroy($group_id)
    {
        $group = UserGroup::findOrFail($group_id);

        $this->authorize('delete', $group);

        $group->delete();

        return redirect('home');
    }

    public function isInGroup($user, $group)
    {

        $joinRequests = $group->joinRequests()->get();

        foreach ($joinRequests as $joinRequest) {
            if ($joinRequest->user_id == $user->id) {
                return true;
            }
        }
        return false;
    }

    public function join($group_id)
    {

        $user = Auth::user();
        $group = UserGroup::findOrFail($group_id);

        if (!$this->isInGroup($user, $group)) {
            $joinRequest = new JoinRequest();
            $joinRequest->user_id = $user->id;
            $joinRequest->user_group_id = $group_id;
            $joinRequest->is_request = true;
            $joinRequest->accepted = false;
            $joinRequest->save();
        }

        return redirect()->back();
    }

    public function leave($group_id)
    {
        $user = Auth::user();
        $group = UserGroup::findOrFail($group_id);
        $joinRequests = $group->joinRequests()->get();

        foreach ($joinRequests as $joinRequest) {
            if ($joinRequest->user_id == $user->id) {
                $joinRequest->delete();
                break;
            }
        }
        return redirect()->back();
    }

    public function answerJoinRequest(Request $request, $joinRequest_id)
    {
        $joinRequest = JoinRequest::findOrFail($joinRequest_id);

        if ($request->input("type") == "accept") {
            $joinRequest->accepted = true;
            $joinRequest->save();
        } else {
            $joinRequest->delete();
        }
        return redirect()->back();
    }

    public function members($group_id)
    {
        $group = UserGroup::findOrFail($group_id);

        $this->authorize('viewGroup', $group);

        $groupJoinRequests = JoinRequest::where('accepted', true)->where('user_group_id', $group_id)->get();
        $members = array();
        foreach ($groupJoinRequests as $joinRequest) {
            $member = User::where('id', $joinRequest->user_id)->get()->first();
            if ($member != null) {
                array_push($members, $member);
            }
        }

        return view('pages.group.members', [
            'members' => $members,
        ]);
    }
}
