( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'HjemmesideController', [ '$scope', '$http', function( $scope, $http ) {

			var controller = this;

			controller.sending = false;
			controller.message = '';

			controller.form = {
				url: '',
				email: ''
			}

	        $scope.send = function() {

	        	controller.sending = true;

	        	$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/sendHjemmeside',
				    data: controller.form,
				    success: function( msg ) {
				    	console.log( msg );
				    	controller.sending = false;
				    	controller.message = 'Besked sendt';
				    	controller.form = {
							url: '',
							email: ''
						}
						controller.formdom.$setPristine();
					    controller.formdom.$setUntouched();
						$scope.$apply();
				    },
				    complete: function( r ) {

				    },
				    error: function( error ) {
				    	console.log( "Error in sending Mail " + error );
				    }
				});

	        }

		} ] );

} )( jQuery );
