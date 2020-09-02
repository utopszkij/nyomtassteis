<?php
	/**
	* wooCommerce category extension for area managment
	*/
	class AreaModel {
	    // ids
		public $id = 0;	
		public $name = '';
		public $center = '';
		public $type = '';	
		public $enableStart = ''; 
		public $enableEnd = '';	
		
		// state
		// not implemented in this object
		
		// other
		public $description = '';	
		public $population = 0;	
		public $place = 0.0;	
		public $poligon = '';
		
		// tree structure
		public $parent = 0;
				
		// components
		// not child components in this object
		
		// logs
		// not logs for this object
				
		protected $metaFields = ['type','center','population','place','poligon','enableStart','enableEnd']; 
		
		/**
		 * read data from database
		 * @param int $id
		 * @return bool
		 */
		public function read(int $id):bool {
		    $result = false;
		    $w = get_term_by('term_id', $id, 'product_cat');
		    if ($w) {
		        $this->id = $w->term_id;
		        $this->name = $w->name;
		        $this->description = $w->description;
		        $this->parent = $w->parent;
		        foreach ($this->metaFields as $metaField) {
		            $this->$metaField = get_term_meta($id, $metaField, true);
		        }
		        $result = true;
		    }
		    return $result;
		}
		
		/**
		 * save data into database
		 * @return bool
		 */
		public function insert(): bool {
		    $result = false;
		    $w = wp_insert_term($this->name, 'product_cat', [
		        "description" => $this->description,
		        "parent" => $this->parent
		    ]);
		    if (isset($w['term_id'])) {
		        $this->id = $w['term_id'];
		        $result = true;
		        foreach ($this->metaFields as $metaField) {
		           if (!add_term_meta($this->id, $metaField, $this->$metaField)) {$result = false;}
                }
		    }
		    return $result;
		}
		
		/**
		 * update data in database
		 * @return bool
		 */
		public function modify(): bool {
		    $result = false;
		    $w = wp_update_term($this->id, 'product_cat', [
		        "name" => $this->name,
		        "description" => $this->description,
		        "parent" => $this->parent
		    ]);
		    if (isset($w['term_id'])) {
		        $this->id = $w['term_id'];
		        $result = true;
		        foreach ($this->metaFields as $metaField) {
    		        if (!update_term_meta($this->id, $metaField, $this->$metaField)) {$result = false;}
		        }
		    }
		    return $result;
		}
		
		/**
		 * delete data from database
		 * @return bool
		 */
		public function remove(): bool {
		    $result = false;
		    if (wp_delete_term($this->id, 'product_cat')) {
		        $result = true;
		        foreach ($this->metaFields as $metaField) {
		          if (!delete_term_meta($this->id, $metaField)) {$result = false;}
		        }
		    }
		    return $result;
		}
	}
?>