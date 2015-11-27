<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use GrahamCampbell\GitHub\Facades\GitHub;

use Github\Client;

class PagesController extends Controller
{
	
	public function index() {

		if ( ! Auth::check() ) {
			return redirect()->route( 'github-login' );
		}

		$user = Auth::user();

		return view( 'home' )->with( array(
			'user' => $user,
		) );

	}

	public function fetch( $user ) {

		$gists_data = $user->gists;

		if ( ! $gists_data ) {
			$githubClient = new Client();
			$githubClient->authenticate( $user->auth_token, '', $githubClient::AUTH_HTTP_TOKEN );

			$gists = $githubClient->api('gists')->all();

			foreach ( $gists as $gist ) {
				$gists_data[] = array(
					'name'      => array_values( $gist['files'] )[0]['filename'],
					'id'        => $gist['id'],
					'expanded'  => 0,
					'favorited' => 0,
					'folders'   => [],
				);
			}

			$gists_data  = json_encode( $gists_data );
			$user->gists = $gists_data;
			$user->save();
		}

		return $gists_data;
	}

	public function fetchGists() {

		if ( ! Auth::check() ) {
			return [];
		}

		$user = Auth::user();

		$gists_data = $this->fetch( $user );

		echo $gists_data;

	}

	public function updateGists( Request $request ) {

		if ( ! Auth::check() ) {
			return [];
		}

		$user  = Auth::user();
		$gists = $request->input('gists');

		foreach ( $gists as &$gist ) {
			$gist['expanded'] = 0;
		}

		$gists_data  = json_encode( $gists );		
		$user->gists = $gists_data;
		$user->save();
	}

}
