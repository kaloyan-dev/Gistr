;(function() {

	Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf_token').getAttribute('value');

	new Vue({

		el: '#gistr',

		data: {
			gists_data : {},
			search     : '',
			loading    : true,
			favorites  : false,
		},

		computed: {

		},

		ready: function() {
			this.gists_data = this.fetchGists();
		},

		methods: {
			fetchGists: function( fetch ) {
				this.loading = true;

				this.$http.get( '/gists', function ( data ) {
					this.gists_data = data;
					this.loading    = false;

					setTimeout(function() {
						jQuery('code[data-gist-id]').gist();
					}, 0);
				} );
			},

			updateGists: function( fetch ) {
				this.$http.post( '/gists', { gists: this.gists_data }, function(data) {

				}, {
					emulateJSON: true
				} );
			},

			toggleFavorites: function() {
				this.favorites = ! this.favorites;
			},

			toggleCode: function(gist) {
				gist.expanded = ( gist.expanded == 1 ) ? 0 : 1;
			},

			favoriteGist: function(gist) {
				gist.favorited = ( gist.favorited == 1 ) ? 0 : 1;
				this.updateGists();
			},

			gistName: function(gist) {
				var gistName = gist.name;

				if ( this.search !== '' && this.search !== ' ' ) {
					var searchQuery  = this.search.trim();
					var searchRegExp = new RegExp( '(' + searchQuery + ')', 'gi' );
					
					gistName = gistName.replace( searchRegExp, '<strong>$1</strong>' );
				}

				return gistName;
			},

			gistHidden: function(gist) {
				var gistClass = '';
				var gistName  = gist.name;

				if ( this.search !== '' && this.search !== ' ' ) {
					var searchQuery = this.search.trim();
					var isMatching  = gistName.match( new RegExp( searchQuery, 'gi' ) );

					if ( ! isMatching ) {
						gistClass = 'gist-hidden';
					}
				}

				if ( this.favorites && gist.favorited == 0 ) {
					gistClass = 'gist-hidden';
				}

				return gistClass;
			}
		}
	});

})();