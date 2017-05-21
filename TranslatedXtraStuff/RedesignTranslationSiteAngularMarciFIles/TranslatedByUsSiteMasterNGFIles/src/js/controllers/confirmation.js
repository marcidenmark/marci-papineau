( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'ConfirmationController', [ '$scope', '$http', 'order', '$cookies', function( $scope, $http, order, $cookies ) {

			var controller = this;

			$scope.order = $cookies.getObject( 'tbu_order_details' );
			$cookies.remove( 'tbu_order_details' );

			if ( typeof $scope.order === 'undefined' ) {
				$scope.order = order.data;
			}

			$http.get( '/app/langs.php' ).then( function( response ) {

	        	controller.langs = response.data;

	        	for( var i = 0, len = controller.langs.length; i < len; i++ ) {
			        if ( controller.langs[ i ].id === $scope.order.fromlanguage ) {
			            controller.fromlang = controller.langs[ i ].name;
			        }
			    }

	        } );

	        $scope.print_order = function() {

	        	$('.section.order-confirmation').printElement( {
	        		leaveOpen: true
	        	} );

	        }

		} ] );

} )( jQuery );
