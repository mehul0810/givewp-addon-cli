const wpTextdomain = require( 'wp-textdomain' );

wpTextdomain( process.argv[ 2 ], {
	domain: '{{name}}',
	fix: true,
} );
