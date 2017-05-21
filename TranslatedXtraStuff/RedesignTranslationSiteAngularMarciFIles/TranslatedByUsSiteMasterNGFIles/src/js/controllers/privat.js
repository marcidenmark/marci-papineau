( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'PrivatController', [ '$scope', '$http', function( $scope, $http ) {

			var controller = this;

			controller.sending = false;
			controller.message = '';

			controller.form = {
				name: '',
				email: '',
				fromlanguage: '',
				tolanguage: '',
				instructions: '',
				files: false
			}

			$http.get( '/app/langs.php' ).then( function( response ) {
	        	controller.langs = response.data;
	        } );

	        $scope.send = function() {

	        	controller.sending = true;

	        	$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/sendPrivat',
				    data: controller.form,
				    success: function( msg ) {
				    	console.log( msg );
				    	controller.sending = false;
				    	controller.message = 'Besked sendt';
				    	controller.form = {
							url: '',
							email: '',
							fromlanguage: '',
							tolanguage: '',
							instructions: '',
							files: false
						}
						controller.dropzone.removeAllFiles(true);
						controller.formdom.$setPristine();
					    controller.formdom.$setUntouched();
						$scope.$apply();
				    },
				    complete: function( r ) {

				    },
				    error: function( error ) {
				    	console.log( error );
				    }
				});

	        }

	        controller.dropzoneConfig = {
				url: '/api/web/index.php/files_save',
			    previewsContainer: ".dropzone-previews",
			    uploadMultiple: true,
			    parallelUploads: 100,
			    maxFiles: 100,
			    addRemoveLinks: false,
			    dictCancelUpload: 'Annulér upload',
			    dictRemoveFile: 'Fjern fil',
			    dictMaxFilesExceeded: 'Du kan kun uploade én fil',
			}

			controller.dropzone_successmultiple = function( files, response ) {

				controller.form.files = JSON.parse( response );
				$scope.$apply();

			}

			controller.dropzone_sending = function( file, xhr, formData ) {
			    formData.append( 'from', 'da' );
			    formData.append( 'to', 'en' );
			}

		} ] );

} )( jQuery );
