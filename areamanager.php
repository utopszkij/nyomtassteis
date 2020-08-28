<?php
/**
 * Plugin Name: AreaManager WordPress bővítmény
 * Plugin URI: http://github.com/utopszkij/areamanager
 * Description: Ez kiegészítés a wordpress / woocomerce eb áruházhoz. Google map -on megjeleníthető szinezett terület rendelhető a termékekhez.
 * Author: Fogler Tibor, Sas Tibor
 * Version: 1.0
 * Author URI: http://github.com/utopszkij/
 * Domain Path: /areamanager/areamanager
 * Text Domain: areamanager
*/
define('AREAMANAGER', 'areamanager' );
define('AREAMANAGER_PLUGINPATH',__DIR__);
define('AREAMANAGER_PLUGINURL',get_site_url().'index.php/areamanager'); 

class Areamanager {
	/**
	* wordpress inditó page létrehozása. Az ilyen oldalak index.php/name módon elindíthatóak.
	* rendszerint esemény kezelő van hozzájuk kapcsolva ami egy plugin rutint indit.
	* @param string $name
	*/
	public function areaManagerCreatePage(string $name) {
		global $wpdb;
	   $w = $wpdb->get_results('select * from '.$wpdb->prefix.'posts where post_type="page" and post_name="'.$name.'"');
	   if (count($w) == 0) {
			$PageGuid = site_url() ."/".$name;
			$my_post  = array( 'post_title'     => $name.' start page',
		                   'post_type'      => 'page',
		                   'post_name'      => $name,
		                   'post_content'   => '',
		                   'post_status'    => 'publish',
		                   'comment_status' => 'closed',
		                   'ping_status'    => 'closed',
		                   'post_author'    => 1,
		                   'menu_order'     => 0,
		                   'guid'           => $PageGuid );
		  wp_insert_post( $my_post );
	  }
	}  
	  
	public function rooter(string $defOption='default', string $defTask='default', array $params = [] ) {
    	ob_start(); // echo és html output átirányitása memóra pufferbe
    	// saját css és betöltése
    	?>
        <link rel='stylesheet' 
       	href="<?php echo get_site_url(); ?>/wp-content/plugins/areamanager/css/areamanager.css" />	
    	<?php
    	
    	// bejelentkezett user elérése
    	$current_user = new stdClass();	
    	if ( is_user_logged_in() ) {
    	    // Current user is logged in,
    	    // so let's get current user info
    	    $current_user = wp_get_current_user();
    	    // User ID
    	    $user_id = $current_user->ID;
    	 }
    	 
    	 // option és task GET/POST paraméter kezelése
    	 if (isset($_POST['option'])) {
    		$_GET['option'] = $_POST['option'];	 
    	 }
    	 if (isset($_POST['task'])) {
    		$_GET['task'] = $_POST['task'];	 
    	 }
    	 $option = $defOption;
    	 if (isset($_GET['option'])) {
    		$option = $_GET['option'];
    	 }	
    	 
    	 // controller betöltése, aktivizálása
    	 if (file_exists(AREAMANAGER_PLUGINPATH.'/controllers/'.$option.'.php')) {
    			include_once(AREAMANAGER_PLUGINPATH.'/fw.php');
    			include_once(AREAMANAGER_PLUGINPATH.'/controllers/'.$option.'.php');
    			$controllerName = ucFirst($option).'Controller';
    			$controller = new $controllerName ($option);
    			$controller->currentUser = $current_user;
    			$task = $defTask;
    			if (isset($_GET['task'])) {
    				$task = $_GET['task'];
    			}		
    			$controller->$task ($params);
    	 } else {
    			echo 'controller not found '.AREAMANAGER_PLUGINPATH.'/controllers/'.$option.'.php'; exit();	 
    	 }
    	 $result = ob_get_contents(); // echo -zott tartalom kinyerése a $result változóba
    	 ob_end_clean();
    	 return $result;
	 }
	 
} // Areamanager class
global $areamanager;

// init plugin
function areamanager_plugin_init(){
	global $areamanager;
    $areamanager = new Areamanager();
    if( !session_id() )
        session_start();
    $areamanager->areaManagerCreatePage(AREAMANAGER);  
    load_plugin_textdomain( AREAMANAGER, false, AREAMANAGER.'/languages' );
}
add_action('init','areamanager_plugin_init');

// ========================== admin oldal ============================================================== 

/**
* ez a plugin admin oldali fő programja,
* beépítve az admin oldal Beállítások menü alá
*/ 
function areamanager_admin() {
    global $areamanager;
    echo $areamanager->rooter('admin','adminPanel');
}
function areamanager_plugin_create_menu() {
 add_options_page("AreaManager WordPress bővítmény", "Area Manager WordPress bővítmény", 1, 
 	AREAMANAGER, "areamanager_admin");
}
add_action('admin_menu', 'areamanager_plugin_create_menu');


// ========================== site ======================================================================

/**
* wordpress default esemény kezelő, minden frontend html megjelenítéskor fut
* global $post már fel van töltve, de még nem lett megjelenítve. 
* Amikor index.php/areamanager URL -el jelenítjuk meg a plugin indító oldalát, 
* akkor jobb lenne ha a title és a content üres lenne.
* Ugyanakkor az admin oldali oldal kezelőben zavaró ha a titke üres.
* Ezért az adatbázisban az oldalnak van title adata, de azt nem jelenítjük meg.
*/
add_action( 'wp', 'areamanagerClearTitle' );
function areamanagerClearTitle() {
	global $post;
    if ('page' === get_post_type()) {
    	if ($post->post_name == AREAMANAGER) {
			$post->post_title = '';
			$post->content = '';
    	}
    } else {
			return '';    
    }
}


/**
* wordpress esemény kezelő, a content megjelenités után fut le
* amit ez visszaad az a content szövege után jelenik meg.
* Ez a plugin a front end fő programja
* az  index.php/areamanager?option=controllerName&task=taskName  szerű hivásokat kezeli
*/
add_action( 'the_content', 'areamanager_main');
function areamanager_main(string $content): string {
    global $areamanager, $post;
	if ($post->post_name == AREAMANAGER) {
	    $result =  $areamanager->rooter('default','default');
	} else {
	    $result = $content;
	}
	return $result;   
}

// esemény kezelő after product save, ez fut lomtárba helyezés után is, lomtárból végleges törlésnél viszont nem.
add_action( 'save_post_product', 'areamanager_save_post_fun', 50, 3);
/**
 * product after save esemény kezelő
 * @param int $product_id
 * @param Product $product
 * @param bool $update
 */
function areamanager_save_post_fun(int $product_id,$product, bool $update) {
    global $areamanager;
    if ($update) {
        echo $areamanager->rooter('product','afterSave',[$product_id, $product]);
        exit();  // itt a woocoommerce redirectelne a product edit oldalra, ez most nem kell.
    }
}

// lomtárból végeles törlésnél ez fut
add_action( 'before_delete_post', 'areamanager_delete_post_fun',50,1);
function areamanager_delete_post_fun($product_id) {
    global $areamanager;
    echo $areamanager->rooter('product','afterDelete',[$product_id]);
}

?>

