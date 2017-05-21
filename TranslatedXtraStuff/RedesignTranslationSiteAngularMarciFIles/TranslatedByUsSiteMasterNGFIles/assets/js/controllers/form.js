( function( $ ) {

	angular.module( 'translatedbyus' )

		.controller( 'FormController', [ '$scope', '$http', 'order', 'Analytics', '$cookies', function( $scope, $http, order, Analytics, $cookies ) {

			var controller = this;
			var uploadTimer;
			var translate_request;
			var current_api_file = 0;

			$scope.order = order.data; 
			$scope.order_files = order.files; 

			controller.files_word_count = 0;

			controller.upload_alert = '';

			$http.get( '/app/langs.php' ).then( function( response ) {
	        	controller.langs = response.data;
	        } );

	        $scope.send_quality_track = function( id ) {
				Analytics.trackPage( '/type-' + id );
			};

	        $scope.show_popup = function() {

				var $popup = $('.braintree-popup');

				if ( 'machine' !== $scope.order.type_raw ) {
					$popup.modal();
				} else {
					$scope.send_order();
				}

			};

	        $scope.send_preorder = function() {

	        	clearTimeout( uploadTimer );
	        	controller.upload_alert = '';

	        	controller.sending = true;

				$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/send_preorder',
				    data: {
				    	'zip': $scope.order.has_files,
				    	'order': $scope.order
				    },
				    success: function( response_raw ) {

				    	console.log( response_raw );

				    	$('.preorder-confirmation-popup').modal();

				    },
				    error: function( error ) {
				    	console.log( error );
				    }
				});

			};

	        $scope.braintree_options = {
				onPaymentMethodReceived: function( payload ) {

					var request = $http( {
		                method: 'post',
		                url: '/app/braintree-transaction.php',
		                data: {
		                    total: $scope.order.total + $scope.order.vat,
							nonce: payload.nonce
		                },
		                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		            } );

		            request.success( function( response ) {

		                if ( response ) {
		                	$scope.send_order( true );
		                }

		            } );

				}
            };

	        $scope.send_order = function( paid ) {

	        	paid = paid || false;

	        	controller.sending = true;

	        	if ( 'machine' == $scope.order.type_raw ) {
					var packageID = 0;
				} else if ( 'basis' == $scope.order.type_raw ) {
					var packageID = 1;
				} else {
					var packageID = 2;
				}

				var translate_request = [ {
					name : 'TranslationPackageId',
					value: packageID
				}, {
					name : 'SourceLang',
					value: $scope.order.fromlanguage
				}, {
					name : 'Name',
					value: $scope.order.project_name
				}, {
					name : 'ClientName',
					value: $scope.order.name
				}, {
					name : 'ClientEmail',
					value: $scope.order.email
				}, {
					name : 'ClientCompany',
					value: $scope.order.company
				}, {
					name : 'Notes',
					value: $scope.order.notes
				} ];

				for( var lang in $scope.order.tolanguage ) {

					translate_request = translate_request.concat( [ { 
						name: 'TargetLanguages',
						value: $scope.order.tolanguage[ lang ]
					} ] );

				}

				if ( paid ) {

					translate_request = translate_request.concat( [ { 
						name: 'PaymentStatus',
						value: 1
					} ] );
					
				}

				if ( $scope.order.source == 'file' ) {

					Array.from( $scope.order_files ).forEach( function( file ) {

						controller.read_file( file ).done( function( result ) {
							translate_request = translate_request.concat( result );
						} );

					} );

					current_api_file = 0;

				} else {

					translate_request = translate_request.concat( [ { 
						name: 'Text',
						value: $scope.order.project_text
					} ] );

				}

				if ( $('.braintree-popup').hasClass('in') ) {

					$('.braintree-popup').modal( 'hide' ).on('hidden.bs.modal', function () {
						controller.api_request( translate_request );
					});

				} else {

					controller.api_request( translate_request );

				}

			};

			controller.api_request = function( request ) {

				console.log( request );

				$.ajax( {
					url: 'https://tms.translatedbyus.com/projectt/api/orderadd', 
					type: 'POST',
					data: request,
					success: function( res ) {

						console.log( res );

						window.google_trackConversion( {
							google_conversion_id: 943361506, 
							google_conversion_label: '8qtUCKCd1mQQ4pvqwQM',
							google_conversion_language: "en",
							google_conversion_format: "3",
							google_conversion_color: "ffffff",
							google_conversion_value: 50.00,
							google_conversion_currency: "DKK",
							google_remarketing_only: false
						} );

						controller.sending = false;
						$cookies.putObject( 'tbu_order_details', $scope.order );

						$scope.send_order_confirmation().done( function() {
							window.location.href = 'ordrebekraeftelse';							
						} );

					},
					error: function( res, status, error ) {
						console.log( res );
					}
				} );

			}

			controller.read_file = function( file ) {

				var reader = new FileReader();
				var result = '';
				var dfd = $.Deferred();

				reader.addEventListener("load", function(e) {

					result = [ {
						name: "FileToUpload[" + current_api_file + "][FileName]",
						value: file.name
					}, { 
						name: "FileToUpload[" + current_api_file + "][FileContent]",
						value: reader.result.slice( reader.result.indexOf( ',' ) + 1 )
					} ];

					current_api_file++;

					dfd.resolve( result );

				}, false);

				reader.readAsDataURL( file );

				return dfd.promise();

			}

			$scope.count_words = function( resetcount ) {

				resetcount = resetcount || false;

				var textarea_val = $scope.order.project_text;

				if ( textarea_val ) {

					$scope.order.count = textarea_val.match(/\S+/g).length;

				} else if ( $scope.order.files ) {

					controller.files_word_count = 0;
	
					$.each( $scope.order.files, function( key, value ) {
						controller.files_word_count += value;
					});

					$scope.order.count = controller.files_word_count;

				} else {

					$scope.order.count = 0;

				}

				$scope.calc_total();

			}

			$scope.calc_total = function() {

				if ( $scope.order.tolanguage ) {
					$scope.order.total = Math.round( $scope.order.count * $scope.order.word_price * $scope.order.tolanguage.length * 1000 ) / 1000;
					$scope.order.vat = $scope.order.total * 0.25;
				} else {
					$scope.order.total = 0;
					$scope.order.vat = 0;
				}

			}

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

			controller.dropzone_successmultiple = function( files, response ) {
				
				var res = JSON.parse( response );

				$scope.order.has_files = res;

				uploadTimer = setTimeout( function() {
					controller.upload_alert = 'Tæller ord...';
					$scope.$apply();
				}, 2000 );

				uploadTimer = setTimeout( function() {
					controller.upload_alert = 'Tæller stadig ord - vent venligst...';
					$scope.$apply();
				}, 5000 );

		        $.ajax({
				    type: "POST",
				    url : '/api/web/index.php/file_word_count',
				    data: res,
				    success: function( response_raw ) {

				    	resp = JSON.parse( response_raw );
				    	
				    	clearTimeout( uploadTimer );
						controller.upload_alert = '';
						$scope.$apply();

						$.each( resp.files, function( rkey, rvalue ) {
							$scope.order.files[ rvalue.fileName ] = rvalue.wordsCount;
						} );

						$scope.count_words();

				    },
				    error: function( error ) {
				    	console.log( error );
				    }
				});

			}

			controller.dropzone_removedfile = function( file ) {

			    delete $scope.order.files[ file['name'] ];

			    if ( $.isEmptyObject( $scope.order.files ) ) {
			    	$scope.order.source = true;
			    }

		  		$scope.count_words( true );
		  		$scope.$apply();

			}

			controller.dropzone_sending = function( file, xhr, formData ) {
				$scope.order.source = 'file';
				$scope.$apply();
			    formData.append( 'from', 'da' );
			    formData.append( 'to', 'en' );
			}

			$scope.send_mail = function( form, upload_link ) {

				$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/sendMail',
				    data: {
				    	email: $scope.order.email,
				        content: $scope.order.project_text
				    },
				    success: function( msg ) {
				    	console.log( msg );
				    },
				    complete: function( r ) {
				    	//console.log(r);
				    },
				    error: function( error ) {
				    	console.log( "Error in sending Mail " + error );
				    }
				});

			}

			$scope.send_order_confirmation = function() {

				var dfd = $.Deferred();

				$.ajax({
				    type: "POST",
				    url : '/api/web/index.php/sendOrderConfirmation',
				    data: $scope.order,
				    success: function( msg ) {
				    	console.log( msg );
				    },
				    complete: function( r ) {
				    	dfd.resolve();
				    },
				    error: function( error ) {
				    	console.log( "Error in sending Mail " + error );
				    }
				});

				return dfd.promise();

			}

		} ] );

} )( jQuery );
