<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Gistr</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/gistr.css' ) }}" type="text/css" media="all" />

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'assets/favicon.ico' ) }}" />

	<script type="text/javascript" src="{{ asset( 'assets/vendor/jquery/jquery-2.1.4.min.js' ) }}"></script>
	<script type="text/javascript" src="{{ asset( 'assets/vendor/gist-embed/gist-embed.min.js' ) }}"></script>
</head>
<body id="gistr">
	<div class="header">
		<div class="shell">
			<div class="user-meta">
				<div class="user-image">
					<img src="{{ $user->avatar }}" alt="" />
				</div>
				<div class="user-name">
					<h2>{{ $user->name }}</h2>
					<h3>{{ $user->username }}</h3>
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
				<div class="gists-search">
					<form action="" method="get">
						<input type="text" v-model="search" placeholder="Filter By Name" />
					</form>
				</div>
				<div class="gists-list">
					<ul>
						<li v-for="gist in gists_data" :class="gistHidden( gist )">
							<h3 @click="toggleCode(gist)">@{{{ gistName(gist) }}}</h3>
							<div class="gist-content" v-show="gist.expanded == 1">
								 <code data-gist-id="@{{ gist.id }}"></code>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset( 'assets/vendor/vue/vue.min.js' ) }}" type="text/javascript"></script>
	<script src="{{ asset( 'assets/vendor/vue/vue-resource.min.js' ) }}" type="text/javascript"></script>
	<script src="{{ asset( 'assets/gistr.js' ) }}" type="text/javascript"></script>
</body>
</html>