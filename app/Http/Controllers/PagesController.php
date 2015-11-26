<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use GrahamCampbell\GitHub\Facades\GitHub;

use Github\Client;
use File;

class PagesController extends Controller
{
	
	public function index() {

		if ( $user = Auth::user() ) {

			$gists_data = $user->gists;

			if ( ! $gists_data ) {
				$githubClient = new Client();
				$githubClient->authenticate( $user->auth_token, '', $githubClient::AUTH_HTTP_TOKEN );

				$gists = $githubClient->api('gists')->all();

				foreach ( $gists as $gist ) {
					$script_url = 'http://gist.github.com/' . $user->username . '/' . $gist['id'] . '.js';
					$search     = array( '\n', '\\', 'document.write(', ')' );
					$contents   = file_get_contents( $script_url );
					$stripped   = str_replace( $search, '', $contents );

					$gists_data[] = array(
						'name'      => array_values( $gist['files'] )[0]['filename'],
						'id'        => $gist['id'],
						'content'   => '',
						'expanded'  => 0,
						'favorited' => 0,
						'folders'   => [],
						'code'      => $stripped,
					);
				}

				$gists_data = json_encode( $gists_data );
				$user->gists = $gists_data;
				$user->save();
			}

			return view( 'home' )->with( array(
				'gists_data' => $gists_data,
				'user'       => $user,
			) );
		}

		return redirect()->route( 'github-login' );
	}
}
