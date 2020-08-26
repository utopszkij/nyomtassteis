<?php
class DefaultController extends Controller {
	public function default() {
		$this->LNG->token1 = 'szöveg1';
		$this->title = __('teszt_plugin_title.',AREAMANAGER);
	 	$this->w = $this->model->getItems();
		$this->view->display('default');	
	} 
}
?>