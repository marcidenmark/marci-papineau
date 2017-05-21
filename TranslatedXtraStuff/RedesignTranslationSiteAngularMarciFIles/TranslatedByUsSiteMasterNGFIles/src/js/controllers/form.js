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
			controller.counting = false;

			controller.upload_alert = '';

			$http.get( '/app/langs.php' ).then( function( response ) {
				controller.langs = response.data;
				controller.to_langs = response.data;
			} );

			$scope.disable_selected_lang = function(){
				var language_to_remove = false;
				var array = [];
				angular.forEach(controller.langs, function(value, key) {
					if( value.id !== $scope.order.fromlanguage ){
						array.push(value);
					}
				});
				controller.to_langs = array;
			};

			$scope.send_quality_track = function( id ) {
				Analytics.trackPage( '/type-' + id );
			};

			$scope.show_popup = function() {

				var $popup = $('.braintree-popup');

				if ( 'machine' !== $scope.order.type_raw ) {

					for( var i = 0, len = controller.langs.length; i < len; i++ ) {
						if ( controller.langs[ i ].id === $scope.order.fromlanguage ) {
							controller.fromlang = controller.langs[ i ].name;
						}
					}

					$popup.modal();

				} else {
					$scope.send_order();
				}

			};

			$scope.send_preorder = function() {

				clearTimeout( uploadTimer );
				controller.upload_alert = '';

				controller.sending = true;

				// we don't need this anymore and it's to big
				$scope.order.files = {};
				$scope.order.files_raw = {};

				$.ajax({
						type: "POST",
						url : '/api/web/index.php/send_preorder',
						data: {
							'zip': $scope.order.has_files,
							'order': $scope.order
						},
						success: function( response_raw ) {

							$('.preorder-confirmation-popup').modal();

						},
						error: function( error ) {
							console.log( error.responseText );
						}
				});

			};

			/**
			 * Variable to store information if user send quote
			 *	@var bool
			 */
			$scope.quote_request = false;

			/**
			 * Function to send quote instead of new order
			 *	Client wants to implement possibility to sending quote instead of standard order. 
			 *	The only difference between send_preorder is that quote_request is set to true.
			 *	It is used in send_order function to disable sending order confirmation to user.
			 *	
			 *	@date 02.03.2017
			 *	@author Przemysław Hernik <przemek.hernik@wpserved.com>
			 */
			$scope.send_quote = function() {
				$scope.quote_request = true;
				$scope.send_order();
			};

			/**
			 * Function sends Quote confirmation to TBU Admin
			 *	@return $ajax
			 */
			$scope.send_quote_confirmation = function() {
				return $.ajax({
					type: "POST",
						url : '/api/web/index.php/send_quote',
						data: {
							'order': $scope.order
						}
				});
			};

			$scope.braintree_options = {
				onPaymentMethodReceived: function( payload ) {

				var request = $http({
					method: 'post',
					url: '/app/braintree-transaction.php',
					data: {
						total: $scope.order.total + $scope.order.vat,
						nonce: payload.nonce
						},
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					});

					request.success( function( response ) {
						if ( response ) {
							$scope.send_order( true );
						}
					});

				}
			};

			/**
			 * Include payment option in order confirmation
			 *	If paid by credit card show the following: "Betalt med kreditkort"
			 *	If not paid show the following: "Betaling via faktura"
			 *	By default payment type is: "Betaling via faktura"
			 */
			$scope.change_payment_type = function(type){
				order.data.paymenttype = type;
				//console.log('Payment type: ', order.data.paymenttype);
			};	

			$scope.change_payment_type_and_send = function(type){
				order.data.paymenttype = type;
				$scope.send_order();
				//console.log('Payment type: ', order.data.paymenttype);
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

				var project_name = $scope.order.project_name;
				if( $scope.quote_request ) project_name = '[Quote] ' + project_name;

				var translate_request = [ {
					name : 'TranslationPackageId',
					value: packageID
				}, {
					name : 'SourceLang',
					value: $scope.order.fromlanguage
				}, {
					name : 'Name',
					value: project_name
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

				var reading_files = false;

				if ( $scope.order.source == 'file' ) {

					//console.log( $scope.order.files_raw );

					$.each( $scope.order.files_raw, function( index, file ) {

						reading_files = controller.read_file( file );

						reading_files.done( function( result ) {
							translate_request = translate_request.concat( result );
						} );

					});

					current_api_file = 0;

				} else {

					translate_request = translate_request.concat( [ { 
						name: 'Text',
						value: $scope.order.project_text
					} ] );

					//console.log(translate_request);
				}

				if ( $('.braintree-popup').hasClass('in') ) {

					$('.braintree-popup').modal( 'hide' ).on('hidden.bs.modal', function () {
						
						if ( reading_files !== false ) {

							reading_files.done( function( result ) {
								controller.api_request( translate_request );
							} );
							
						} else {

							controller.api_request( translate_request );

						}

					});

				} else {

					if ( reading_files !== false ) {

						reading_files.done( function( result ) {
							controller.api_request( translate_request );
						} );
						
					} else {

						controller.api_request( translate_request );

					}

				}

			};

			controller.api_request = function( request ) {

				$.ajax( {
					url: 'https://tms.translatedbyus.com/projectt/api/orderadd', 
					type: 'POST',
					data: request,
					success: function( res ) {

						try {
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
						}
						catch (e) {
							console.log(e);
						}

						$scope.order.files_raw = {};
						$cookies.putObject( 'tbu_order_details', $scope.order );

						if( $scope.quote_request ){
							$scope.send_quote_confirmation().done( function() {
								controller.sending = false;
								$('.quote-confirmation-popup').on('hidden.bs.modal', function (e) {
									window.location.href = 'bestil-oversaettelse';		
								});
								$(".quote-confirmation-popup").modal('show');						
							} );
						}else{
							controller.sending = false;
							$scope.send_order_confirmation().done( function() {
								window.location.href = 'ordrebekraeftelse';							
							} );
						}

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

				$scope.disable_selected_lang();

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

				$scope.calc_delivery_time();

				$scope.calc_total();

			}

			$scope.calc_total = function() {

				if ( $scope.order.tolanguage ) {
					$scope.order.total = Math.round( $scope.order.count * $scope.order.word_price * $scope.order.tolanguage.length * 1000 ) / 1000;
					
					if ( $scope.order.no_vat ) {
						$scope.order.vat = 0;
					} else {
						$scope.order.vat = $scope.order.total * 0.25;
					}

				} else {
					$scope.order.total = 0;
					$scope.order.vat = 0;
				}

			}

			$scope.calc_delivery_time = function() {

				console.log( $scope.order.count );

				if ( $scope.order.count <= 2000 ) {

					if ( $scope.order.count <= 1000 ) {
						$scope.order.estimation = '1-2';
					} else {
						$scope.order.estimation = '2-3';
					}

				} else {

					var days = Math.floor( $scope.order.count / 2000 ) + 2;

					$scope.order.estimation = days + '-' + ( days + 1 );

				}

			}

			controller.dropzoneConfig = {
				url: 'https://tms.translatedbyus.com/projectt_test/api/filessave',
				previewsContainer: ".dropzone-previews",
				uploadMultiple: true,
				parallelUploads: 100,
				maxFiles: 100,
				addRemoveLinks: true,
				dictCancelUpload: 'Annulér upload',
				dictRemoveFile: 'Fjern fil',
				dictMaxFilesExceeded: 'Du kan kun uploade én fil',
				autoProcessQueue: true,
			};

			controller.dropzone_successmultiple = function( files, res ) {

				var dropzone = this;

				$scope.order.files_raw = dropzone.files;

				$scope.order.has_files = res;

				uploadTimer = setTimeout( function() {
					controller.upload_alert = 'Tæller ord...';
					$scope.$apply();
				}, 2000 );

				uploadTimer = setTimeout( function() {
					controller.upload_alert = 'Tæller stadig ord - vent venligst...';
					$scope.$apply();
				}, 5000 );

				controller.counting = true;

				$.ajax({
					type: "POST",
					url : 'https://tms.translatedbyus.com/projectt_test/api/filewordcount',
					data: res,
					success: function( resp ) {
						
						clearTimeout( uploadTimer );
						controller.upload_alert = '';
						$scope.$apply();

						$.each( resp.files, function( rkey, rvalue ) {
							$scope.order.files[ rvalue.fileName ] = rvalue.wordsCount;
						} );

						$scope.count_words();

						controller.counting = false;

						//console.log($scope.order.files)
						//dropzone.processQueue();

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
