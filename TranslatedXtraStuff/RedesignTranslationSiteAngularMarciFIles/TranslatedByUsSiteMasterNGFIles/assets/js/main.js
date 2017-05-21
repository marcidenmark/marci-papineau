( function( $ ) {

	angular.module( 'translatedbyus', [ 'ngRoute', 'localytics.directives', 'ngDropzone', 'angular-google-analytics', 'braintree-angular', 'metatags', 'ngCookies' ] )

		.constant( 'clientTokenPath', '/app/braintree-token.php' )

		.config( [ '$provide', 'AnalyticsProvider', function( $provide, AnalyticsProvider ) {

		    $provide.decorator( '$locale', [ '$delegate', function( $delegate ) {
		        $delegate.NUMBER_FORMATS.DECIMAL_SEP = ',';
		        $delegate.NUMBER_FORMATS.GROUP_SEP = '.';
		        return $delegate;
		    } ] );

		    AnalyticsProvider.setAccount('UA-72652134-1');
		    
		} ] )

		.run( function( Analytics ) {} )

		/*.run(function(MetaTags, $rootScope){

			var title = $('head title').text();
			var description = $('head meta[name="description"]').attr( 'content' );

		    MetaTags.initialize();
		    
		    $rootScope.metatags = {
				title: title,
				description: description
			};

		})

		.config( function( $routeProvider, $locationProvider, MetaTagsProvider ) {

			$locationProvider.html5Mode( {
				enabled:     true,
				requireBase: false
			} );

			$routeProvider
				.when( '/', {
					templateUrl: 'views/index.html',
				} )
				.when( '/bestil', {
					templateUrl: 'views/bestil.html',
				} )
				.when( '/hjemmeside', {
					templateUrl: 'views/hjemmeside.html',
				} )
				.when( '/om-os', {
					templateUrl: 'views/om-os.html',
				} )
				.when( '/ordrebekraeftelse', {
					templateUrl: 'views/ordrebekraeftelse.html',
				} )
				.when( '/privatlivspolitik', {
					templateUrl: 'views/privatlivspolitik.html',
				} )
				.when( '/oversaettelsesdata', {
					templateUrl: 'views/oversaettelsesdata.html',
				} )
				.when( '/validering', {
					templateUrl: 'views/validering.html',
				} )
				.otherwise( {
					templateUrl: 'views/404.html',
				} );

			angular.injector(['ng']).get('$http').get( '/app/metatags.php' ).then( function( response ) {
				
				$.each( response.data, function( url, tag ) {
				    
					MetaTagsProvider
						.when( url, tag );

				});

	        } );

			MetaTagsProvider
				.otherwise( {
					title: 'Translated By Us - 404'
				} );

		} )*/

		.service( 'order', function() {

			var order = this;

			order.data = {
				type: 'Business',
				type_raw: 'business',
				word_price: 0.99,
				count: 0,
				total: 0,
				vat: 0,
				project_name: '',
				project_text: '',
				name: '',
				email: '',
				company: '',
				fromlanguage: '',
				tolanguage: '',
				notes: '',
				source: true,
				files: {},
				has_files: false,
			};

			order.order_files = [];

		} )

		.controller( 'MainController', [ '$scope', '$location', '$http', 'order', function( $scope, $location, $http, order ) {

			var controller = this;

			$scope.changeView = function( view ){
				window.location.href = view;
	        }

	        $scope.reset_order = function() {

	        	order.data = {
					type: 'Business',
					type_raw: 'business',
					word_price: 0.99,
					count: 0,
					total: 0,
					project_name: '',
					project_text: '',
					name: '',
					email: '',
					company: '',
					fromlanguage: '',
					tolanguage: '',
					notes: '',
					source: true,
					files: {}
				};

	        }

	        $http.get( '/app/prices.php' ).then( function( response ) {
	        	$scope.prices = response.data;
	        } );

		} ] );

} )( jQuery );