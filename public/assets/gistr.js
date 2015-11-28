;(function() {

	Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf_token').getAttribute('value');

	new Vue({

		el: '#gistr',

		data: {
			gists_data  : {},
			search      : '',
			loading     : true,
			favorites   : false,
			currentPage : 1,
			maxPages    : 1
		},

		computed: {
			showPagination: function() {
				var displayPagination = true;

				if ( this.maxPages < 1 ) {
					displayPagination = false;
				}

				if ( this.search !== '' && this.search !== ' ' ) {
					displayPagination = false;
				}

				if ( this.favorites ) {
					displayPagination = false;
				}

				return displayPagination;
			}
		},

		ready: function() {
			this.fetchGists();
		},

		methods: {
			fetchGists: function() {
				this.loading = true;

				this.$http.get( 'gists', function ( data ) {

					var gistIndex = 1;

					for ( var gist in data ) {
						
						if ( gistIndex > 10 ) {
							this.maxPages++;
							gistIndex = 1;
						}

						data[gist].page = this.maxPages;

						gistIndex++;
					}

					this.gists_data = data;
					this.loading    = false;

					setTimeout(function() {
						jQuery('code[data-gist-id]').gist();
					}, 0);
				} );
			},

			updateGists: function() {
				this.$http.post( 'gists', { gists: this.gists_data }, function(data) {

				}, {
					emulateJSON: true
				} );
			},

			setPage: function( page ) {
				this.currentPage = page;
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

			gistShow: function(gist) {
				var showGist = true;
				var gistName = gist.name;

				if ( this.search !== '' && this.search !== ' ' ) {
					var searchQuery = this.search.trim();
					var isMatching  = gistName.match( new RegExp( searchQuery, 'gi' ) );

					if ( ! isMatching ) {
						showGist = false;
					}
				}

				if ( this.favorites && gist.favorited == 0 ) {
					showGist = false;
				}

				if ( ( this.search === '' || this.search === ' ' ) && ! this.favorites ) {
					if ( this.currentPage !== gist.page ) {
						showGist = false;
					}
				}

				return showGist;
			}
		}
	});

})();