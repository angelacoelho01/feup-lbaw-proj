<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Education;
use App\Models\Employment;
use App\Models\FriendRequest;
use App\Models\JoinRequest;
use App\Models\Place;
use App\Models\User;

class ProfileController extends Controller
{
    public function show($user)
    {
        $user = User::findOrFail($user);

        $this->authorize('viewProfile', $user);

        $hometown = $user->cities()->where('is_hometown', '=', true)
            ->get()->first();
        $currentCity = $user->cities()->where('is_current', '=', true)
            ->get()->first();
        $education = $user->educations()->orderBy('id', 'desc')->get()->first();
        $employment = $user->employments()->orderBy('id', 'desc')->get()->first();
        $posts = $user->posts()->where('group_id', null)->orderBy('date_time', 'DESC')->get();
        $groups = JoinRequest::where('user_id', '=', $user->id)
            ->where('accepted', true)->take(3)->get();
        $friends = FriendRequest::where('accepted', true)
            ->where('sender_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->where('accepted', true)
                    ->where('receiver_id', $user->id);
            })
            ->take(3)
            ->get();
        return view(
            'pages.profiles.index',
            [
                'user' => $user,
                'groups' => $groups,
                'friends' => $friends,
                'hometown' => $hometown,
                'posts' => $posts,
                'currentCity' => $currentCity,
                'education' => $education,
                'employment' => $employment
            ]
        );
    }

    public function edit($user)
    {
        $user = User::findOrFail($user);

        $this->authorize('update', $user);

        $hometown = $user->cities()->where('is_hometown', '=', true)->get()->first();
        $currentCity = $user->cities()->where('is_current', '=', true)
            ->get()->first();
        $education = $user->educations()->orderBy('id', 'desc')->get()->first();
        $employment = $user->employments()->orderBy('id', 'desc')->get()->first();

        return view(
            'pages.profiles.edit',
            [
                'user' => $user,
                'hometown' => $hometown,
                'currentCity' => $currentCity,
                'education' => $education,
                'employment' => $employment,
            ]
        );
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'image' => '',
            'nickname' => '',
            'name' => '',
            'description' => '',
            'phone_number' => 'nullable|digits:9',
            'website' => 'url|nullable',
            'gender' => '',
            'birthdate' => 'before:today|nullable',
            'political_ideology' => '',
            'religious_belief' => '',
            'hometown' => '',
            'current_city' => '',
            'education' => '',
            'employment' => '',
            'is_public' => '',
        ]);


        $user->nickname = $data['nickname'];
        $user->phone_number = $data['phone_number'];
        $user->website = $data['website'];
        $user->gender = $data['gender'];
        $user->birthdate = $data['birthdate'];

        if (request('name')) {
            $user->name = $data['name'];
        }

        if (request('description')) {
            $user->description = $data['description'];
        }

        if (request('is_public')) {
            $user->is_public = true;
        } else {
            $user->is_public = false;
        }

        if (request('political_ideology') !== 'null') {
            $user->political_ideology = $data['political_ideology'] == "none" ?
                null : $data['political_ideology'];
        }

        if (request('religious_belief') !== 'null') {
            $user->religious_belief = $data['religious_belief'] == "none" ? null : $data['religious_belief'];
        }

        if (request('image')) {
            $imagePath = request('image')->store('images/profiles', 'public');
            $user->profile_pic = $imagePath;
        }

        if (request('hometown')) {
            $hometown = City::where('name', $data['hometown'])->get();
            if ($hometown->isEmpty()) {
                $hometown = new City;
                $hometown->name = $data['hometown'];
                $hometown->save();
            } else {
                $hometown = $hometown->first();
            }

            $user->cities()->attach($hometown, ['is_hometown' => true, 'is_current' => false, 'is_old_city' => false]);
        }

        if (request('current_city')) {
            $currentCity = City::where('name', $data['current_city'])->get();
            if ($currentCity->isEmpty()) {
                $currentCity = new City;
                $currentCity->name = $data['current_city'];
                $currentCity->save();
            } else {
                $currentCity = $currentCity->first();
            }

            $user->cities()->attach($currentCity, ['is_hometown' => false, 'is_current' => true, 'is_old_city' => false]);
        }

        if (request('education')) {
            $education = Place::where('name', $data['education'])->where('type', 'University')->get();
            if ($education->isEmpty()) {
                $education = new Place;
                $education->name = $data['education'];
                $education->type = 'University';
                $education->save();
            } else {
                $education = $education->first();
            }

            $study = new Education;
            $study->place()->associate($education);
            $study->user_id = $user->id;
            $study->save();

            $user->educations()->save($study);
        } else {
            $edu = $user->educations()->get();
            foreach ($edu as $education) {
                $education->user_id = null;
                $education->save();
            }
        }


        if (request('employment')) {
            $employment = Place::where('name', $data['employment'])->where('type', 'Job')->get();
            if ($employment->isEmpty()) {
                $employment = new Place;
                $employment->name = $data['employment'];
                $employment->type = 'Job';
                $employment->company = $data['employment'];
                $employment->save();
            } else {
                $employment = $employment->first();
            }

            $study = new Employment;
            $study->place()->associate($employment);
            $study->user_id = $user->id;
            $study->save();

            $user->employments()->save($study);
        } else {
            $jobs = $user->employments()->get();
            foreach ($jobs as $job) {
                $job->user_id = null;
                $job->save();
            }
        }

        $user->save();

        return redirect("/profile/{$user->id}");
    }

    public function destroy(User $user)
    {
        $this->authorize('update', $user);

        $user->delete();

        return redirect('/register');
    }

    public function changePassword(User $user)
    {
        $this->authorize('changePassword', $user);

        return view('pages.profiles.change-password', [
            'user' => $user,
        ]);
    }
    public function friends(User $user)
    {
        $this->authorize('viewProfile', $user);

        $friends = FriendRequest::where('accepted', true)
            ->where('sender_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->where('accepted', true)
                    ->where('receiver_id', $user->id);
            })
            ->get();
        $friendRequests = FriendRequest::where('accepted', false)
            ->where('sender_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->where('accepted', false)
                    ->where('receiver_id', $user->id);
            })
            ->get();

        return view('pages.profiles.friends', [
            'profile' => $user,
            'friends' => $friends,
            'friendRequests' => $friendRequests 
        ]);
    }

    public function groups(User $user)
    {

        $this->authorize('viewProfile', $user);

        $groups = JoinRequest::where('user_id', '=', $user->id)
            ->where('accepted', true)->get();

        return view('pages.profiles.groups', [
            'groups' => $groups,
        ]);
    }
}
