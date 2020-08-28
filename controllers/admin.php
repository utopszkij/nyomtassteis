<?php
class AdminController extends Controller {
	public function adminPanel(array $params = []) {
		$this->view->display('adminpanel');	
	} 
}
?>