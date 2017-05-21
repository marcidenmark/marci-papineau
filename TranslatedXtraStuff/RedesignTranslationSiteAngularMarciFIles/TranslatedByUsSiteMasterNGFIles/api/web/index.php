<?php

/**
 * Send all emails to DEVMAIL when DEV env
 */
if ( ! file_exists( '../../dev_emails' ) ){
	define('DEV',false);
	define('DEVMAIL',false);
}else{
	define('DEV',true);
	define('DEVMAIL','przemek.hernik@wpserved.com');
}

/**
 * Sandbox
 */
// define('LOGIN', 'caezaris');
// define('PASS', '268W0Nop');

define('LOGIN', 'api');
define('PASS', 'tbu@memsource');

error_reporting(0);
// ini_set("log_errors", 1);
// ini_set("error_log", "php-error.log");

class MemSourceClient {
  public function __construct($username, $password) {
	$res = $this->request('v3/auth/login', array('userName' => $username, 'password' => $password), null, true, false);
	$this->token = $res->token;
  }
  public function request($call, $params, $files = null, $isjson = true, $addToken = true) {
	if ($addToken) $params['token'] = $this->token;
	$url = 'http://cloud1.memsource.com/web/api/' . $call . '?' . http_build_query($params);
	//var_dump($url);
	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($c, CURLOPT_VERBOSE, true);
	//curl_setopt($c, CURLOPT_STDERR, fopen('curl.log', 'a'));
	@curl_setopt($c, CURLOPT_SAFE_UPLOAD, false);
	if ($files) {
	  curl_setopt($c, CURLOPT_POST, true);
	  @curl_setopt($c, CURLOPT_POSTFIELDS, $files);
	}
	
	$res = curl_exec($c);
	if ($isjson)
	  return json_decode($res);
	else 
	  return $res;
  }
}


function get_langs_array() {

	return array(
		'en' => 'Engelsk',
		'da' => 'Dansk',
		'no' => 'Norsk',
		'sv' => 'Svensk',
		'fi' => 'Finsk',
		'nl' => 'Hollandsk',
		'fr' => 'Fransk',
		'de' => 'Tysk',
		'ar' => 'Arabisk',
		'hy' => 'Armensk',
		'be' => 'Hviderussisk',
		'bn' => 'Bengali',
		'bs' => 'Bonisk',
		'bg' => 'Bulgarsk',
		'my' => 'Burmesisk',
		'ca' => 'Catalansk',
		'zh' => 'Kinesisk (Kina & Singapore)',
		'zh_hk' => 'Kinesisk (Hong Kong Taiwan)',
		'hr' => 'Kroatisk',
		'cs' => 'Tjekkisk',
		'en_gb' => 'Engelsk (Storbritanien)',
		'en_us' => 'Engelsk(United States)',
		'et' => 'Estisk',
		'fo' => 'Færøsk',
		'fa' => 'Farsi',
		'ka' => 'Georgisk',
		'el' => 'Græsk',
		'he' => 'Hebræisk',
		'hi' => 'Hindi',
		'hu' => 'Ungarsk',
		'is' => 'Islandsk',
		'id' => 'Indonesisk',
		'ga' => 'Irsk',
		'it' => 'Italiensk',
		'ja' => 'Japansk',
		'ko' => 'Koreansk',
		'ku' => 'Kurdisk',
		'lv' => 'Lettisk',
		'lt' => 'Litauisk',
		'mk' => 'Makedonsk',
		'mt' => 'Maltesisk',
		'mn' => 'Mongolsk(Kyrillisk)',
		'ne' => 'Nepalesisk',
		'ps' => 'Pashto',
		'pl' => 'Polsk',
		'pt_br' => 'Portugisisk (Brasilien)',
		'pt_pt' => 'Portugisisk (Portugal)',
		'ro' => 'Rumænsk',
		'ru' => 'Russisk',
		'sr' => 'Serbisk',
		'sk' => 'Slovakisk',
		'sl' => 'Slovensk',
		'so' => 'Somali',
		'es' => 'Spansk',
		'syr' => 'Syrisk',
		'tl' => 'Tagalog',
		'th' => 'Thai',
		'tr' => 'Tyrkisk',
		'uk' => 'Ukrainsk',
		'ur' => 'Urdu',
		'vi' => 'Vietnamesisk'
	);

}

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

