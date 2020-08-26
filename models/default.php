<?php
class DefaultModel extends Model {
	public function getItems() {
		 global $wpdb;
		 return $wpdb->get_results('show tables');
	}
}
?>