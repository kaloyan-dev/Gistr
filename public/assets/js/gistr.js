;(function() {

	new Vue({

		el: '#gistr',

		data: {
			gists_data : {},
			search     : '',
			loading    : true
		},

		ready: function() {
			this.gists_data = this.fetchGists();
		},

		methods: {
			fetchGists: function() {
				this.$http.get( '/fetch-gists', function ( data ) {
					this.gists_data = data;
					this.loading    = false;

					setTimeout(function() {
						jQuery('code[data-gist-id]').gist();
					}, 0);
				});
			},

			toggleCode: function(gist) {
				gist.expanded = ! gist.expanded;
			},

			gistName: function(gist) {
				var gistName = gist.name;

				if ( this.search !== '' ) {
					var searchRegExp = new RegExp( '(' + this.search + ')', 'gi' );
					var strongRegExp = new RegExp( '<strong></strong>', 'gi' );
					
					var highlighted  = gist.name.replace( searchRegExp, '<strong>$1</strong>' );

					gistName = highlighted.replace( strongRegExp, '' );					
				}

				return gistName;
			},

			gistHidden: function(gist) {
				var gistClass = '';
				var gistName  = gist.name;

				if ( this.search !== '' ) {
					var isMatching = gistName.match( new RegExp( this.search, 'gi' ) );

					if ( ! isMatching ) {
						gistClass = 'gist-hidden';
					}
				}

				return gistClass;
			}
		}
	});

})();