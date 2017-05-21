( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'TipsOgTriksController', [ '$scope', '$http', function( $scope, $http ) {

			var controller = this;
			
			controller.form = {
				files: false,
				text: '',
				email: ''
			};
			controller.sending = false;
			controller.message = '';

			controller.dropzoneConfig = {
				url: '/api/web/index.php/files_save',
			    previewsContainer: ".dropzone-previews",
			    uploadMultiple: true,
			    parallelUploads: 100,
			    maxFiles: 100,
			    addRemoveLinks: true,
			    dictCancelUpload: 'Annulér upload',
			    dictRemoveFile: 'Fjern fil',
			    dictMaxFilesExceeded: 'Du kan kun uploade én fil',
			}

			controller.dropzone_successmultiple = function( files, res ) {

				controller.form.files = res;
				$scope.$apply();

			}

			controller.dropzone_sending = function( file, xhr ) {
				controller.form.files = true;
			}

	        $scope.send = function() {

	        	controller.sending = true;

	        	$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/sendTipsOgTriks',
				    data: controller.form,
				    success: function( msg ) {
				    	console.log( msg );
				    	controller.message = 'Filerne er nu sendt til os. Vi kigger på det og vender personligt tilbage til jer.';
				    	controller.form = {
							files: false,
							text: '',
							email: ''
						};
						controller.dropzone.removeAllFiles();
						controller.formdom.$setPristine();
					    controller.formdom.$setUntouched();
					    controller.sending = false;
						$scope.$apply();
				    },
				    complete: function( r ) {

				    },
				    error: function( error ) {
				    	console.log( error );
				    }
				});

	        }

		} ] );

} )( jQuery );
