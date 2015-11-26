<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Gistr</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/css/gistr.css' ) }}" type="text/css" media="all" />
	<link rel="stylesheet" href="{{ asset( 'assets/css/gist.css' ) }}" type="text/css" media="all" />

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'assets/favicon.ico' ) }}" />
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
			<div class="gists-search">
				<form action="" method="get">
					<input type="text" v-model="search" placeholder="Filter By Name" />
				</form>
			</div>
			<div class="gists-list">
				<ul>
					<li v-for="gist in gists_data | filterBy search">
						<h3 @click="toggleCode(gist)">@{{{ gistName(gist) }}}</h3>
						<div class="gist-content" v-show="gist.expanded == 1">
							@{{{ gist.code }}}
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var gists_data = {!! $gists_data !!};
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.10/vue.min.js" type="text/javascript"></script>
    <script src="{{ asset( 'assets/js/gistr.js' ) }}" type="text/javascript"></script>
</body>
</html>