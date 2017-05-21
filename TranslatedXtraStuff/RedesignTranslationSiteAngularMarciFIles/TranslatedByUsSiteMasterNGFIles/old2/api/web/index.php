<?php
define('LOGIN', 'caezaris');
define('PASS', '268W0Nop');

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

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();


$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['debug'] = true;
$app['swiftmailer.options'] = array(
    'auth_mode' => null
);
$app['swiftmailer.transport'] = new \Swift_MailTransport();
$app['swiftmailer.use_spool'] = false;

$app->post('/file_word_count', function(Request $req) use ($app) {
    set_time_limit(0);
    
    if (!is_array($req->files->get('file'))) $app->abort('404', 'Nothing to translate');
    if (!$req->get('from') || !$req->get('to')) $app->abort('404', 'Select languages');
    
    $zname = 'tempfiles/upload_' . uniqid() . '.zip';
    $zip = new PclZip($zname);
    
    $files = array();
    foreach ($req->files->get('file') as $file) {
        $origName = preg_replace('/[^\x20-\x7E]/', '_', $file->getClientOriginalName()); 
        $fname = 'upload_' . uniqid() . '.' . $origName;
        $files[$fname] = $file->getClientOriginalName();
        $files[$fname]['orgName'] = $file->getClientOriginalName();
        $file->move('tempfiles/', $fname);
        $fname = 'tempfiles/' . $fname;
        $zip->add($fname, PCLZIP_OPT_REMOVE_PATH, 'tempfiles');
        unlink($fname);
    }


    /*
    * WPS: Sending file via email */
    $zip_name = ($zip->zipname);
    $message_to_send = \Swift_Message::newInstance()
        ->setSubject('New Client tried to send order form.')
        ->setFrom(array('mads@translatedbyus.com'))
        ->setTo(array('gitta@translatedbyus.com'))
        ->setBody('<p style="font-weight: 100; padding: 20px;">User have added file to translate, which was attached to the message.</p>', 'text/html')
        ->attach(Swift_Attachment::fromPath($zip_name));
    $result = $app['mailer']->send($message_to_send);

    
    $ms = new MemSourceClient(LOGIN, PASS);
    echo realpath($fname);
    $proj = $ms->request('v3/project/create', array('name' => uniqid(), 'sourceLang' => $req->get('from'), 'targetLang' => $req->get('to')));
    $job = $ms->request('v6/job/create', array('project' => $proj->id, 'sourceLang' => $req->get('from'), 'targetLang' => $req->get('to')), array('file' => '@' . realpath($zname)));
    
    $ms->request('v3/project/delete', array('project' => $proj->id));
    unlink($zname);

    $count = 0;
    $singleFiles = [];
    foreach ($job->jobParts as $part) {
        $count += $part->wordsCount;        
        $fn = $part->fileName;
        $singleFiles[$fn] = $part;
        $fn = substr($fn, strpos($fn, '.') + 1);        
        $partFileName = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $part->fileName);
        if ($part->wordsCount > 0) unset($files[json_decode('"' . $part->fileName . '"')]);
    }
    return json_encode(array(
        'count' => $count, 
        'skipped' => array_values($files),
        'files' => $singleFiles
    ));
});

$app->post('/send_file_email', function(Request $req) use ($app) {
    set_time_limit(0);
    if (!is_array($req->files->get('translate_file'))) $app->abort('404', 'Nothing to translate');

    $message = \Swift_Message::newInstance()
        ->setSubject('TBU new file translation request')
        //->setFrom(array('info@translatedbyus.com'))
        //->setTo(array('tadk1234@gmail.com', 'sven.kannler@gmail.com'))
        ->setTo(array('daniel.garde@gmail.com'))
        ->setBody('Translation request details:
        
        Source language: ' . $req->get('SourceLang') . '
        Target languages: ' . $req->get('TargetLanguages') . '
        Client Name: ' . $req->get('Name') . '
        Client Email: ' . $req->get('Email') . '
        Project Name: ' . $req->get('Notes') . '
        Words Count: ' . $req->get('words') . '
        Cost: ' . $req->get('cost'));
    
    $names = array();
    foreach ($req->files->get('translate_file') as $file) {
        $fname = 'upload_' . uniqid() . '.' . $file->getClientOriginalName();
        $file->move('tempfiles/', $fname);
        $fname = 'tempfiles/' . $fname;
        $message->attach(\Swift_Attachment::fromPath($fname));
        $names[] = $fname;
    }
    
    $app['mailer']->send($message);
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

        $message_to_send = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array('mads@translatedbyus.com'))
            ->setTo(array('gitta@translatedbyus.com'))
            ->setBody($msg_body, 'text/html');

        $result = $app['mailer']->send($message_to_send,$failures);
        //var_dump($result );
        //var_dump($failures);
        return ( $result != 0)? 'Message has sent.':'Mail wasn\'t sent';

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

        $message_to_send = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array('info@translatedbyus.com'))
            ->setTo(array('mads@translatedbyus.com'))
            ->setBody($msg_body, 'text/html');

        $result = $app['mailer']->send($message_to_send,$failures);
        //var_dump($result );
        //var_dump($failures);
        return ( $result != 0)? 'Message has sent.':'Mail wasn\'t sent';

    }else{
        return $messageError;
    }

    return 'OK';
    
});

$app->run();
