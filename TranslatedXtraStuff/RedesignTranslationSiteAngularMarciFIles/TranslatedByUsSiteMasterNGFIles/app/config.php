<?php
/**
 * Config file
 */

$braintree = array(
	'environment' => 'production',
	'merchantId'  => 'b2tzy7mr3kmhjf6q',
	'publicKey'   => '5364dd7dxdp9tmkw',
	'privateKey'  => 'ef21af266cf51ceaf68e8b62b87e6c22'
);

$translation_prices = array(
	'machine'  => 0,
	'basic'    => 0.79,
	'business' => 0.99
);

$routing = array(
	'/' => 'index.html',
	'/bestil-oversaettelse' => 'bestil.html',
	'/oversaet-hjemmeside' => 'hjemmeside.html',
	'/om-os' => 'om-os.html',
	'/ordrebekraeftelse' => 'ordrebekraeftelse.html',
	'/privatlivspolitik' => 'privatlivspolitik.html',
	'/oversaettelsesdata' => 'oversaettelsesdata.html',
	'/oversaettere' => 'oversaettere.html',
	'/validering' => 'validering.html',
	'/privat' => 'privat.html',
	'/tips-og-triks' => 'tips-og-triks.html',
);

$meta_tags = array(
	'/' => array(
		'title' => 'Professionelt og billigt oversættelsesbureau.',
		'description' => 'Oversættelsesbureau - Få oversat din tekst eller hjemmeside i dag! Vi tilbyder hurtig & billig oversættelse uden minimumsgebyr og til 79 øre pr. ord.'
	),
	'/bestil-oversaettelse' => array(
		'title' => 'Bestil oversættelse af din tekst eller hjemmeside allerede i dag.',
		'description' => 'Bestil oversættelse af din tekst med det samme. Vi kan oversætte mere end 800 sprogkombinationer hurtigt og billigt.'
	),
	'/oversaet-hjemmeside' => array(
		'title' => 'Professionel Oversættelse af Hjemmeside | TranslatedByUs',
		'description' => 'Oversættelse af hjemmeside – Få et nyt sprog tilføjet din hjemmeside nemt og hurtigt.'
	),
	'/om-os' => array(
		'title' => 'Om os - Hvem er vi? Hvad kan vi? | TranslatedByUs',
		'description' => 'Hos Translated By Us har vi gjort det nemt for dig, at få oversat din tekst. Du skal blot uploade dit dokument eller indtaste din tekst og bekræfte ordre.'
	),
	'/ordrebekraeftelse' => array(
		'title' => 'Din ordre',
		'description' => ''
	),
	'/privatlivspolitik' => array(
		'title' => 'Handelsbetingelser & Privatlivspolitik | TranslatedByUs',
		'description' => 'Vi har gjort vores handelsbetingelser og privathedspolitik tilgængelig for alle, så intet er overladt til tilfældighederne. Læs vores handelsbetingelser.'
	),
	'/validering' => array(
		'title' => 'Mulighed for Gennemlæsning ved Validering | TranslatedByUs',
		'description' => ': Vær med til at gøre den gode oversættelse endnu bedre med validering. I får mulighed for at gennemlæse oversættelsen, så den indeholder de rigtige termer.'
	),
	'/oversaettelsesdata' => array(
		'title' => 'De mest bestilte oversættelser illustreret | TranslatedByUs',
		'description' => 'De mest bestilte oversættelser illustreret | TranslatedByUs'
	),
	'/oversaettere' => array(
		'title' => 'Et lille udvalg af Vores Bedste Oversættere | TranslatedByUs',
		'description' => 'Vores oversættere er eksperter indenfor ét eller flere felter. Fælles for dem alle er en stærk interesse for det skrevne sprog. Læs mere om dem her.'
	),
	'/privat' => array(
		'title' => 'Translated By Us - Privat.',
		'description' => 'Oversættelse til private'
	),
	'/tips-og-triks' => array(
		'title' => 'Tips og tricks til en god oversættelse | Translated By Us',
		'description' => 'Tips og tricks til en god oversættelse | Translated By Us'
	),
	'/404' => array(
		'title' => 'Translated By Us - 404',
		'description' => ''
	)
);

