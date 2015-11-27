<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta id="csrf_token" value="{{ csrf_token() }}">

	<title>Gistr</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/vendor/font-awesome/css/font-awesome.min.css' ) }}" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/gistr.css' ) }}" type="text/css" media="all" />

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'assets/favicon.ico' ) }}" />

	<script type="text/javascript" src="{{ asset( 'assets/vendor/jquery/jquery-2.1.4.min.js' ) }}"></script>
	<script type="text/javascript" src="{{ asset( 'assets/vendor/gist-embed/gist-embed.min.js' ) }}"></script>
</head>
<body id="gistr">
	@if ( $user )
	<div class="header">
		<div class="shell">
			<div class="user-meta">
				<div class="user-image">
					<a href="https://gist.github.com/{{ $user->username }}" target="_blank">
						<img src="{{ $user->avatar }}" alt="" />						
					</a>
				</div>
				<div class="user-name">
					<h2>{{ $user->name }}</h2>
					<h3>{{ $user->username }}</h3>
					<h4><a href="auth/logout">Logout</a></h4>
				</div>
			</div>
		</div>
	</div>

	<div class="main">
		<div class="shell">
			<div v-show="loading" class="gists-loading">
				<span><em></em></span>
			</div>
			<div v-show="! loading" class="gists-wrapper">
				<div class="gist-search">
					<form action="" method="get">
						<input type="text" v-model="search" placeholder="Filter by name" />
					</form>
				</div>
				<div class="gist-buttons">
					<a href="#" :class="favorites ? 'active' : ''" @click.prevent="toggleFavorites"><span class="fa fa-star"></span> Toggle Favorites</a>
				</div>
				<div class="gist-list">
					<ul>
						<li v-for="gist in gists_data" v-show="gistShow( gist )">
							<h3 @click="toggleCode(gist)">
								@{{{ gistName(gist) }}}
								<a class="gist-favorite @{{ gist.favorited == 1 ? 'gist-favorited' : '' }}" href="#" @click.stop.prevent="favoriteGist(gist)">
									<span class="fa @{{ gist.favorited == 1 ? 'fa-star' : 'fa-star-o' }}"></span>
								</a>
							</h3>
							<div class="gist-content" v-show="gist.expanded == 1">
								 <code data-gist-id="@{{ gist.id }}"></code>
							</div>
						</li>
					</ul>
				</div>
				<div class="gist-pagination" v-if="showSearch">
					<a v-for="n in maxPages" :class="( n + 1 ) === currentPage ? 'active' : ''" @click.prevent="setPage(n + 1)" href="#">@{{ n + 1 }}</a>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset( 'assets/vendor/vue/vue.min.js' ) }}" type="text/javascript"></script>
	<script src="{{ asset( 'assets/vendor/vue/vue-resource.min.js' ) }}" type="text/javascript"></script>
	<script src="{{ asset( 'assets/gistr.js' ) }}" type="text/javascript"></script>
	@else
		<div class="shell">
			<div class="github-login">
				<img src="{{ asset( 'assets/iron-octo-cat.png' ) }}" alt="" title="It is an octo-cat in an iron man suit with the 2create logo on his forehead, yes" />
				<a href="auth/github"><span class="fa fa-github"></span>Login with GitHub</a>
			</div>
		</div>
	@endif
</body>
</html>