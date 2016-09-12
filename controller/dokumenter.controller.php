<?php

# Entry-point-controller.
require_once(__DIR__ . '/../class/UKMdokumenter.class.php');
$docs = new UKMdokumenter();

# Hvis dette er en lagring:
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$TWIGdata['message'] = handleSave($docs);
}

# Hvis dette er en sletting:
if(isset($_POST['delete']) && $_POST['delete'] == 'true' && isset($_POST['doc'])) {
	echo 'Deleting...';
	$TWIGdata['message'] = deleteDocument($docs, $_POST['cat'], $_POST['doc']);
}

# Sett tilgjengelige kategorier:
$TWIGdata['categories'] = $docs->getAllCategories();

# Er kategori valgt?
if(isset($_GET['cat'])) {
	$TWIGdata['selected_cat'] = $docs->getCategory($_GET['cat']);
	if(isset($_GET['doc'])) {
		$TWIGdata['selected_doc'] = $docs->getDocument($_GET['doc']);
	}
}

function handleSave($docs) {
	// Nytt dokument:
	if(isset($_POST['doc_name'])) {
		$id = $_POST['doc_id']; // For edits
		$name = $_POST['doc_name'];
		$upload_id = $_POST['upload_id'];
		$category_id = $_GET['cat'];
		return $docs->addDocument($id, $name, $upload_id, $category_id);
	}
	// Ny kategori
	elseif(isset($_POST['cat_name'])) {
		return $docs->addCategory($_POST['cat_id'], $_POST['cat_name']);
	}
}

function deleteDocument($docs, $cat_id, $doc_id) {
	$doc = $docs->getDocument($doc_id);
	$message = new stdClass();
	if($doc->category_id != $cat_id) {
		$message->level = 'danger';
		$message->header = "Kan ikke slette dokumentet!";
		return $message;
	}

	$result = $docs->deleteDocument($doc_id);
	if($result) {
		$message->level = 'success';
		$message->header = "Slettet dokumentet '".$doc->name."'!";
		return $message;
	}
	$message->level = 'danger';
	$message->header = "Klarte ikke å slette dokumentet!";
	return $message;

}