require_once __DIR__ . '/../vendor/sendgrid/sendgrid-php.php';
$sendgrid = new \SendGrid('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');

$app['sendgrid'] = $sendgrid;

$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['debug'] = true;
$app['swiftmailer.options'] = array(
	'auth_mode' => null
);
// $app['swiftmailer.transport'] = new \Swift_MailTransport();
$app['swiftmailer.transport'] = \Swift_SmtpTransport::newInstance( 'smtp.sendgrid.net', 465, 'ssl' )->setUsername( 'translatedbyus' )->setPassword( 'tbutbutbu123' );
$app['swiftmailer.use_spool'] = false;

function handle_file( $zip, $file, $dir = '' ) {

	if ( is_object( $file ) ) { // symfony file
		$mimetype = $file->getMimeType();
	} else { // plczip file

		if ( $file['folder'] == '1' ) {
			return $zip;
		}

		$mimetype = mime_content_type( $file['filename'] );
	}

	if ( $mimetype == 'application/zip' ) {

		if ( is_object( $file ) ) { // symfony file
			$file->move( 'tempfiles/' . $dir, 'tmp.zip' );
			$tmp_zip_name = 'tempfiles/' . $dir . 'tmp.zip';
		} else { // plczip file
			$tmp_zip_name = $file['filename'];
		}
		
		$tmp_zip = new PclZip( $tmp_zip_name );

		$extracted = $tmp_zip->extract( PCLZIP_OPT_PATH, 'tempfiles/' . $dir, PCLZIP_OPT_REMOVE_ALL_PATH );

		foreach ( $extracted as $ext_file ) {
			handle_file( $zip, $ext_file );
		}

		unset( $tmp_zip );
		unlink( $tmp_zip_name );

	} else if ( is_object( $file ) ) {

		$origName = preg_replace('/[^\x20-\x7E]/', '_', $file->getClientOriginalName()); 
		$fname = 'upload_' . uniqid() . '.' . $origName;
		$file->move( 'tempfiles/' . $dir, $fname );
		$zip->add( 'tempfiles/' . $dir . $fname, PCLZIP_OPT_REMOVE_PATH, 'tempfiles/' . $dir );
		unlink( 'tempfiles/' . $dir . $fname );

	} else {

		$zip->add( $file['filename'], PCLZIP_OPT_REMOVE_PATH, 'tempfiles' );
		unlink( $file['filename'] );

	}

	return $zip;

}

$app->post('/file_word_count', function(Request $req) use ($app) {
	set_time_limit(0);

	$dir = $req->get('dir');
	$zip_filename = $req->get('zip');

	$zip_full_name = 'tempfiles/' . $dir . $zip_filename;

	/*
	* WPS: Sending file via email */
	$email = new \SendGrid\Email();
	$email
		->setSubject('New Client tried to send order form.')
		->setFrom('mads@translatedbyus.com')
		->addTo('orders@translatedbyus.com')
		->setText('User have added file to translate, which was attached to the message.')
		->setHtml('<p style="font-weight: 100; padding: 20px;">User have added file to translate, which was attached to the message.</p>')
		->addAttachment($zip_full_name)
	;
	if(DEV) $email->setTos(array(DEVMAIL));
	$app['sendgrid']->send($email);


	
	$ms = new MemSourceClient(LOGIN, PASS);
	// echo realpath($fname);
	$proj = $ms->request('v3/project/create', array('name' => 'test_' > uniqid(), 'sourceLang' => 'da', 'targetLang' => 'en'));
	$job = $ms->request('v6/job/create', array('project' => $proj->id, 'sourceLang' => 'da', 'targetLang' => 'en'), array('file' => '@' . realpath($zip_full_name)));
	
	$ms->request('v3/project/delete', array('project' => $proj->id));
	unlink($zip_full_name);
	rmdir( 'tempfiles/' . $dir );

	$count = 0;
	$singleFiles = [];

	foreach ($job->jobParts as $part) {
		$count += $part->wordsCount;        
		$fn = $part->fileName;
		$singleFiles[$fn] = $part;
	}

	return json_encode(array(
		'count' => $count, 
		'files' => $singleFiles
	));
	
});

