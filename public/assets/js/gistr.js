;(function() {
	new Vue({

		el: '#gistr',

		data: {
			'gists_data' : {},
			'search'     : ''
		},

		ready: function() {
			this.gists_data = gists_data;
		},

		methods: {
			toggleCode: function(gist) {
				gist.expanded = ! gist.expanded;
			},

			gistName: function(gist) {
				var regex       = new RegExp( '(' + this.search + ')', 'gi' );
				var strongRegEx = new RegExp( '(<strong></strong>)', 'gi' );
				var highlighted = gist.name.replace( regex, '<strong>$1</strong>' );

				highlighted = highlighted.replace( strongRegEx, '' );

				return highlighted;
			}
		}

	});

})()