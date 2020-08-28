<?php
// szükség van AREAMANAGER, AREAMANAGER_PLUGINPATH, AREAMANAGER_PLUGINURL konstansra

/**
 * adatmodel root class, minde modell ennek leszármazottja
 * @author utopszkij
*/
class Model {
	protected $modelName;
	protected $controller;
	protected $db;

	/**
	 * model object constructor
	 * @param string $modelName
	 * @param Controller $controller
	 */
	function __construct(string $modelName, $controller) {
		global $wpdb;
		$this->modelName = $modelName;
		$this->controller = $controller;
		$this->db = $wpdb;
	}
}

/**
 * viewer root class, minden cviewer class ennekleszármazottja
 * @author utopszkij
*/
class View {
	// jQuery és angulár nélküli változat
	protected $viewName;
	protected $controller;
	
	/**
	 * view object contructor
	 * @param string $modelName
	 * @param Controller $controller
	 */
	function __construct(string $modelName, $controller) {
		$this->modelName = $modelName;
		$this->controller = $controller;
	}
	/**
	 * template html megjelenítése
	 * @param string $tmplName
	 */
	public function display(string $tmplName) {
		  $tmplDir = get_template_directory();
		  $htmlName = $this->controller->ROOTURL.'/wp-content/plugins/'.AREAMANAGER.'/views/htmls/'.$tmplName.'.html';
		  $jsName = $this->controller->ROOTURL.'/wp-content/plugins/'.AREAMANAGER.'/js/'.AREAMANAGER.'.js';
        ?>
        <!-- angulárJs változathoz div ng-app="app "id ="areamanager_page">
         	<div ng-controller="ctrl" id="areamanager_scope" style="display:none" -->
        <div id ="areamanager_page">
         	<div  id="areamanager_scope">
					<?php         	   
         	   $path = $this->controller->MYPATH.'/views/htmls';
         	   if (file_exists($tmplDir.'/'.$tmplName.'.html')) {
						$path = $tmplDir;         	   
         	   } else if (file_exists($tmplDir.'/'.AREAMANAGER.'/'.$tmplName.'.html')) {
						$path = $tmplDir.'/'.AREAMANAGER;         	   
         	   }	
         		include $path.'/'.$tmplName.'.html'; 
         		?>
         	</div>
        </div>
        <?php if (file_exists($this->controller->MYPATH.'/js/'.AREAMANAGER.'.js')) : ?>
        		<script src="<?php echo $jsName; ?>"></script>
        <?php endif; ?>
        <!-- angularJs -hez  script src="https://code.angularjs.org/1.7.8/angular.js"></script -->
        <script type="text/javascript">
              var $scope = {};
              /* angularJs -hez 
		        angular.module("app", []).controller("ctrl", function($scope) {
		        	 // angular page onload
		          <?php 
		            // paraméterek átadása az angularjs -nek
						foreach ($this->controller as $fn => $fv) {
							if (is_string($fv)) {
								echo '$scope.'.$fn.' = '.JSON_encode($fv).";\n";
							} else if (is_numeric($fv)) {
								echo '$scope.'.$fn.' = '.$fv.";\n";
							} else if (is_bool($fv)) {
								if ($fv) {
									echo '$scope.'.$fn.' = true;'.";\n";
								} else {
									echo '$scope.'.$fn.' = false;'.";\n";
								}
							} else {
								echo '$scope.'.$fn.' = '.JSON_encode($fv).";\n";
							}							
						}		        	 
		        	 ?>
		        	 // saját pageOnlad funkció hívása
		        	 jQuery('body').ready(function() {
		        	 	if (pageOnLoad != undefined) {
		        	 		pageOnLoad($scope);
		        	 	}	
		        	 	jQuery('#scope').show();
		        	 });	
 				  });
 				  */
		        <?php 
		            // paraméterek átadása a js -nek
						foreach ($this->controller as $fn => $fv) {
							if (is_string($fv)) {
								echo '$scope.'.$fn.' = '.JSON_encode($fv).";\n";
							} else if (is_numeric($fv)) {
								echo '$scope.'.$fn.' = '.$fv.";\n";
							} else if (is_bool($fv)) {
								if ($fv) {
									echo '$scope.'.$fn.' = true;'.";\n";
								} else {
									echo '$scope.'.$fn.' = false;'.";\n";
								}
							} else {
								echo '$scope.'.$fn.' = '.JSON_encode($fv).";\n";
							}							
						}		        	 
		        ?>
 				  pageOnLoad(document.getElementById($scope);
      	</script>
		  <?php		
	}
}

/**
 * controller root class, minden controller ennek leszármazottja
 * @author utopszkij
 */
class Controller {
	protected $controllerName;
	protected $model = false;
	protected $view = false;
	
