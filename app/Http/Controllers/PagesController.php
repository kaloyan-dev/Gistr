<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class PagesController extends Controller
{
	
	public function index() {

		if ( $user = Auth::user() ) {
			return view( 'home' );
		}

		return redirect()->route( 'github-login' );
	}
}
