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

		.filter('inArray', function($filter){
		    return function(list, arrayFilter, element){
		        if(arrayFilter){
		            return $filter("filter")(list, function(listItem){
		                return arrayFilter.indexOf(listItem[element]) != -1;
		            });
		        }
		    };
		})

		.run( function( Analytics ) {} )

		.service( 'order', function() {

			var order = this;

			order.data = {
				type: 'Business',
				type_raw: 'business',
				word_price: 0.99,
				count: 0,
				estimation: '1-2',
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
				no_vat: false,
				paymenttype: 'Betaling via faktura'
			};

			order.order_files = [];

		} )

		.controller( 'MainController', [ '$scope', '$location', '$anchorScroll', '$http', 'order', function( $scope, $location, $anchorScroll, $http, order ) {

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
					files: {},
					paymenttype: 'Betaling via faktura'
				};

	        }

	        $scope.scroll_to = function( fragment ) {
	        	$location.hash( fragment );
				$anchorScroll();
	        } 

	        $http.get( '/app/prices.php' ).then( function( response ) {
	        	$scope.prices = response.data;
	        } );

		} ] );

	jQuery(function($) {
	    $('ul.nav li.dropdown').hover(function() {
	        $(this).find('.dropdown-menu').stop(true, true).fadeIn(200);
	    }, function() {
	        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(200);
	    });
	});

	$(document).ready(function() {
	    $("#testi-prev").click(function() {
	        $('#testimonials').carousel('prev');
	    });
	    $("#testi-next").click(function() {
	        $('#testimonials').carousel('next');
	    });
	});


} )( jQuery );