	/**
	 * controller object constructor
	 * @param string $option  - álltalában a controller neve
	 */
	function __construct(string $option = 'option') {
	    $this->controllerName = $option;
		$this->loggedUser = new stdClass();
		$this->loggedUser->ID = 0;
		$this->ROOTURL = get_site_url();
		$this->MYURL = AREAMANAGER_PLUGINURL;
		$this->MYPATH = AREAMANAGER_PLUGINPATH;
		 if ( is_user_logged_in() ) {
		    $this->loggedUser = wp_get_current_user();
		    // ID, user_login, .....
		}
		foreach ($_SESSION as $fn => $fv) {
			$this->$fn = $fv;
		}
		foreach ($_GET as $fn => $fv) {
			$this->$fn = $fv;
		}
		foreach ($_POST as $fn => $fv) {
			$this->$fn = $fv;
		}
	    $this->model = $this->getModel($this->controllerName);
		$this->view = $this->getView($this->controllerName);
	}
	
	/**
	 * model betöltése és példányosítása, ha nincs ilyen file akkor result=false
	 * @param string $modelName
	 * @return Model|boolean
	 */
	public function getModel(string $modelName) {
		if (file_exists(AREAMANAGER_PLUGINPATH.'/models/'.$modelName.'.php')) {
			include_once AREAMANAGER_PLUGINPATH.'/models/'.$modelName.'.php';
			$modelName = ucfirst($modelName).'Model';
			$result = new  $modelName ($modelName, $this);
		} else {
			$result = false;
		}	
		return $result;
	}

	/**
	 * viewer betöltése és példányosítása, ha nincs ilyen file akkor result=false
	 * @param string $viewName
	 * @return View|boolean
	 */   
	public function getView(string $viewName) {
		if (file_exists(AREAMANAGER_PLUGINPATH.'/views/'.$viewName.'.php')) {
			include_once AREAMANAGER_PLUGINPATH.'/views/'.$viewName.'.php';
			$viewName = ucfirst($viewName).'View';
			$result = new  $viewName ($viewName, $this);
		} else {
			$result = false;
		}	
		return $result;
	}
	
	
	/**
	* adat olvasás sessionból
	* @param string $name
	* @param mixed $defValue
	* @return mixed
	*/	
	public function sessionGet(string $name, $default = '') {
		if (isset($_SESSION[$name])) {
			$default = $_SESSION[$name];
		} 
		return $default;
	}


	/**
	* adat irás sessionba
	* @param string $name
	* @param mixed $value
	* @return void
	*/	
	public function sessionSet(string $name, $value) {
		$_SESSION[$name] = $value;
	}
	
	/**
	* adat olvasás GET vagy POST -ból
	* @param string $name
	* @param mixed $defValue
	* @return mixed
	*/	
	public function paramGet(string $name, $default = '') {
		if (isset($_POST[$name])) {
			$default = $_POST[$name];
		} else if (isset($_GET[$name])) {
			$default = $_GET[$name];
		}	
		return $default;
	}

	/**
	* adat irás GET -be
	* @param string $name
	* @param mixed $value
	* @return void
	*/	
	public function paramSet(string $name, $value) {
		$_GET[$name] = $value;
	}
	
	/**
	* adat olvasás a wp config -ból
	* @param string $name
	* @result string
	*/
	public function getOption(string $name) {
		return get_option($name, '');
    }

} // Controller

/**
* szöveg formázó
* javasolt használat:  txt(__('token1 {v1} / {v2}'),['v1'=>1, 'v2'=>2])
*     így a poEditor felismeri
* @param $format     tartalmazhat {nev} formáju hivatkozásokat is
* @param $vars       ["nev"=>érték, ....]
* @return string
*/
function txt(string $format, $vars): string {
	$result = $format;
	foreach ($vars as $fn => $fv) {
		$result = str_replace('{'.$fn.'}', $fv, $result);	
	}
	return $result;
}


?>