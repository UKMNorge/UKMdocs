<?php

function parse($attr) {
	// List ut alle dokument i kategori
	if($attr['cat'] != null && $attr['doc'] == null) {
		return buildCategory($attr['cat']);
	}
	// Lenke til ett dokument
	elseif ($attr['doc'] != null) {
		return getDoc($attr['doc']);
	} 
	else return '[ukmdocs]';
}

# Returns HTML
function buildCategory($cat_id) {
	require_once(__DIR__.'/class/UKMdokumenter.class.php');
	$docs = new UKMdokumenter();
	$TWIGdata['docs'] = $docs->getAllDocsFromCategory($cat_id);

	if(count($TWIGdata['docs']) > 1) 
		return TWIG('dokumentListe.html.twig', $TWIGdata, dirname(__FILE__), true);
	return printDoc($TWIGdata['docs'][0]);
}

function getDoc($doc_id) {
	require_once(__DIR__.'/class/UKMdokumenter.class.php');
	$docs = new UKMdokumenter();
	$doc = $docs->getDocument($doc_id);	

	return printDoc($doc);
}

function printDoc($doc) {
	return '<a href="'.$doc->link.'">'.$doc->name.'</a>';
}