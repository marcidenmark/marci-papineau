<!DOCTYPE html>
<html ng-app="translatedbyus" lang="da">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<title><?php echo get_seo_tag( 'title' ); ?></title>
	<meta name="description" content="<?php echo get_seo_tag( 'description' ); ?>" >
	<base href="/">

	<link href="assets/img/favicon.png" rel="icon" type="image/png" />

</head>
<body ng-cloak ng-controller="MainController as mainCtrl">

<nav>
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><img class="img-responsive" src="assets/img/logo.png" /></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="om-os">Om os</a>
				</li>
				<li>
					<a href="oversaettere">Oversætterne</a>
				</li>
				<li>
					<a href="oversaet-hjemmeside">Oversæt hjemmeside</a>
				</li>
				<li class="dropdown">
					<a href="#0" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mere  <span class="caret"></span></a>
					<ul class="dropdown-menu megamenu-content" role="menu">
						<li><a href="oversaettelsesdata">Oversættelsesdata</a></li>
						<li><a href="validering">Validering</a></li>
						<li><a href="privatlivspolitik">Privatlivspolitik og handelsbetingelser</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="brand-info">
					<div class="data-wrap">
						<i class="fa fa-phone" aria-hidden="true"></i><a href="tel:+45-23-67-42-72"> (+45) 23 67 42 72</a>
					</div>
					<div class="data-wrap">
						<i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:info@translatedbyus.com">info@translatedbyus.com</a>
					</div>
				</li>
				<li>
					<button ng-click="reset_order(); changeView('bestil-oversaettelse')" class="navbar-btn btn btn-primary">Bestil oversættelse</button>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
	<div class="clearfix"></div>
</nav>

<div class="page-container">
	<?php get_view(); ?>
</div>

<footer>
	<div class="footer-top footer-section">
		<div class="container">
			<div class="social-icons text-center">
				<a href="https://www.facebook.com/translatedbyus/" target="_blank" class="social-icon facebook"></a>
				<a href="https://www.linkedin.com/company/translated-by-us" target="_blank" class="social-icon linkedin"></a>
				<a href="https://plus.google.com/+TranslatedByUsGentofte" target="_blank" class="social-icon googleplus"></a>
				<a href="https://twitter.com/tbu_world_wide" target="_blank" class="social-icon twitter"></a>
			</div>
			<h2 class="text-center title">Translated By Us ApS</h2>
			<div class="row info">
				<div class="col-sm-6">
					<div class="row contact-info">
						<div class="col-xs-4 text-right">
							<img src="assets/img/location_icon.png" alt="Location" class="footer-icon" />
						</div>
						<div class="col-xs-8">
							<p class="text-left">
								Ellegårdsvej 10 1st floor<br />
								2820 Gentofte<br />
								Denmark<br />
							</p>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row contact-info">
						<div class="col-xs-4 text-right">
							<img src="assets/img/phone_icon.png" alt="Contact" class="footer-icon tel-icon" />
						</div>
						<div class="col-xs-8">
							<p class="text-left">
								(+45) 23 67 42 72<br />
								<a href="mailto:info@translatedbyus.com">info@translatedbyus.com</a><br />
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="row team">
				<div class="col-sm-4">
					<div class="person">
						<div class="photo">
							<a href="https://www.linkedin.com/in/gitta-helena-greve-%E2%80%93-translated-by-us-91a566100/en" target="_blank"><img src="assets/img/persons/gitta-helena-greve-oversaettelsesbureau-professionel-oversaettelse.jpg" alt="Gitta Helena Greve oversættelsesbureau professionel oversættelse" class="img-circle img-responsive"></a>
						</div>
						<div class="info">
							<h4 class="name">Gitta Helena Greve</h4>
							<h5 class="position">Produktionschef</h5>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="person">
						<div class="photo">
							<a href="https://www.linkedin.com/in/jacob-vesterk%C3%A6r-7a01a411" target="_blank"><img src="assets/img/persons/jacob-vesterkaer-oversaettelsesbureau-professionel-oversaettelse.jpg" alt="Jacob Vesterkær oversættelsesbureau professionel oversættelse" class="img-circle img-responsive"></a>
						</div>
						<div class="info">
							<h4 class="name">Jacob Vesterkær</h4>
							<h5 class="position">Salg</h5>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="person">
					<div class="photo">
							<a href="https://www.linkedin.com/in/mads-ingemann-bl%C3%BCcher-85ba624" target="_blank"><img src="assets/img/persons/mads-ingemann-blucher-oversaettelsesbureau-professionel-oversaettelse.jpg" alt="Mads Ingemann Blücher oversættelsesbureau professionel oversættelse" class="img-circle img-responsive"></a>
						</div>
						<div class="info">
							<h4 class="name">Mads Ingeman Blücher</h4>
							<h5 class="position">Direktør</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom footer-section">
		<div class="container">
			<a href="privatlivspolitik">PRIVATLIVSPOLITIK</a>
		</div>
	</div>
</footer>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MZ4DGD"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MZ4DGD');</script>
<!-- End Google Tag Manager -->

<!--build:css assets/css/styles.min.css -->
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="assets/css/c3/c3.min.css">
<link rel="stylesheet" href="assets/css/bootstrap-chosen.css">
<link rel="stylesheet" href="assets/css/dropzone.css">
<link rel="stylesheet" href="assets/css/tbustyle.css">
<!-- endbuild -->

<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion_async.js"></script>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<!--build:js assets/js/scripts.min.js -->
<script type="text/javascript" src="assets/js/tbuscripts.js"></script>

<script type="text/javascript" src="assets/js/jquery.printElement.min.js"></script>

<script type="text/javascript" src="assets/js/c3/d3.min.js"></script>
<script type="text/javascript" src="assets/js/c3/c3.min.js"></script>

<script type="text/javascript" src="assets/js/dropzone.js"></script>

<script type="text/javascript" src="assets/js/angular.min.js"></script>
<script type="text/javascript" src="assets/js/angular-route.min.js"></script>
<script type="text/javascript" src="assets/js/angular-cookies.js"></script>

<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="assets/js/angular-chosen.min.js"></script>
<script type="text/javascript" src="assets/js/angular-dropzone.js"></script>
<script type="text/javascript" src="assets/js/angular-google-analytics.min.js"></script>
<script type="text/javascript" src="assets/js/braintree-angular.js"></script>
<script type="text/javascript" src="assets/js/angular-metatags.min.js"></script>
<!-- endbuild -->

<!--build:js assets/js/app.min.js -->
<script type="text/javascript" src="assets/js/main.js"></script>
<script type="text/javascript" src="assets/js/controllers/form.js"></script>
<script type="text/javascript" src="assets/js/controllers/confirmation.js"></script>
<script type="text/javascript" src="assets/js/controllers/archive.js"></script>
<script type="text/javascript" src="assets/js/controllers/hjemmeside.js"></script>
<script type="text/javascript" src="assets/js/controllers/privat.js"></script>
<!-- endbuild -->

</body>
</html>