$app->post('/files_save', function(Request $req) use ($app) {
	set_time_limit(0);
	
	if (!is_array($req->files->get('file'))) $app->abort('404', 'Nothing to translate');

	$dir = uniqid() . '/';

	$zip_filename = 'upload_' . uniqid() . '.zip';
	$zip_full_name = 'tempfiles/' . $dir . $zip_filename;
	$zip = new PclZip( $zip_full_name );

	foreach( $req->files->get('file') as $file ) {
		handle_file( $zip, $file, $dir );
	}

	return json_encode(array(
		'dir' => $dir,
		'zip' => $zip_filename
	));
	
});

$app->post('/send_file_email', function(Request $req) use ($app) {
	set_time_limit(0);
	if (!is_array($req->files->get('translate_file'))) $app->abort('404', 'Nothing to translate');

	$email = new \SendGrid\Email();
	$email
		->setSubject('TBU new file translation request')
		->addTo('orders@translatedbyus.com')
		->setHtml('Translation request details:
		
		Source language: ' . $req->get('SourceLang') . '
		Target languages: ' . $req->get('TargetLanguages') . '
		Client Name: ' . $req->get('Name') . '
		Client Email: ' . $req->get('Email') . '
		Project Name: ' . $req->get('Notes') . '
		Words Count: ' . $req->get('words') . '
		Cost: ' . $req->get('cost'))
		->addAttachment($zip_name)
	;
	
	$names = array();
	foreach ($req->files->get('translate_file') as $file) {
		$fname = 'upload_' . uniqid() . '.' . $file->getClientOriginalName();
		$file->move('tempfiles/', $fname);
		$fname = 'tempfiles/' . $fname;
		$email->addAttachment($fname);
		$names[] = $fname;
	}
	if(DEV) $email->setTos(array(DEVMAIL));
	$app['sendgrid']->send($email);
	foreach ($names as $name) { 
		unlink($name);
	}
	
	return true;
});

$app->post('/sendMail', function(Request $req) use ($app) {

	$send = false;
	$email = trim(htmlspecialchars($_POST['email']));
	$content = trim(htmlspecialchars($_POST['content']));

	$messageError = 'Mail wasn\'t sent';

	if ( empty($email) && empty($content) ){
		$send = false;
	}else{
		$send = true;

		$email_msg = ( empty($email) ) ? 'Unknown client tried to send order form. ' : 'Client '.$email.' tried to send order form. ' ;
		$content_msg = ( empty($content) )? '' : '<h3>Text to translate:</h3> <p style="font-weight: 100; padding: 20px;">'.$content.'</p>';
		
		$message = $email_msg.'<br />'.$content_msg;
	}

	if ($send){

		$subject = 'Translation request - '.$email_msg;
		$msg_body = '
			<h2><b>'.$email_msg.'<b/></h2>
			'.$content_msg.'
		';

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom('mads@translatedbyus.com')
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}
	
});

$app->post('/sendMailPageTranslate', function(Request $req) use ($app) {

	$send = true;

	$url = trim(htmlspecialchars($_POST['url']));
	$email = trim(htmlspecialchars($_POST['email']));
	$name = trim(htmlspecialchars($_POST['name']));

	$messageError = 'Mail wasn\'t sent';



	if ( empty($url) && empty($email) ){
		$send = false;
	}else{
		$send = true;

		$subject = ( empty($name) ) ? 'Client '.$email.' sent page translation order. ' : $name.'('.$email.') sent page translation order. '  ;
		$msg = '<h2>Page translation request</h2>Client name: <strong>'.$name.'</strong><br />Client email: <strong>'.$email.'</strong><br />Page to translate: <strong>'.$url.'</strong>';
	}

	if ($send){

		$msg_body = $msg;

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom('info@translatedbyus.com')
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);
		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}

	return 'OK';
	
});

$app->post('/sendMailPagePrivate', function(Request $req) use ($app) {

	$send = true;

	$url = trim(htmlspecialchars($_POST['url']));
	$email = trim(htmlspecialchars($_POST['email']));
	$name = trim(htmlspecialchars($_POST['name']));

	$messageError = 'Mail wasn\'t sent';



	if ( empty($url) && empty($email) ){
		$send = false;
	}else{
		$send = true;

		$subject = ( empty($name) ) ? 'Client '.$email.' sent page translation order. ' : $name.'('.$email.') sent page translation order. '  ;
		$msg = '<h2>Page translation request</h2>Client name: <strong>'.$name.'</strong><br />Client email: <strong>'.$email.'</strong><br />Page to translate: <strong>'.$url.'</strong>';
	}

	if ($send){

		$msg_body = $msg;

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom('info@translatedbyus.com')
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}

	return 'OK';
	
});

