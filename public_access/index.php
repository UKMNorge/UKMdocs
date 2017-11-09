<?php
error_reporting(E_ALL);
ini_set('display_errors',true);

require_once( __DIR__ . '/../class/UKMdokumenter.class.php');
$docs = new UKMdokumenter();
require_once('UKM/inc/twig-admin.inc.php');
$TWIGdata = [];

try {
	if( isset( $_GET['id'] ) ) {
		$doc = $docs->getDocumentByPublicId( $_GET['id'] );
		if( is_object( $doc ) && isset( $doc->file ) ) {
			header("Location: ". $doc->file);
			exit();
		}
	}
	throw new Exception('Klarte ikke å finne dokumentet du leter etter!');

} catch( Exception $e ) {
	$TWIGdata['error'] = $e->getMessage();
	echo TWIG( 'error.html.twig', $TWIGdata, dirname( dirname(__FILE__) ), true);
}