$langs = array(
	array( 
		'id' => 'en',
		'name' => 'Engelsk'
	),
	array( 
		'id' => 'da',
		'name' => 'Dansk'
	),
	array( 
		'id' => 'no',
		'name' => 'Norsk'
	),
	array( 
		'id' => 'sv',
		'name' => 'Svensk'
	),
	array( 
		'id' => 'fi',
		'name' => 'Finsk'
	),
	array( 
		'id' => 'nl',
		'name' => 'Hollandsk'
	),
	array( 
		'id' => 'fr',
		'name' => 'Fransk'
	),
	array( 
		'id' => 'de',
		'name' => 'Tysk'
	),
	array( 
		'id' => 'ar',
		'name' => 'Arabisk'
	),
	array( 
		'id' => 'hy',
		'name' => 'Armensk'
	),
	array( 
		'id' => 'be',
		'name' => 'Hviderussisk'
	),
	array( 
		'id' => 'bn',
		'name' => 'Bengali'
	),
	array( 
		'id' => 'bs',
		'name' => 'Bonisk'
	),
	array( 
		'id' => 'bg',
		'name' => 'Bulgarsk'
	),
	array( 
		'id' => 'my',
		'name' => 'Burmesisk'
	),
	array( 
		'id' => 'ca',
		'name' => 'Catalansk'
	),
	array( 
		'id' => 'zh',
		'name' => 'Kinesisk (Kina & Singapore)'
	),
	array( 
		'id' => 'zh_hk',
		'name' => 'Kinesisk (Hong Kong Taiwan)'
	),
	array( 
		'id' => 'hr',
		'name' => 'Kroatisk'
	),
	array( 
		'id' => 'cs',
		'name' => 'Tjekkisk'
	),
	array( 
		'id' => 'en_gb',
		'name' => 'Engelsk (Storbritanien)'
	),
	array( 
		'id' => 'en_us',
		'name' => 'Engelsk(United States)'
	),
	array( 
		'id' => 'et',
		'name' => 'Estisk'
	),
	array( 
		'id' => 'fo',
		'name' => 'Færøsk'
	),
	array( 
		'id' => 'fa',
		'name' => 'Farsi'
	),
	array( 
		'id' => 'ka',
		'name' => 'Georgisk'
	),
	array( 
		'id' => 'el',
		'name' => 'Græsk'
	),
	array( 
		'id' => 'he',
		'name' => 'Hebræisk'
	),
	array( 
		'id' => 'hi',
		'name' => 'Hindi'
	),
	array( 
		'id' => 'hu',
		'name' => 'Ungarsk'
	),
	array( 
		'id' => 'is',
		'name' => 'Islandsk'
	),
	array( 
		'id' => 'id',
		'name' => 'Indonesisk'
	),
	array( 
		'id' => 'ga',
		'name' => 'Irsk'
	),
	array( 
		'id' => 'it',
		'name' => 'Italiensk'
	),
	array( 
		'id' => 'ja',
		'name' => 'Japansk'
	),
	array( 
		'id' => 'ko',
		'name' => 'Koreansk'
	),
	array( 
		'id' => 'ku',
		'name' => 'Kurdisk'
	),
	array( 
		'id' => 'lv',
		'name' => 'Lettisk'
	),
	array( 
		'id' => 'lt',
		'name' => 'Litauisk'
	),
	array( 
		'id' => 'mk',
		'name' => 'Makedonsk'
	),
	array( 
		'id' => 'mt',
		'name' => 'Maltesisk'
	),
	array( 
		'id' => 'mn',
		'name' => 'Mongolsk(Kyrillisk)'
	),
	array( 
		'id' => 'ne',
		'name' => 'Nepalesisk'
	),
	array( 
		'id' => 'ps',
		'name' => 'Pashto'
	),
	array( 
		'id' => 'pl',
		'name' => 'Polsk'
	),
	array( 
		'id' => 'pt_br',
		'name' => 'Portugisisk (Brasilien)'
	),
	array( 
		'id' => 'pt_pt',
		'name' => 'Portugisisk (Portugal)'
	),
	array( 
		'id' => 'ro',
		'name' => 'Rumænsk'
	),
	array( 
		'id' => 'ru',
		'name' => 'Russisk'
	),
	array( 
		'id' => 'sr',
		'name' => 'Serbisk'
	),
	array( 
		'id' => 'sk',
		'name' => 'Slovakisk'
	),
	array( 
		'id' => 'sl',
		'name' => 'Slovensk'
	),
	array( 
		'id' => 'so',
		'name' => 'Somali'
	),
	array( 
		'id' => 'es',
		'name' => 'Spansk'
	),
	array( 
		'id' => 'syr',
		'name' => 'Syrisk'
	),
	array( 
		'id' => 'tl',
		'name' => 'Tagalog'
	),
	array( 
		'id' => 'th',
		'name' => 'Thai'
	),
	array( 
		'id' => 'tr',
		'name' => 'Tyrkisk'
	),
	array( 
		'id' => 'uk',
		'name' => 'Ukrainsk'
	),
	array( 
		'id' => 'ur',
		'name' => 'Urdu'
	),
	array( 
		'id' => 'vi',
		'name' => 'Vietnamesisk'
	)
);