$app->post('/sendOrderConfirmation', function(Request $req) use ($app) {

	$send = true;

	$email = trim(htmlspecialchars($_POST['email']));
	$name = trim(htmlspecialchars($_POST['name']));

	$messageError = 'Mail wasn\'t sent';



	if ( empty($email) ){
		$send = false;
	}else{
		$send = true;

		$langs = get_langs_array();

		$tolang = array();
		foreach ( $_POST['tolanguage'] as $lang_code ) {
			$tolang[] = $langs[ $lang_code ];
		}

		if ( $_POST['word_price'] == 0 ) {
			$word_price = 'Gratis';
		} else {
			$word_price = 'DKK ' + round( $_POST['word_price'], 2 ) + '/ord';
		}

		$subject = 'Translated By Us: Din ordre';

		$msg = '<div class="row">
					<h2>Projekt</h2>
					<table class="table">
						<tr>
							<th scope="row">Projekt navn</th>
							<td>' . $_POST['project_name'] . '</td>
						</tr>   
						<tr>
							<th scope="row">Navn</th>
							<td>' . $_POST['name'] . '</td>
						</tr>   
						<tr>
							<th scope="row">E-mail</th>
							<td>' . $_POST['email'] . '</td>
						</tr>   
						<tr>
							<th scope="row">Ord i alt</th>
							<td>' . $_POST['count'] . '</td>
						</tr>
						<tr>
							<th scope="row">Forventet levering</th>
							<td>' . $_POST['estimation'] . ' hverdage</td>
						</tr>
						<tr>
							<th scope="row">Kommentar / note</th>
							<td>' . $_POST['notes'] . '</td>
						</tr>
					</table>
				</div>';

		$msg .= '<div class="row">
					<h2>Valgte sprog</h2>
					<table class="table">
						<tr>
							<th scope="row">Fra</th>
							<td>' . $langs[ $_POST['fromlanguage'] ] . '</td>
						</tr>   
						<tr>
							<th scope="row">Til</th>
							<td>' . implode( ', ', $tolang ) . '</td>
						</tr>
					</table>
				</div>';

		$msg .= '<div class="row">
					<h2>Du har valgt</h2>
					<table class="table">
						<tr>
							<th scope="row">Produkt</th>
							<td>' . $_POST['type'] . '</td>
						</tr>   
						<tr>
							<th scope="row">Pris</th>
							<td>' . $word_price . '</td>
						</tr>
						<tr>
							<th scope="row">Betaling</th>
							<td>' . $_POST['paymenttype'] . '</td>
						</tr>';


						if ( $_POST['no_vat'] != 'false' ) {
							$msg .= '<tr>
										<th scope="row"></th>
										<td>Ikke momspligtig</td>
									</tr>';
						}

					$msg .= '</table>
				</div>';

		$msg .= '<div class="row">
					<h2>I alt: DKK ' . round( $_POST['total'], 2 ) . '</h2>
					<p>Total inklusiv Moms: DKK ' . round( ( $_POST['total'] + $_POST['vat'] ), 2 ) . '</p>
				</div>';

	}

	if ($send){

		$msg_body = $msg;

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom('gitta@translatedbyus.com')
			->addTo($email)
			->addBcc('orders@translatedbyus.com')
			->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}

	return 'OK';
	
});


