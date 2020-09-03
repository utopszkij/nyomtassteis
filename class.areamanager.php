<?php
	/**
	* wooCommerce category extend for area managment
	*/
    include_once __DIR__.'/model.php';
	class Area extends AreaModel {
	    public $gApiKey;
	    
		// php object constructor
		function __construct() {
		    $this->create();
		}
		
		// php object destructor
		function __desruct() {
		    $this->kill();
		}
		
		// main constructor
		public function create() {
		    $this->gApiKey = get_option('areamanager_gApiKey', null);
		}
		
		// insert, modify, remove implemented in model.php
		
		// main objecct destructor
		public function  kill () {
		}
		
		/**
		 * erase object from database
		 */
		public function erase() {
		    $this->remove();
		}
		
		/**
		 * init propertys for insert
		 */
		public function init() {
		    $this->id = 0;
		    $this->name = '';
		    $this->description = '';
		    $this->parent = 0;
		    $this->type = '';
		    $this->center = '';
		    $this->population = 0;
		    $this->place = 0.0;
		    $this->poligon = '[]';
		    $this->enableStart = '';
		    $this->enableEnd = '';
		}
		
		/**
         * move object
		 */
		public function move() {
		    echo 'not impelemted';
		}
		
		/**
         * select object  
		 */
		public function select() {
		    echo 'not impelemted';
		}
		
		/**
         * deselect object
		 */
		public function deselect() {
		    echo 'not impelemted';
		}
		
		/**
         * sort object   
		 */
		public function sort() {
		    echo 'not impelemted';
		}

		// ================= Display methods ====================
		/**
		 * echo add complate add from or only form controls for meta fields
		 * @param bool $onlyMetas
		 */
		public function addForm(bool $onlyMetas) {
		    if ($onlyMetas) {
		        $this->display('add_form_metas');
		    } else {
		        echo 'not yet programmed'; exit();
		    }
		}
		
		/**
		 * echo add complate add from or only form controls for meta fields
		 * @param bool $onlyMetas
		 */
		public function editForm(bool $onlyMetas) {
		    if ($onlyMetas) {
		        // $this->display('edit_form_metas');
		        $this->display('add_form_metas');
		    } else {
		        echo 'not yet programmed'; exit();
		    }
		}
		
		/**
		 * echo admin form
		 */
		public function adminPanel() {
		    $task = filter_input(INPUT_POST, 'task');
		    if ($task == 'setupSave') {
		        $this->setupSave();
		    } else {
		      $this->display('adminform');
		    }
		}
		
		/**
		 * process adminpanel 
		 */
		public function setupSave() {
		    $this->gApiKey = filter_input(INPUT_POST, 'gApiKey');
		    update_option('areamanager_gApiKey', $this->gApiKey);
		    ?>
		    <h1>AREAMANGER SETUP</h1>
		    <div class="alert alert-succes"><?php echo __('data_saved',AREAMANAGER); ?></div>
		    <p>google API key:<?php echo get_option('areamanager_gApiKey', null); ?></p>
		    <?php
		}
		
		// ===========================================
		
		/**
         * hide object
		 */
		public function hide() {
		    echo 'not impelemented';
		}
		
        // ============= protected methods =========== 		
		
        /**
         * include html template
         * find first in templateDir/areamanager/htmls
         *      second in plugindir/htmls
         * @param string $tmplName
         */
		protected function display(string $tmplName) {
		    $tmplDir = get_template_directory();
		    if (file_exists($tmplDir.'/'.AREAMANAGER.'/htmls/'.$tmplName.'.php')) {
		        $path = $tmplDir.'/'.AREAMANAGER.'/htmls';
		    } else {
		        $path = __DIR__.'/htmls';
		    }
		    include ($path.'/'.$tmplName.'.php');
		}
	} // class
?>