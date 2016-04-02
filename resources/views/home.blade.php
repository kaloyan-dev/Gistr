<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=yes, minimum-scale=1.0, maximum-scale=2.0" />
	<meta id="csrf_token" value="{{ csrf_token() }}">

	<title>Gistr</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/vendor/font-awesome/css/font-awesome.min.css' ) }}" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/gistr.css' ) }}" type="text/css" media="all" />

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'assets/favicon.ico' ) }}" />

	<script type="text/javascript" src="{{ asset( 'assets/vendor/jquery/jquery-2.1.4.min.js' ) }}"></script>
	<script type="text/javascript" src="{{ asset( 'assets/vendor/gist-embed/gist-embed.min.js' ) }}"></script>
</head>
<body id="gistr" :class="{ 'no-stripes' : search || userSettings.favorites }" v-cloak>
	@if ( $user )
		<div class="main sidebar-hidden" :class="{ 'sidebar-hidden' : ! userSettings.sidebar }">
			<div class="sidebar" v-if="! startup">
				<a class="sidebar-toggle" href="#" @click.prevent="toggleSidebar">
					<span class="fa" :class="{ 'fa-chevron-left' : userSettings.sidebar, 'fa-chevron-right' : ! userSettings.sidebar }"></span>
				</a>
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

			<div class="content">
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
						<a href="https://gist.github.com/" target="_blank"><span class="fa fa-plus"></span> New Gist</a>
						<a href="#" @click.prevent="fetchGists"><span class="fa fa-refresh"></span> Reload Gists</a>
						<a href="#" :class="userSettings.favorites ? 'active' : ''" @click.prevent="toggleFavorites"><span class="fa fa-star"></span> Toggle Favorites</a>
					</div>
					<div class="gist-list">
						<ul>
							<li v-for="gist in gists_data" v-show="gistShow( gist )" @click.prevent="toggleCode(gist)">
								<h3>@{{{ gistName(gist) }}}</h3>
								<div class="gist-tools">
									<a @click.stop="" href="https://gist.github.com/{{ $user->username }}/@{{ gist.id }}" target="_blank" title="View Gist">
										<span class="fa fa-eye"></span>
									</a>
									<a @click.stop="" href="https://gist.github.com/{{ $user->username }}/@{{ gist.id }}/edit" target="_blank" title="Edit Gist">
										<span class="fa fa-pencil"></span>
									</a>
									<a href="#" @click.prevent.stop="copyGist(gist, '{{ $user->username }}' )" title="Copy Gist URL">
										<span class="fa fa-clipboard"></span>
									</a>
									<a class="gist-favorite @{{ gist.favorited == 1 ? 'gist-favorited' : '' }}" href="#" @click.stop.prevent="favoriteGist(gist)" title="Favorite Gist">
										<span class="fa @{{ gist.favorited == 1 ? 'fa-star' : 'fa-star-o' }}"></span>
									</a>
								</div>
								<div class="gist-content" v-show="gist.expanded == 1" @click.stop>
									 <code data-gist-id="@{{ gist.id }}"></code>
								</div>
							</li>
						</ul>
					</div>
					<div class="gist-pagination" v-if="showPagination">
						<div class="gists-per-page">
							<select v-model="userSettings.per_page" @change="setPerPage">
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
							</select>
							<label>per page</label>
						</div>

						<a @click.prevent="setPage(currentPage - 1)" href="#"><em class="fa fa-angle-left"></em></a>
						<a v-for="n in maxPages" :class="( n + 1 ) === currentPage ? 'active' : ''" @click.prevent="setPage(n + 1)" href="#">@{{ n + 1 }}</a>
						<a @click.prevent="setPage(currentPage + 1)" href="#"><em class="fa fa-angle-right"></em></a>
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
				<img src="{{ asset( 'assets/iron-octo-cat.png' ) }}" alt="" />
				<a href="auth/github"><span class="fa fa-github"></span>Login with GitHub</a>
			</div>
		</div>
	@endif
</body>
</html>