$app->post('/send_preorder', function(Request $req) use ($app) {

	$messageError = 'Mail wasn\'t sent';

	$send = true;

	$langs = get_langs_array();

	$tolang = array();
	foreach ( $_POST['order']['tolanguage'] as $lang_code ) {
		$tolang[] = $langs[ $lang_code ];
	}

	if ( $_POST['order']['word_price'] == 0 ) {
		$word_price = 'Gratis';
	} else {
		$word_price = 'DKK ' + round( $_POST['order']['word_price'], 2 ) + '/ord';
	}

	$subject = 'Ny ordre';

	$msg = '<div class="row">
				<h2>Projekt</h2>
				<table class="table">
					<tr>
						<th scope="row">Projekt navn</th>
						<td>' . $_POST['order']['project_name'] . '</td>
					</tr>   
					<tr>
						<th scope="row">Navn</th>
						<td>' . $_POST['order']['name'] . '</td>
					</tr>   
					<tr>
						<th scope="row">E-mail</th>
						<td>' . $_POST['order']['email'] . '</td>
					</tr>   
					<tr>
						<th scope="row">Ord i alt</th>
						<td>' . $_POST['order']['count'] . '</td>
					</tr>
					<tr>
						<th scope="row">Forventet levering</th>
						<td>' . $_POST['order']['estimation'] . ' hverdage</td>
					</tr>
					<tr>
						<th scope="row">Kommentar / note</th>
						<td>' . $_POST['order']['notes'] . '</td>
					</tr>
				</table>
			</div>';

	$msg .= '<div class="row">
				<h2>Valgte sprog</h2>
				<table class="table">
					<tr>
						<th scope="row">Fra</th>
						<td>' . $langs[ $_POST['order']['fromlanguage'] ] . '</td>
					</tr>   
					<tr>
						<th scope="row">Til</th>
						<td>' . implode( ', ', $tolang ) . '</td>
					</tr>
				</table>
			</div>';

	$msg .= '<div class="row">
				<h2>Du har valgt</h2>
				<table class="table">
					<tr>
						<th scope="row">Produkt</th>
						<td>' . $_POST['order']['type'] . '</td>
					</tr>';

						if ( $_POST['order']['no_vat'] != 'false' ) {
							$msg .= '<tr>
										<th scope="row"></th>
										<td>Ikke momspligtig</td>
									</tr>';
						}

				$msg .= '</table>
			</div>';

	if ($send){

		$msg_body = $msg;

		$zip_full_name = 'tempfiles/' . $_POST['zip']['dir'] . $_POST['zip']['zip'];

		$send_email = new \SendGrid\Email();
		$send_email
		    ->setSubject($subject)
		    ->setFrom('mads@translatedbyus.com')
		    ->addTo('orders@translatedbyus.com')
		    ->setHtml($msg_body)
		    ->addAttachment($zip_full_name)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		unlink( $zip_full_name );
		rmdir( 'tempfiles/' . $_POST['zip']['dir'] );

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}

	return 'OK';
	
});

$app->post('/send_quote', function(Request $req) use ($app) {

	$messageError = 'Mail wasn\'t sent';

	$send = true;

	$langs = get_langs_array();

	$tolang = array();
	foreach ( $_POST['order']['tolanguage'] as $lang_code ) {
		$tolang[] = $langs[ $lang_code ];
	}

	if ( $_POST['order']['word_price'] == 0 ) {
		$word_price = 'Gratis';
	} else {
		$word_price = 'DKK ' + round( $_POST['order']['word_price'], 2 ) + '/ord';
	}

	$subject = 'Quote request';

	$msg = '<div class="row">
				<h2>Projekt</h2>
				<table class="table">
					<tr>
						<th scope="row">Projekt navn</th>
						<td>' . $_POST['order']['project_name'] . '</td>
					</tr>   
					<tr>
						<th scope="row">Navn</th>
						<td>' . $_POST['order']['name'] . '</td>
					</tr>   
					<tr>
						<th scope="row">E-mail</th>
						<td>' . $_POST['order']['email'] . '</td>
					</tr>   
					<tr>
						<th scope="row">Ord i alt</th>
						<td>' . $_POST['order']['count'] . '</td>
					</tr>
					<tr>
						<th scope="row">Forventet levering</th>
						<td>' . $_POST['order']['estimation'] . ' hverdage</td>
					</tr>
					<tr>
						<th scope="row">Kommentar / note</th>
						<td>' . $_POST['order']['notes'] . '</td>
					</tr>
				</table>
			</div>';

	$msg .= '<div class="row">
				<h2>Valgte sprog</h2>
				<table class="table">
					<tr>
						<th scope="row">Fra</th>
						<td>' . $langs[ $_POST['order']['fromlanguage'] ] . '</td>
					</tr>   
					<tr>
						<th scope="row">Til</th>
						<td>' . implode( ', ', $tolang ) . '</td>
					</tr>
				</table>
			</div>';

	if( !empty($_POST['order']['project_text']) ){
		$msg .= '<div class="row">
				<h2>Text to translate</h2>
				<table class="table">
					<tr>
						<td>' . $_POST['order']['project_text'] . '</td>
					</tr>   
				</table>
			</div>';
	}

	$msg .= '<div class="row">
				<h2>Du har valgt</h2>
				<table class="table">
					<tr>
						<th scope="row">Produkt</th>
						<td>' . $_POST['order']['type'] . '</td>
					</tr>';

						if ( $_POST['order']['no_vat'] != 'false' ) {
							$msg .= '<tr>
										<th scope="row"></th>
										<td>Ikke momspligtig</td>
									</tr>';
						}

				$msg .= '</table>
			</div>';

	if ($send){

		$msg_body = $msg;

		$send_email = new \SendGrid\Email();
		$send_email
		    ->setSubject($subject)
		    ->setFrom('mads@translatedbyus.com')
		    ->addTo('orders@translatedbyus.com ')
		    ->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}
});

