var priceRate = Basic_price;
var wordCount = 0;
var isFile;
var fileObject = {};
var fromLanguageString = "[]";
var toLanguageString = "[]";
var toLanguageCount = 1;
var free = SMT_price;
var basic = Basic_price;
var pro = PRO_price;
var selectedQuality = basic;
var allFiles;
var uploadTimer;
var hasLanguages = false
var fromLanguage; // magicsuggest
var toLanguage; // magicsuggest
var fromLanguageJSON;
var toLanguageJSON;
var toLangs = [];

jQuery(document).ready(function($){

	var form = $("#translation-order");
	
	form.validate();
	form.css('max-height', form.height()+100)

	var sticky = new Waypoint.Sticky({
	  element: $('#summary')[0]
	})
	
	
	Dropzone.autoDiscover = false; 

	
	/*
	* WPS Send mail onBlur #ordertext, #email */
	form.find("#ordertext").blur(function(){
		wpsSendMail(form,null);
	});

	form.find("#email").blur(function(){
		wpsSendMail(form,null);
	});

	function wpsSendMail(form,upload_link){
		var email = $(form).find("#email").val();
		var content = $(form).find("#ordertext").val();
		$.ajax({
		    type     : "POST",
		    url: 	'/api/web/index.php/sendMail',
		    data     : {
		    		email : email,
		            content : content
		    },
		    success : function(msg) {
		    	console.log(msg);
		    },
		    complete : function(r) {
		    	//console.log(r);
		    },
		    error:    function(error) {
		    	console.log("Error in sending Mail "+error);
		    }
		});
	}

	
	var myDropzoneNST = $('#my-drop').dropzone({ 
	    url: '/api/web/index.php/file_word_count',
	    previewsContainer: ".dropzone-previews",
	    uploadMultiple: true,
	    parallelUploads: 100,
	    maxFiles: 100,
	    addRemoveLinks:true,
	    dictCancelUpload : cancelUpload,
	    dictRemoveFile : removeFile,
	    dictMaxFilesExceeded : noMoreFiles,
	    init: function() {

	    	this.on("addedfiles", function(files) { 
				 allFiles = files;
				 isFile = true;
				 				 
				 
				 uploadTimer = setTimeout(function(){				 
					 $('.alert-info').show().find('.txt').text(CountingWords);			 
				 }, 2000)
				 
				 uploadTimer = setTimeout(function(){					 
					$('.alert-info').show().find('.txt').text(StillCountingWords);					 
				 }, 5000)
				 
				 $('.order-block').addClass('hasfiles');

			  });
		  
		  	this.on("successmultiple", function(files, response) {
			  var res = JSON.parse(response);
			  
			  //wpsSendMail(form, res.upload_name); 	  	
			  //wpsSendMail(form, null); 		  
			  			  
			  clearTimeout(uploadTimer);
			  $('.alert-info').hide().text('');	
			  	
			  // add wordcounts to fileObject
			  $.each(res.files, function( rkey, rvalue ) {
				 $.each(files, function( fkey, fvalue ) {
					 var uploadName = fvalue['name'].replace(/[^\x20-\x7E]/g, '__');
					 console.log(uploadName)	
					 var responseName = rkey.slice(uploadName.length*-1);
					 if (uploadName == responseName){
						 fileObject[uploadName] = res.files[rkey]['wordsCount'];
					 }

				 })  
			  });

			  
			  
			  
			  
			  updateWordCount();
			  	
				if (res.skipped.length > 0) {				 
					$('.alert-danger').show().text('Text not found in following files: ' + res.skipped.join(', ') + '. Files ignored');
				}
      
	  		});

			this.on("error", function(files, response) {
				$(".dropzone-danger").html('We are still counting words. Just fill out the form and submit it to get a price by email when we are ready.');
				$(".dropzone-danger").css("display","block");
				$(".dropzone-info").css("display","");
				$(".dropzone-info .txt").empty();
			}) 
	  		
	  		this.on("removedfile", function(files) {	

	  			$(".dropzone-danger").empty();
				$(".dropzone-danger").css("display","");
				$(".dropzone-info").css("display","");
				$(".dropzone-info .txt").empty();		  		
		  		
		  		if(this.files.length == 0){
			  		$('.order-block').removeClass('hasfiles');
			  		isFile = false;
			  		$('.alert').hide()
		  		} 
		  		
		  		delete fileObject[files['name']];
		  		updateWordCount();
		  		//nsole.log(files, fileObject)
		  			
		  	})
		  	
		  	this.on("canceled", function(files, response) {		  		
		  		if(this.files.length == 0){
			  		$('.order-block').removeClass('hasfiles');
			  		isFile = false;
			  		$('.alert').hide()
		  		}	
		  	})
	  		
		  	      
	    },
	    
	    sending: function(file, xhr, formData) {
		    //console.log(file, xhr, formData)
		    
		    formData.append("from", 'da');
		    formData.append("to", 'en');
		    

		}
	    
	    
	  }) // end dropzone
	  
	function updateWordCount(){
		wordCount = 0;		
		$.each(fileObject, function( key, value ) {		
			wordCount += value;
		})
		
		updateSummary(wordCount);
		
		
	}
	
	function updateSummary(wc){
		
		var price = Math.round(wordCount*selectedQuality*toLanguageCount * 1000) / 1000;
		price = price.toString();
		
		if (curLang == 'da'){
			price = price.replace('.', ',');
		}
	
		if (wordCount == 0) {
			$('#summary .wordCount').slideUp()
			$('#summary .total').slideUp()		
		} else {
			$('#summary .wordCount').slideDown().find('.output').text(wordCount);
			$('#summary .total').slideDown().find('.output').text(price);
			
		}
		
		isFormValid();
	}
	
	function countWords( val ){
	    return {
	        charactersNoSpaces : val.replace(/\s+/g, '').length,
	        characters         : val.length,
	        words              : val.match(/\S+/g).length,
	        lines              : val.split(/\r*\n/).length
	    }
	}
	
	$('#ordertext').on('input', function(){
    	
    	if(this.value != ""){
		  wordsCounted = countWords( this.value );
		  wordCount = wordsCounted.words;
		  	updateSummary(wordCount);
	  	} else {
		  	updateWordCount();
	  	}    
	});
	
	$('#projectName').on('input', function(){		
		showSummaryRow('projectName', this.value)		
	});
	
	$(' input').on('input', function(){		
		showSummaryRow($(this).attr('id'), this.value)		
	});
	
	
	$.getJSON( themePath+'js/lang_'+curlang+'.json', function( the_data ) {
	
		fromLanguageJSON = toLanguageJSON = the_data;
		
	
		fromLanguage = $('#fromLanguage').magicSuggest({
	        allowFreeEntries: false,
			data: fromLanguageJSON,
			maxSelection: 1,
			required: true,
			placeholder: ChooseLanguage,
	    });
	    
	    toLanguage = $('#toLanguage').magicSuggest({
	        allowFreeEntries: false,
	        disabledField: 'disabled',
			data: toLanguageJSON,
			expandOnFocus: true,
			required: true,
			maxSelection: null,
			placeholder: ChooseLanguages,
// 			selectionPosition: 'bottom',
			
	    });    
	    
				
		$(fromLanguage).on('selectionchange', function(e,m){	
			
			showSummaryRow('fromLanguage', this.getSelection()[0]['name'])
			
			fromLanguageString = JSON.stringify(this.getSelection()[0]['name']);
			
			$('#SourceLang').val(this.getSelection()[0]['id'])
			
			//console.log(this.getSelection()[0], toLanguageJSON);
			
			for (var k in toLanguageJSON){
				if (typeof toLanguageJSON[k] !== 'function') {
				    if (toLanguageJSON[k]['id'] == this.getSelection()[0]['id']){
					  	toLanguageJSON[k]['disabled'] = true;
				    } else {
					    toLanguageJSON[k]['disabled'] = false;
				    }
			    }	
			}
			
			// console.log(toLanguageJSON)
			
			$(toLanguage)[0].setData(toLanguageJSON);
			$(toLanguage)[0].removeFromSelection(this.getSelection(), false);
			
			anyLanguage();		  
		});
		
		$(toLanguage).on('selectionchange', function(e,m){
			
			toLangs = [];
			
			var lang = this.getSelection().map(function(elem){
				toLangs.push(elem.id);
			    return elem.name;
			}).join(", ");
			
			
					
			
			//$('#TargetLanguages').val(JSON.stringify(toLangs));
			//$('#TargetLanguages').val(toLangs);	
			
			showSummaryRow('toLanguage', lang)
			toLanguageString = JSON.stringify(this.getSelection());
			anyLanguage();
			
			if(this.getValue() != ""){
				toLanguageCount = lang.split(',').length;
			} else {
				toLanguageCount = 1;
			}
			
			
			
			updateSummary(wordCount);
				
		});
		
	})
	
	function anyLanguage(){		
		
		if (toLanguageString == "[]" && fromLanguageString == "[]"){
			$('#summary .languages').slideUp();
			
		} else {
			$('#summary .languages').slideDown();
			
		}
		
		isFormValid()		

	}
	
	
	function showSummaryRow(cls, input){
		
		
		
		if(input != ""){
    		$('#summary .'+cls).slideDown().find('.output').text(input);
    		
    	} else {
	    	$('#summary .'+cls).slideUp().find('.output').text('');
	    	
    	}   	
    	
	}
	
	
	$('#ordertext').focus(function(){
		$('.order-block').addClass('textinput');		
	}).blur(function(){
		if ($(this).val() == ""){
			$('.order-block').removeClass('textinput');
		}		
	})
	
	
	$('#projectName').focus(function(){
		$(this).addClass('hasName');		
	}).blur(function(){
		if ($(this).val() == ""){
			$(this).removeClass('hasName');
		}		
	})
	
	$('.order-block .textarea-ins .clear').click(function(){
		$('#ordertext').val('').blur();
		wordCount = 0;
		updateSummary(wordCount)
	})
	
	/* ORDER QUALITY*/
	$('.order-block .selectable').click(function(){
		$('.order-block .active').removeClass('active').find('.btn').text(SelectText);
		$(this).addClass('active').find('.btn').text(SelectedText);
		
		selectedQuality = eval($(this).attr('rel'));
		updateSummary(wordCount);
		
		$('#summary .quality .q').addClass('hidden');		
		$('#TranslationPackageId').val(this.id.substr(1));		
		$('#summary .quality .'+$(this).attr('rel')).removeClass('hidden');
		
		var type = '/type-' + $(this).attr('id')
		ga('send', 'pageview', type);
		
	})
	    
	
	$('#translation-order').submit(function(e){
		e.preventDefault();
		
		
		var files = Dropzone.forElement('#my-drop').getAcceptedFiles();
		var data = $("#translation-order").serializeArray();
		var filesToUpload = [];
		var curFileCount = 0;
		var count = 0;
		
		// Add ToLanguages		
		for(var lang in toLangs){
			data = data.concat([
		    	{name: "TargetLanguages", value: toLangs[lang]},
			]);
		}
		
		if (isFile){
		
			function readAndPreview(file) {
		
			      var reader = new FileReader();
			      var filesObject = {};
			      
			
			      reader.addEventListener("load", function (e) {
				      filesObject.FileName = file.name;
				      filesObject.FileContent = reader.result.slice(reader.result.indexOf(',') + 1);
				      
				      //filesToUpload.push(fileArray);	      
				      
				      data = data.concat([
					      {name: "FileToUpload["+count+"][FileName]", value: file.name},
					      {name: "FileToUpload["+count+"][FileContent]", value: reader.result.slice(reader.result.indexOf(',') + 1)},
					  ]);
					  
					  
					  
					  count++;
					  
				      
			      }, false);
			      
			      reader.addEventListener("loadend", function () {
				      
				      curFileCount++
				      
					  if (curFileCount == files.length){
						  
						  
						// REMOVE Text from array:  
						var index;
						
						for (var k in data){
						    if (typeof data[k] !== 'function') {
							    if (data[k]['name'] == 'Text'){
								  index = k;
							    }
						    }
						}
						
						data.splice(index, 1);  
						
						// For multiple files:
						  
						//value: JSON.stringify(filesObject)
						
						
						/*
						data = data.concat([
					    	{name: "FileToUpload.FileName", value: filesObject.FileName},
					    	{name: "FileToUpload.FileContent", value: filesObject.FileContent},
						]);					 
						 */
						sendData(data);	  
					  }		  
				  })
			            	
			      reader.readAsDataURL(file);
		
			}
		  
			if (files) {
				[].forEach.call(files, readAndPreview);
			}
		} else if(wordCount == 0 && !isFile){
			$('.alert.alert-danger').text(noText).show();	
		} else if(wordCount > 0){
			sendData(data);	
		}  			
		
		
		
	})
	
	
	serialize = function(obj) {
	  var str = [];
	  for(var p in obj)
	    if (obj.hasOwnProperty(p)) {
	      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
	    }
	  return str.join("&");
	}
	
	
	function sendData(data){
		
		
		$("#translation-order").addClass('loading');
		$('#sendLoader').fadeIn();
		
		$.ajax({
			'url': 'http://tms.translatedbyus.com/projectt/api/orderadd', 
			'type': 'POST',
			'data': data,
			'success': function(res) {
				console.log(res)
				
				$("#translation-order").addClass('zeroW');
				$("#response").slideDown();		
				$('.orderform').css('min-height', $('#summary').height()+253)		
				$('.one-step .sticky-wrapper, .one-step #summary.stuck, #orderformwrapper').addClass('confirmation');
				$('#sendLoader').fadeOut();

				var type = data[0].value; //TranslationPackageId
				var url = '/order-confirmed'+'?type=q'+type;
				window.history.pushState({ 
					page: '/order-confirmed' 
				},
				  'Order Confirmed',
				  url
				);

				ga('send', 'pageview', url);
				
				// Google googleadservices
				var google_conversion_id = 943361506;
				var google_conversion_language = "en";
				var google_conversion_format = "3";
				var google_conversion_color = "ffffff";
				var google_conversion_label = "8qtUCKCd1mQQ4pvqwQM";
				var google_conversion_value = 50.00;
				var google_conversion_currency = "DKK";
				var google_remarketing_only = false;
				
				$.getScript( "http://www.googleadservices.com/pagead/conversion.js" );

				function trackConv(google_conversion_id, google_conversion_label) {
			      var image = new Image(1, 1); 
			      image.src = "//www.googleadservices.com/pagead/conversion/" + google_conversion_id + "/?label=" + google_conversion_label + "&script=0&value="+google_conversion_value;  
				}

				trackConv(google_conversion_id,google_conversion_label);

			},
			'error': function(res, status, error) {
				console.log(res, status, error) ;
			}
		});	
	}
	
	
	function resetForm(){
		wordCount = 0;
		$('#translation-order').removeClass('loading zeroW').find("input[type=text], input[type=email], textarea").val("");
		$('.order-block').removeClass('textinput hasfiles');
		showSummaryRow('projectName', '');
		showSummaryRow('languages', '');
		showSummaryRow('fromLanguage', '');
		showSummaryRow('toLanguage', '');
		showSummaryRow('name', '');
		showSummaryRow('email', '');
		showSummaryRow('company', '');
		fromLanguageString = "[]";
		toLanguageString = "[]";
		fromLanguage.clear(true)
		toLanguage.clear(true)
		Dropzone.forElement('#my-drop').removeAllFiles(true);
		updateSummary(wordCount)
		
		$("#response").slideUp();
		$('.alert').hide(); 
		$('.one-step .sticky-wrapper, .one-step #summary.stuck, #orderformwrapper').removeClass('confirmation');
		$('input[type="submit"]').addClass('disabled');
	}
	
	
	$('#reset-form').click(function(){
		resetForm()
	})
	
	$('#email').on('input', isFormValid)
	
	
	function isFormValid(){
				
		if (toLanguageString != "[]" && fromLanguageString != "[]" && wordCount > 0 && $('#email').valid()){
			$('input[type="submit"]').removeClass('disabled');
		} else {
			$('input[type="submit"]').addClass('disabled');
		}		
		
	}

	$('#order-confirmation').click(function(){
		$('#summary .data').printElement({leaveOpen:true});
	})
	
});
	

