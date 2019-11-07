<?php

use UKMNorge\Database\SQL\Query;
use UKMNorge\Database\SQL\Insert;
use UKMNorge\Database\SQL\Update;
use UKMNorge\Database\SQL\Delete;

require_once('UKM/Autoloader.php');

class UKMdokumenter {
	
	public function getCategoryName($cat_id) {
		$sql = new Query("SELECT `name` FROM ukm_docs_categories WHERE id = '#id'", array('id' => $cat_id));
		return $sql->getField();
		#return 'Testnavn';
	}

	# Skal returnere et array med dokumenter.
	public function getAllDocsFromCategory($cat_id, $sort = null) {
		if($sort == 'newest') {
			$sql = new Query("SELECT * FROM ukm_docs 
							WHERE category_id = '#cat_id'
							ORDER BY `created` DESC", array('cat_id' => $cat_id));
		}
		elseif($sort == 'oldest') {
			$sql = new Query("SELECT * FROM ukm_docs 
							WHERE category_id = '#cat_id'
							ORDER BY `created` ASC", array('cat_id' => $cat_id));	
		}
		else {
			$sql = new Query("SELECT * FROM ukm_docs WHERE category_id = '#cat_id'", array('cat_id' => $cat_id));
		}

		$res = $sql->run();
		$docs = array();
		while ($row = Query::fetch($res)) {
			$docs[] = $this->getDocumentData( $row );
		}
		return $docs;
	}

	public function getAllCategories() {
		$sql = new Query("SELECT * FROM `ukm_docs_categories` ORDER BY `name` ASC");
		$res = $sql->run();
		
		$cats = array();

		while($row = Query::fetch($res)) {
			$cat = new stdClass();
			$cat->id = $row['id'];
			$cat->name = $row['name'];
			$cat->shortcode = '[ukmdocs cat="'.$cat->id.'"]';
			$cats[] = $cat;
		}
		
		return $cats;
		#return array();
	}

	public function getCategory($cat_id) {
		$cat = new stdClass();
		$cat->id = $cat_id;
		$cat->name = $this->getCategoryName($cat->id);
		$cat->docs = $this->getAllDocsFromCategory($cat->id);
		$cat->shortcode = '[ukmdocs cat="'.$cat->id.'"]';
		$cat->shortcode_newest = '[ukmdocs cat="'.$cat->id.'" sort="newest"]';

		return $cat;
	}

	public function getDocumentByPublicId( $public_id ) {
		$sql = new Query(
				"SELECT * ".
				"FROM `ukm_docs` ".
				"WHERE `public_id` = '#doc_id'",
			array("doc_id" => $public_id)
		);
		$res = $sql->run('array');
		
		return $this->getDocumentData( $res );
	}

	public function getDocument($doc_id) {
		$sql = new Query(
				"SELECT * ".
				"FROM `ukm_docs` ".
				"WHERE `id` = '#doc_id'",
			array("doc_id" => $doc_id)
		);
		$res = $sql->run('array');
		
		return $this->getDocumentData( $res );
	}
	
	public function getDocumentData( $res ) {
		$doc = new stdClass();
		$doc->id = $res['id'];
		$doc->name = $res['name'];
		$doc->upload_id = $res['upload_id'];
		$doc->file = str_replace('http:','https:', $res['url']); # Vi har URLer med Ø i...
		$doc->link = '//dokument.'. UKM_HOSTNAME .'/'.$res['public_id'].'/';
		$doc->category_id = $res['category_id'];
		$doc->shortcode = '[ukmdocs doc="'.$doc->id.'"]';
		$doc->public_id = $res['public_id'];

		return $doc;
	}

	// SQL-strukturen er som følger:
	// Tabell: ukm_docs_categories
	// id INT
	// name VARCHAR(255) NOT NULL
	public function addCategory($id = null, $name) {
		if (null != $id) 
			$sql = new Update('ukm_docs_categories', array('id' => $id));
		else 
			$sql = new Insert('ukm_docs_categories');
		$sql->add('name', $_POST['cat_name']);
		$res = $sql->run();

		if($res == 1) {
			$message = new stdClass();
			$message->level = 'success';
			$message->header = 'La til kategorien '.$_POST['cat_name'].'.';
		} else {
			$message = new stdClass();
			$message->level = 'danger';
			$message->header = 'Klarte ikke å legge til kategorien '.$_POST['cat_name'].'.';
		}

		return $message;
	}

	// Params: 
	// 	Filnavn (Typ. "Styreprotokoll september 2016")
	//	Upload-ID - Wordpress Media Uploader-ID
	//	Category_id - ID på kategorien filen skal tilhøre.
	// 	
	// SQL-strukturen er som følger:
	// Tabell: ukm_docs
	// id int
	// name varchar NOT NULL
	// upload_id int NOT NULL
	// category_id int NOT NULL
	public function addDocument($id = null, $name, $upload_id, $category_id) {
		if($id != null) {
			$sql = new Update('ukm_docs', array('id' => $id));
		} else {
			$sql = new Insert('ukm_docs');
			$sql->add('public_id', substr(md5(uniqid(mt_rand(), true)), 0, 8) );
		}
		$sql->add('name', $name);
		$sql->add('upload_id', $upload_id);
		$sql->add('category_id', $category_id);
		$sql->add('url', wp_get_attachment_url($upload_id));
	
		$res = $sql->run();
		if($res == 1 || $sql->error == null) {
			$message = new stdClass();
			$message->level = 'success';
			$message->header = 'Lagret dokumentet '.$name.'.';
		} else {
			$message = new stdClass();
			$message->level = 'danger';
			$message->header = 'Klarte ikke å legge til dokumentet '.$name.'.';
		}

		return $message;
	}

	public function deleteDocument($id) {
		$qry = new Delete('ukm_docs', array('id' => $id));
		#echo $qry->debug();
		$res = $qry->run();
		return $res;
	}

	public function deleteCategory($id) {
		$qry = new Delete('ukm_docs_categories', array('id' => $id));
		#echo $qry->debug();
		$res = $qry->run();
		return $res;
	}
}
