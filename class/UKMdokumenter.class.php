<?php

class UKMdokumenter {
	
	// TODO: Implement
	public function getCategoryName($cat_id) {

	}

	// TODO: Implement
	# Skal returnere et array med dokumenter.
	public function getAllDocsFromCategory($cat_id) {

	}

	// TODO: Implement
	public function getAllCategories() {
		$cat = new stdClass();
		$cat->id = 1;
		$cat->name = "Testkategori";
		return array($cat);
		#return array();
	}

	// TODO: Implementer
	// SQL-strukturen er som følger:
	// Tabell: UKMdocs_categories
	// id INT
	// name VARCHAR(255)
	public function addCategory($name) {

	}

	// TODO: Implementer
	// Params: 
	// 	Filnavn (Typ. "Styreprotokoll september 2016")
	//	Upload-ID - Wordpress Media Uploader-ID
	//	Category_id - ID på kategorien filen skal tilhøre.
	// 	
	// SQL-strukturen er som følger:
	// Tabell: UKMdocs_documents
	// id int
	// name varchar
	// upload_id int
	// category_id int
	public function addDocument($name, $upload_id, $category_id) {

	}
}