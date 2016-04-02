;(function() {

	Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf_token').getAttribute('value');
	Vue.http.options.emulateHTTP            = true;
	Vue.http.options.emulateJSON            = true;

	new Vue({

		el: '#gistr',

		data: {
			gists_data    : {},
			search        : '',
			loading       : true,
			currentPage   : 1,
			maxPages      : 1,
			userSettings  : {},
			startup       : true,
		},

		computed: {
			searchActive: function() {
				return this.search.trim().length > 0;
			},

			showPagination: function() {
				var displayPagination = true;

				if ( this.maxPages < 1 ) {
					displayPagination = false;
				}

				if ( this.searchActive ) {
					displayPagination = false;
				}

				if ( this.userSettings.favorites ) {
					displayPagination = false;
				}

				return displayPagination;
			}
		},

		ready: function() {
			this.fetchGists();
		},

		methods: {
			reset: function() {
				this.gists_data  = {};
				this.search      = '';
				this.loading     = true;
				this.currentPage = 1;
				this.maxPages    = 1;
			},

			fetchGists: function() {
				this.reset();

				this.$http.get( 'gists', function ( data ) {
					this.gists_data = data;
					this.loading    = false;

					this.paginateGists( 20 );
					this.getUserSettings();

					setTimeout(function() {
						jQuery('code[data-gist-id]').gist();
					}, 0);
				} );
			},

			updateGists: function() {
				this.$http.post( 'gists', { gists: this.gists_data }, function(data) {

				});
			},

			paginateGists: function( perPage ) {
				var gistIndex = 1;

				for ( var gist in this.gists_data ) {
					
					if ( gistIndex > perPage ) {
						this.maxPages++;
						gistIndex = 1;
					}

					this.gists_data[gist].page = this.maxPages;

					gistIndex++;
				}
			},

			setPage: function( page ) {
				if ( page < 1 ) {
					page = this.maxPages;
				}

				if ( page > this.maxPages ) {
					page = 1;
				}

				this.currentPage = page;
			},

			getUserSettings: function() {
				this.$http.get( 'user', {}, function(data) {
					this.userSettings = data;
					this.startup      = false;
				});
			},

			toggleSidebar: function() {
				this.userSettings.sidebar = ! this.userSettings.sidebar;
				this.$http.post( 'user', {
					sidebar: this.userSettings.sidebar
				}, function(data) {

				});
			},

			toggleFavorites: function() {
				this.userSettings.favorites = ! this.userSettings.favorites;
				this.$http.post( 'user', {
					favorites: this.userSettings.favorites
				}, function(data) {

				});
			},

			toggleCode: function(gist) {
				gist.expanded = ( gist.expanded == 1 ) ? 0 : 1;
			},

			copyGist: function(gist, username) {
				var gistURL = 'https://gist.github.com/' + username + '/' + gist.id;

				window.prompt( 'Gist URL:', gistURL );
			},

			favoriteGist: function(gist) {
				gist.favorited = ( gist.favorited == 1 ) ? 0 : 1;
				this.updateGists();
			},

			gistName: function(gist) {
				var gistName = gist.name;

				if ( this.searchActive ) {
					var searchQuery  = this.search.trim();
					var searchRegExp = new RegExp( '(' + searchQuery + ')', 'gi' );
					
					gistName = gistName.replace( searchRegExp, '<strong>$1</strong>' );
				}

				return gistName.substr( 0, gistName.lastIndexOf('.') ) || gistName;
			},

			gistShow: function(gist) {
				var showGist = true;
				var gistName = gist.name;

				if ( this.searchActive ) {
					var searchQuery = this.search.trim();
					var isMatching  = gistName.match( new RegExp( searchQuery, 'gi' ) );

					if ( ! isMatching ) {
						showGist = false;
					}
				}

				if ( this.userSettings.favorites && gist.favorited == 0 ) {
					showGist = false;
				}

				if ( ! this.searchActive && ! this.userSettings.favorites ) {
					if ( this.currentPage !== gist.page ) {
						showGist = false;
					}
				}

				return showGist;
			}
		}
	});

})();