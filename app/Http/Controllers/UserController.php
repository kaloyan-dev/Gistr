<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
	public function getSettings() {
		if ( ! Auth::check() ) {
			return [];
		}

		$user     = Auth::user();
		$settings = json_decode( $user->settings );

		if ( ! $settings ) {
			$settings            = new \stdClass();
			$settings->sidebar   = true;
			$settings->favorites = false;
		}

		$settings->sidebar   = ( $settings->sidebar === 'true' ) ? true : false;
		$settings->favorites = ( $settings->favorites === 'true' ) ? true : false;

		echo json_encode( $settings );
	}

	public function updateSettings( Request $request ) {

		if ( ! Auth::check() ) {
			return [];
		}

		$user     = Auth::user();
		$settings = json_decode( $user->settings );

		if ( ! $settings ) {
			$settings            = new \stdClass();
			$settings->sidebar   = true;
			$settings->favorites = false;
		}

		if ( $request->input('sidebar') ) {
			$settings->sidebar = $request->input('sidebar');
			$user->settings    = json_encode( $settings );
		}

		if ( $request->input('favorites') ) {
			$settings->favorites = $request->input('favorites');
			$user->settings      = json_encode( $settings );
		}

		$user->save();
	}
}
