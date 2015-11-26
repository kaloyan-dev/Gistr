<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Routing\Controller;
use App\User;
use Auth;

class AuthController extends Controller
{
    
    public function redirectToProvider()
    {
        return Socialite::driver('github')
            ->scopes( ['gist'] )
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
        } catch (Exception $e) {
            return Redirect::to('auth/github');
        }

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return \Redirect::to('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $githubUser
     * @return User
     */
    private function findOrCreateUser($githubUser)
    {
        $user = User::firstOrCreate(['github_id'  => $githubUser->id])->fill([
            'username'   => $githubUser->user['login'],
            'name'       => $githubUser->name,
            'email'      => $githubUser->email,
            'github_id'  => $githubUser->id,
            'avatar'     => $githubUser->avatar,
            'auth_token' => $githubUser->token,
        ]);

        $user->save();

        return $user;
    }
}