$app->post('/sendHjemmeside', function(Request $req) use ($app) {

	$send = false;
	$url = trim(htmlspecialchars($_POST['url']));
	$email = trim(htmlspecialchars($_POST['email']));

	$messageError = 'Mail wasn\'t sent';

	if ( empty($email) && empty($url) ){
		$send = false;
	}else{
		$send = true;
	}

	if ($send){

		$subject = 'Translated By Us - Oversæt komplet hjemmeside';
		$msg_body = '
			<p><strong>Hjemmeside:</strong> <a href="' . $_POST['url'] . '">' . $_POST['url'] . '</a></p>
			<p><strong>E-mail addresse:</strong> ' . $_POST['email'] . '</p>
		';

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom('mads@translatedbyus.com')
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}
	
});

$app->post('/sendPrivat', function(Request $req) use ($app) {

	$send = false;

	$messageError = 'Mail wasn\'t sent';

	if ( empty( $req->get('email') ) ){
		$send = false;
	}else{
		$send = true;
	}

	if ($send){

		$langs = get_langs_array();

		$tolang = array();
		foreach ( $req->get('tolanguage') as $lang_code ) {
			$tolang[] = $langs[ $lang_code ];
		}

		$files = $req->get('files');

		$subject = 'Translated By Us - Oversættelse af private dokumenter';
		$msg_body = '
			<p><strong>Name:</strong> ' . $req->get('name') . '</p>
			<p><strong>E-mail addresse:</strong> ' . $req->get('email') . '</p>
			<p><strong>Fra:</strong> ' . $langs[ $req->get('fromlanguage') ] . '</p>
			<p><strong>Til:</strong> ' . implode( ', ', $tolang ) . '</p>
			<p><strong>Instruktions:</strong> ' . nl2br( $req->get('instructions') ) . '</p>
		';

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom( 'mads@translatedbyus.com' )
			->setReplyTo($req->get('email'))
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
			->addAttachment( 'tempfiles/' . $files['dir'] . $files['zip'] )
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		rmdir( 'tempfiles/' . $files['dir'] );

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}
	
});

$app->post('/sendTipsOgTriks', function(Request $req) use ($app) {

	$send = false;

	$messageError = 'Mail wasn\'t sent';

	if ( empty( $req->get('email') ) ){
		$send = false;
	}else{
		$send = true;
	}

	$files = json_decode( $req->get('files') );

	if ($send){

		$subject = 'Translated By Us - Tips og triks';
		$msg_body = '
			<p><strong>E-mail addresse:</strong> ' . $req->get('email') . '</p>
			<p><strong>Indtast tekst:</strong><br>' . nl2br( $req->get('text') ) . '</p>
		';

		$send_email = new \SendGrid\Email();
		$send_email
			->setSubject($subject)
			->setFrom( 'mads@translatedbyus.com' )
			->setReplyTo($req->get('email'))
			->addTo('orders@translatedbyus.com')
			->setHtml($msg_body)
			->addAttachment( 'tempfiles/' . $files->dir . $files->zip )
		;
		if(DEV) $send_email->setTos(array(DEVMAIL));
		$result = $app['sendgrid']->send($send_email);

		//var_dump($result );
		//var_dump($failures);
		return ( $result->code === 200 )? 'Message has sent.':'Mail wasn\'t sent';

	}else{
		return $messageError;
	}
	
});

$app->run();
