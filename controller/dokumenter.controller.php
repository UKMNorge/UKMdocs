<?php

# Entry-point-controller.
require_once(__DIR__ . '/../class/UKMdokumenter.class.php');
$docs = new UKMdokumenter();

# Hvis dette er en lagring:
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$TWIGdata['message'] = handleSave($docs);
}

# Sett tilgjengelige kategorier:
$TWIGdata['categories'] = $docs->getAllCategories();

# Er kategori valgt?
if(isset($_GET['cat'])) {
	$TWIGdata['selected_cat'] = $docs->getCategory($_GET['cat']);
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