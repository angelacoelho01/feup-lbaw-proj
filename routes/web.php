<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

Route::get('/', 'Auth\LoginController@home');

// Password Reset
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::get('change-password', 'ChangePasswordController@index');
Route::post('change-password', 'ChangePasswordController@store')->name('password.store');


// Profiles
Route::get('profile/{user}', 'ProfileController@show')->name('profile.show');
Route::get('profile/{user}/edit', 'ProfileController@edit')->name('profile.edit');
Route::patch('profile/{user}', 'ProfileController@update')->name('profile.update');
Route::delete('profile/{user}', 'ProfileController@destroy')->name('profile.destroy');
Route::get('profile/{user}/friends', 'ProfileController@friends')->name('profile.friends');
Route::get('profile/{user}/groups', 'ProfileController@groups')->name('profile.groups');

// Admins
Route::get('admin/{id}', 'AdminController@show')->name('admin.show');
Route::patch('admin/{id}', 'AdminController@update')->name('admin.update');

// Search
Route::get('search', 'SearchController@show')->name('search');
Route::get('search/filter/{searchedQuery}', 'SearchController@filter')->name('search.filter');

// Post
Route::get('/post/{id}', 'PostsController@show')->name('post.show');
Route::post('/post/create/{group_id?}', 'PostsController@create')->name('post.create');
Route::get('/post/{post_id}/delete', 'PostsController@delete')->name('post.delete');
Route::get('/post/{post_id}/edit', 'PostsController@edit')->name('post.edit');
Route::patch('/post/{post_id}', 'PostsController@update')->name('post.update');
Route::get('/home', 'PostsController@index')->name('home');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name('register');
Route::post('register', 'Auth\RegisterController@register');

//Static
Route::get('contact-us', 'Static\ContactUsController@show');
Route::get('our-mission', 'Static\OurMissionController@show');

// Friend Request
Route::get('friend-requests', 'FriendRequestController@show')->name('friend-requests');

// Accept or decline friend request
Route::post('friend-requests/{friend_request_id}', 'FriendRequestController@reply')->name('friend-requests.reply');

// Add friend
Route::post('profile/{user_id}', 'FriendRequestController@add')->name('friend-requests.add');

// Remove friend
Route::post('remove/{friend_request_id}', 'FriendRequestController@remove')->name('friend-requests.remove');

// Reactions
Route::post('/posts/{post_id}/reactions/', 'ReactionController@store')->name('posts.reactions.store');
Route::post('/comments/{comment_id}/reactions/', 'ReactionController@storeComment')->name('comments.reactions.store');

// Comments
Route::post('/posts/{post_id}/comments/', 'CommentController@store')->name('posts.comments.store');
Route::post('/comments/{comment_id}/comments/', 'CommentController@storeComment')->name('comments.comments.store');
Route::get('/comment/{comment_id}/delete', 'CommentController@destroy')->name('comment.delete');
Route::get('/comment/{comment_id}/edit', 'CommentController@edit')->name('comment.edit');
Route::patch('/comment/{comment_id}', 'CommentController@update')->name('comment.update');

// Groups
Route::get('group/create', 'UserGroupController@createPage')->name('create.page');
Route::post('group/create', 'UserGroupController@create')->name('group.create');
Route::get('group/{id}', 'UserGroupController@show')->name('group.show');
Route::get('group/{id}/edit', 'UserGroupController@edit')->name('group.edit');
Route::patch('group/{id}', 'UserGroupController@update')->name('group.update');
Route::delete('group/{id}', 'UserGroupController@destroy')->name('group.destroy');
Route::post('group/join/{id}', 'UserGroupController@join')->name('group.join');
Route::delete('group/leave/{id}', 'UserGroupController@leave')->name('group.leave');
Route::post('group/answer/{joinRequest}', 'UserGroupController@answerJoinRequest')->name('group.answerJoinRequest');
Route::get('group/{id}/members', 'UserGroupController@members')->name('group.members');
