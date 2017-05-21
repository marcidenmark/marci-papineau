var priceRate = 0.05;
var wordCount = 0;
var isFile;
var fileObject = {};
var fromLanguageString = "[]";
var toLanguageString = "[]";
var toLanguageCount = 1;
var free = 0;
var basic = 0.05;
var pro = 0.17;
var selectedQuality = basic;
var allFiles;


jQuery(document).ready(function($){

	var form = $("#translation-order").show();
 
	function resizeJquerySteps() {
	//	console.log('resize')
	      $('.wizard .content').animate({ height: $('.body.current').outerHeight() }, "slow");
	}
 
	form.steps({
		headerTag: "h3",
	    bodyTag: "section",
	    transitionEffect: "slideLeft",
	    stepsOrientation: "vertical",
	    onInit: function (event, currentIndex) { 
		    $('.wizard .steps ul').after($('#summary'));
	    },
	    onStepChanging: function (event, currentIndex, newIndex)
	    {
		    
		    $('.error').removeClass('error');
		    
		    if (wordCount == 0){
			    $('.or').addClass('error');
			    return false;
		    }
		    
		    if (fromLanguageString == "[]"){
			    $('.fromLang').addClass('error');
			    return false;
		    }
		    
		    if (toLanguageString == "[]"){
			    $('.toLang').addClass('error');
			    return false;
		    }
		    
		     if (currentIndex < newIndex)
	        {
	            // To remove error styles
	            form.find(".body:eq(" + newIndex + ") label.error").remove();
	            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
	        }
		    
		    
		    form.validate().settings.ignore = ":disabled,:hidden";
			return form.valid();		    
		    
		    
	    },
		
		onStepChanged: function (event, currentIndex, priorIndex) {
            resizeJquerySteps();
		},
		onFinishing: function (event, currentIndex)
	    {
	        sendEmail();
	    
	        
	    }
	}).validate({
	    errorPlacement: function errorPlacement(error, element) { 
		    element.before(error); 
		}	    
	});
	
	
	
	Dropzone.autoDiscover = false; 

	
	$('#my-drop').dropzone({ 
	    url: '/api/web/index.php/file_word_count',
	    previewsContainer: ".dropzone-previews",
	    uploadMultiple: true,
	    parallelUploads: 100,
	    maxFiles: 100,
	    addRemoveLinks:true,
	    init: function() {
	      	
		  	this.on("addedfiles", function(files) { 
				 allFiles = files;
				 isFile = true;
				 
				 $('.order-block.step1').addClass('hasfiles');
				 resizeJquerySteps();
			  });
		  
		  	this.on("successmultiple", function(files, response) {
			  var res = JSON.parse(response);		  		  
			  	
			  	
			  // add wordcounts to fileObject
			  $.each(res.files, function( rkey, rvalue ) {
				 $.each(files, function( fkey, fvalue ) {
					 var uploadName = fvalue['name'];
					 var responseName = rkey.slice(uploadName.length*-1);
					 if (uploadName == responseName){
						 fileObject[uploadName] = res.files[rkey]['wordsCount'];
					 }

				 })  
			  })
			  
			  updateWordCount();
			  	
			  if (res.skipped.length > 0) {				 
                $('.alert').show().text('Text not found in following files: ' + res.skipped.join(', ') + '. Files ignored');
          	  }
      
	  		});
	  		
	  		this.on("removedfile", function(files) {			  		
		  		
		  		if(this.files.length == 0){
			  		$('.order-block.step1').removeClass('hasfiles');
			  		isFile = false;
		  		} 
		  		
		  		delete fileObject[files['name']];
		  		updateWordCount();
		  		//nsole.log(files, fileObject)
		  			
		  	})
		  	
		  	this.on("canceled", function(files, response) {		  		
		  		if(this.files.length == 0){
			  		$('.order-block.step1').removeClass('hasfiles');
			  		isFile = false;
		  		}	
		  	})
	  		
		  	      
	    },
	    
	    sending: function(file, xhr, formData) {
		    formData.append("from", 'da');
		    formData.append("to", 'en');
		    resizeJquerySteps();
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
		
	
		if (wordCount == 0) {
			$('#summary .wordCount').slideUp()
			$('#summary .total').slideUp()		
		} else {
			$('#summary .wordCount').slideDown().find('.output').text(wordCount);
			$('#summary .total').slideDown().find('.output').text(Math.round(wordCount*selectedQuality*toLanguageCount * 1000) / 1000);
			
		}
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
	
	$('.step2 input').on('input', function(){		
		showSummaryRow($(this).attr('id'), this.value)		
	});
	


	var fromLanguage = $('#fromLanguage').magicSuggest({
        allowFreeEntries: false,
		data: ['Danish', 'English'],
		maxSelection: 1,
		required: true
    });
	
	var toLanguage = $('#toLanguage').magicSuggest({
        allowFreeEntries: false,
		data: ['Danish', 'German', 'Finish', 'French', 'Italian', 'Dutch', 'Nowegian','Swedish'],
		expandOnFocus: true,
		required: true
    });
	
	$(fromLanguage).on('selectionchange', function(e,m){		
		showSummaryRow('fromLanguage', this.getValue())
		fromLanguageString = JSON.stringify(this.getValue());
		anyLanguage();		  
	});
	
	$(toLanguage).on('selectionchange', function(e,m){
		var lang = this.getValue().toString();
		showSummaryRow('toLanguage', lang.split(',').join(', '))
		toLanguageString = JSON.stringify(this.getValue());
		anyLanguage();
		
		if(this.getValue() != ""){
			toLanguageCount = toLanguageString.split(',').length;
		} else {
			toLanguageCount = 1;
		}
		
		updateSummary(wordCount);
			
	});
	
	
	function anyLanguage(){
		
	
		
		if (toLanguageString == "[]" && fromLanguageString == "[]"){
			$('#summary .languages').slideUp();
		} else {
			$('#summary .languages').slideDown();
		}

	}
	
	
	function showSummaryRow(cls, input){
		if(input != ""){
    		$('#summary .'+cls).slideDown().find('.output').text(input);
    		
    	} else {
	    	$('#summary .'+cls).slideUp().find('.output').text();
	    	
    	}   	
    	
	}
	
	/* STEP 1 */
	
	$('#ordertext').focus(function(){
		$('.order-block.step1').addClass('textinput');		
	}).blur(function(){
		if ($(this).val() == ""){
			$('.order-block.step1').removeClass('textinput');
		}		
	})
	
	
	$('#projectName').focus(function(){
		$(this).addClass('hasName');		
	}).blur(function(){
		if ($(this).val() == ""){
			$(this).removeClass('hasName');
		}		
	})
	
	$('.order-block.step1 .textarea-ins .clear').click(function(){
		$('#ordertext').val('').blur();
	})
	
	/* STEP 3 */
	
	$('.order-block.step3 .selectable').click(function(){
		$('.order-block.step3 .active').removeClass('active');
		$(this).addClass('active');
		
		selectedQuality = eval($(this).attr('rel'));
		updateSummary(wordCount);
	})
	    
	
	
	var sendEmail = function() {
	    var fileinput = allFiles;
	    if (!fileinput.files[0]) return false;
	    var rformData = new FormData($('form')[0]);
		formData.append('TargetLanguages', toLanguageString.split(',').join(' '));
		formData.append('words', wordCount);
		formData.append('cost', selectedQuality);
	    for (var i = 0; i < fileinput.files.length; i++) {
	        formData.append('file[' + i + ']', fileinput.files[i]);
	    }
	    
	    console.log(formData);
	    
			/*$.ajax({
				'url': '/api/web/index.php/send_file_email', 
				'type': 'POST',
				'data': formData,
				'processData': false,
				'contentType': false,
				'dataType': 'json',
				'success': function(res) {
					location.href = '/?ordered';
				},
				'error': function(res, status, error) {
					alert('Error occured: ' + status + ' ' + error);
				}
		});*/
		
	}
	
	
	
	

	
	
});
	

	
	/*
		
	function updatePrice() {
	    jQuery('.words-price').html((wordsCount * priceRate * parseInt(jQuery('.trans-count').html())).toFixed(2) + '');
	}
	
	
	
	var ua = jQuery.browser;
	if ( ua.msie ) {
		jQuery(document).ready(function(){
			jQuery("body").addClass("ie");
		});
	}
	if ( ua.msie && ua.version.slice(0,1) == "7" ) {
		jQuery(document).ready(function(){
			jQuery("body").addClass("ie7");
		});
	}	
	if ( ua.msie && ua.version.slice(0,1) == "8" ) {
		jQuery(document).ready(function(){
			jQuery("body").addClass("ie8");
		});
	}
	    
	    var tmp = window.location.href.split('?');
	    if (tmp.length > 1 && tmp[1] == 'ordered') {
	        jQuery('.order-block.step1, .order-block.step2, .order-block.step3').stop(true,true).addClass('ghost-block').animate({left:"-1960px"}, speed);
			jQuery('.order-block.step4').show().stop(true,true).animate({left:"131px", opacity: 1}, speed);
			jQuery('.side-blocks').fadeOut(speed);
			jQuery('.ghost-shadow').css('z-index',100);
			jQuery('.customer-name').text(jQuery('input[name="Name"]').val());
			jQuery('#order-summary').html(jQuery('.side-blocks .order-table').html());
	    }
	
	
	jQuery(document).ready(function($){
	
		var speed = 600;
		var isFile;
	
		$('.step1-sel1').selectpicker();
		$('.step1-sel2').selectpicker();
		
		
		
		$('.order-block .go-to-step2').bind('click', function(){
	        if (parseInt(jQuery('.trans-count').html()) == 0) {
	            $($('.step1-sels button').get(1)).css('border', '1px solid red');
	            $('#step1-error').show();
	            return;
	        }
	        
	        $($('.step1-sels button').get(1)).css('border', 'none');
	        $('#step1-error').hide();
	    
	        
			$('.order-block.step1').stop(true,true).addClass('ghost-block').animate({left:"-160px"}, speed);
			$('.order-block.step1 .tofade').stop(true,true).fadeTo( speed , 0.5);
			$('.order-block.step2').animate({left:"514px", opacity: 1}, speed).show();
			$('.side-block.for-step-1').fadeOut(speed);
			$('.side-block.for-step-2').fadeIn(speed);
			$('.top-img img').stop(true,true).animate({left:"-15%"}, speed);
			
			isFile = getWordsCount();
			if (isFile) {
				$('.words-count, .words-price').html('counting');
				$('input[type=submit]').attr('disabled', true);
			}
		})
		
		$('input[type=submit]').click(function() {
	        $('#loader').show();
			if (isFile) {
				sendEmail();
				return false;
			}
		});
		
		$('.order-block.step1 .textarea-ins textarea').focus(function(){
			$('.order-block.step1').addClass('textivated').removeClass('filevated');
		})
		
		$('.order-block .edit-step1').bind('click', function(){
			$('.order-block.step1').stop(true,true).removeClass('ghost-block').animate({left:"175px"}, speed);
			$('.order-block.step1 .tofade').stop(true,true).fadeTo( speed , 1);
			$('.order-block.step2').animate({left:"909px", opacity: 0}, speed);
			$('.side-block.for-step-2').fadeOut(speed);
			$('.side-block.for-step-1').fadeIn(speed);
			$('.top-img img').stop(true,true).animate({left:"0%"}, speed);
		})
	    
	    $('.step2 .ghost-shadow').bind('click', function() {
			$('.order-block.step1').stop(true,true).addClass('ghost-block').animate({left:"-160px"}, speed);
			$('.order-block.step2').stop(true,true).addClass('ghost-block').animate({left:"514px"}, speed);
			$('.order-block.step2 .tofade').stop(true,true).fadeTo( speed , 1);
			$('.order-block.step3').animate({left:"909px", opacity: 0}, speed).show();
			$('.side-block.for-step-2').fadeIn(speed);
			$('.side-block.for-step-3').fadeOut(speed);
			$('.ghost-shadow').css('z-index',-1);
			$('.edit-step1').fadeTo(speed, 0.5);
			$('.top-img img').stop(true,true).animate({left:"-15%"}, speed);
	        $('.step1-file').after($('.step1-sels1'));
	    })
		
		$('.order-block .next-step').bind('click', function(){
	        $('input[name=Name]').parent().css('border', 'none');
	        $('input[name=Email]').parent().css('border', 'none');
	        $('#step2-error').hide();
	
	        if (!$('input[name=Name]').val() || !$('input[name=Email]').val()) {
	            if (!$('input[name=Name]').val()) $('input[name=Name]').parent().css('border', '1px solid red');
	            if (!$('input[name=Email]').val()) $('input[name=Email]').parent().css('border', '1px solid red');
	            $('#step2-error').show();
	            return;
	        }
	
			$('.order-block.step1').stop(true,true).addClass('ghost-block').animate({left:"-820px"}, speed);
			$('.order-block.step2').stop(true,true).addClass('ghost-block').animate({left:"-160px"}, speed);
			$('.order-block.step2 .tofade').stop(true,true).fadeTo( speed , 0.5);
			$('.order-block.step3').animate({left:"131px", opacity: 1}, speed).show();
			$('.side-block.for-step-2').fadeOut(speed);
			$('.side-block.for-step-3').fadeIn(speed);
			$('.ghost-shadow').css('z-index',100);
			$('.edit-step1').fadeTo(speed, 0.5);
			$('.top-img img').stop(true,true).animate({left:"-30%"}, speed);
			if (!isFile) {
				var words = $('textarea[name=Text]').val().split(' ').length;
				$('.words-count').html(words + '');
	            wordsCount = words;
	            priceRate = 0.05;
	            updatePrice();
			}
			$('.order-block.step3 .step1-textarea').after($('.step1-sels1'));
		})
		
		
	
		
		$(window).resize(function(){
			resize();
		})
		
		function resize(){
			$('.site-main').css('min-height', $(window).height() -  $('.main-footer-text').outerHeight());
			$('.section.fullscreen').css('min-height', $(window).height() -  $('.main-footer-text').outerHeight());
		}
		
		resize();
		
		
		$('#fullsize').click(function(){
		
			var $area = $('textarea.textfull')
			$(this).find('span').toggleClass('genericon-close-alt genericon-fullscreen');
		
			if ($(this).hasClass('open')){
				$(this).removeClass('open');
				$area.height('124px');
				$('.order-block.step3 .textarea-ins').height('149px')
				$('.order-block.step3').css('background', 'none');
				
			} else {
				$(this).addClass('open');
				var SH = $area.get(0).scrollHeight
				$area.height(SH);
				$('.order-block.step3 .textarea-ins').height(SH+30)
				$('.order-block.step3').css('background', 'url(/wp-content/themes/tbu/images/blue-bg-overlay.png) repeat');
				
			}
			return false;
			
		})
		
		// open zopim chat window
		$('.openChat').click(function(){
			$(".zopim iframe").contents().find(".meshim_widget_components_chatButton_Bubble").click();
			return false;
		})
		
		// toggle menu on mobile
		$('.toggle-menu').click(function(){
			$('.header .top-menu').slideToggle(200);
			return false;
		})
	    
	    $('.step1-sel2').selectpicker('val', []);	
	});
	
	
		//google maps:
		
		function initialize() {
		  var mapOptions = {
		    zoom: 12,
		    scrollwheel: false,
		    center: new google.maps.LatLng(52.5075419,13.4261419,11), 
		    styles:[
			  {
			    "featureType": "water",
			    "elementType": "geometry",
			    "stylers": [
			      { "color": "#1C629E" }
			    ]
			  },{
			    "featureType": "poi.park",
			    "elementType": "geometry",
			    "stylers": [
			      { "color": "#24B083" }
			    ]
			  },{
			    "featureType": "landscape.natural",
			    "stylers": [
			      { "color": "#24B083" }
			    ]
			  },{
			    "featureType": "road",
			    "stylers": [
			      { "visibility": "simplified" }
			    ]
			  },{
			    "featureType": "road.highway",
			    "stylers": [
			      { "visibility": "off" }
			    ]
			  },{
			    "featureType": "landscape",
			    "stylers": [
			      { "color": "#F7F7F7" }
			    ]
			  },{
			  }
			]
		  };
		
		
		
		  
		
		var map = new google.maps.Map(document.getElementById('map-canvas'),
		      mapOptions);
		      
		   
		var image = {
		    url: '/wp-content/themes/tbu/images/maps_pin.png',
		    // This marker is 20 pixels wide by 32 pixels tall.
		    size: new google.maps.Size(103, 87),
		    // The origin for this image is 0,0.
		    origin: new google.maps.Point(0,0),
		    // The anchor for this image is the base of the flagpole at 0,32.
		    anchor: new google.maps.Point(9, 87)
		  };
		  
		
		var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(52.5075419,13.4261419,11),
	      map: map,
	      icon: image
		 });   
		      
		}
		
		
		
		
		function loadScript() {
		  var script = document.createElement('script');
		  script.type = 'text/javascript';
		  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
		      'callback=initialize';
		  document.body.appendChild(script);
		}
		
		window.onload = loadScript;	
		
	*/



