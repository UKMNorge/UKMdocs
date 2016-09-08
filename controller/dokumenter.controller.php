<?php

# Entry-point-controller.
require_once(__DIR__ . '/../class/UKMdokumenter.class.php');

$docs = new UKMdokumenter();

# Sett tilgjengelige kategorier:
$TWIGdata['categories'] = $docs->getAllCategories();

# Er kategori valgt?
if(isset($_GET['cat'])) {
	$cat = new stdClass();
	$cat->id = $_GET['cat'];
	$cat->name = $docs->getCategoryName($cat->id);
	$cat->docs = $docs->getAllDocsFromCategory($cat->id);
	$TWIGdata['cat'] = $cat;
}