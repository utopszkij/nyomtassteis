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
	  
	/**
	* admin panel
	*/
	public function adminPanel() {
		 echo '	
		 <h1>AREA MANAGER PLUGIN ADMIN panel</h1>
		   copyrigt info, szerzők, támogatás lehetőség stb.
			<h2>Itt lesznek magadhatóak a plugin beállításai</h2>
			<ul>
				<li>Google API_KEY  (help, hogy kell ilyet beszerezni)</li>
				<li>Default google map location pl: "Budapest Hungary"(új felvitelnél ez a térkép jelenik meg)</li>
				<li>Default google map zoom (ajánlott:7)</li>  	
			</ul>
		 <br />
		 ';
	}
} // Areamanager class

// init plugin
function areamanager_plugin_init(){
	 $areamanager = new Areamanager();
    if( !session_id() )
        session_start();
    $areamanager->areaManagerCreatePage(AREAMANAGER);  
    load_plugin_textdomain( AREAMANAGER, false, AREAMANAGER.'/languages' );
}
add_action('init','areamanager_plugin_init');

// ========================== admin oldal ============================================================== 

/**
* ez a plugin admin panelje
*/ 
function areamanager_admin() {
	$areamanager = new Areamanager();
	$reamanager;
	$areamanager->adminPanel();
}

// létrehozunk egy menüpontot az admin oldalon a Beállítások menüben
add_action('admin_menu', 'areamanager_plugin_create_menu');
function areamanager_plugin_create_menu() {
 add_options_page("AreaManager WordPress bővítmény", "Area Manager WordPress bővítmény", 1, 
 	AREAMANAGER, "areamanager_admin");
}


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
* amit ez echo -z az a content szövege után jelenik meg.
* Ez a plugin front end fő programja
* az  index.php/areamanager?option=controllername&task=taskName  szerű hivásokat kezeli
*/
add_action( 'the_content', 'areamanager_main');
function areamanager_main( $content) {

	global $post;
	if ($post->post_name != AREAMANAGER) {
		return $content;	
	}
	
	ob_start(); // echo átirányitása memóra pufferbe
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
	 $option = 'default';
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
			$task = 'default';
			if (isset($_GET['task'])) {
				$task = $_GET['task'];
			}		
			$controller->$task ();
	 } else {
			echo 'controller not found '.AREAMANAGER_PLUGINPATH.'/controllers/'.$option.'.php'; exit();	 
	 }
	 $result = ob_get_contents(); // echo -zott tartalom kinyerése a $result változóba
	 ob_end_clean();
	 return $result;
}

// esemény kezelő after product save, ez fut lomtárba helyezés után is, lomtárból végleges törlésnél viszont nem.
add_action( 'save_post_product', 'areamanager_save_post_fun', 50, 3);
function areamanager_save_post_fun($product_id,$product,$update) {
	if ($update) {
		// rekord már tárolva az adatbázisba, van ID -je is
		?>
			<h2>Most jelenik meg a google map térkép a rajta lévő szerkeszthető sokszöggel és kereső mezővel</h2>
			<p>product_id= <?php echo $product_id; ?></p>
			<p>Ellenörizni: csak bejelentkezett admin használhatja!</p>
			<p>Vizsgálni kell a product ACF "is_area" értékét, ha ez false akkor nem kell csinálni semmit.</p>
			<p></p>			
			<p>a sokszög kialakítása után a "Rendben" vagy a "Mégsem" gombra kell /lehet kattintani.</p>
			<p>"Rendben" esetén tárol, Mindkét esetben visszatérünk a product editor képernyőre.</p>
			<a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $product_id; ?>&action=edit">Rendben</a>
			&nbsp;
			<a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $product_id; ?>&action=edit">Mégsem</a>
		<?php
		exit();
	}
}

// lomtárból végeles törlésnél ez fut
add_action( 'before_delete_post', 'areamanager_delete_post_fun',50,1);
function areamanager_delete_post_fun($product_id) {
		?>
			<p>product_id= <?php echo $product_id; ?></p>
			<p>Vizsgálni kell a product ACF "is_area" értékét, ha ez false akkor nem kell csinálni semmit.</p>
			<p>Ellenörizni: csak bejelentkezett admin használhatja!</p>
			<p>Most a program törli az adatbázisból a körvonal adatait,</p>
			Képernyő nem jelenik meg, a művelet elvégzése után visszatérünk a termék editor oldalára.
			<a href="<?php echo get_site_url(); ?>'/wp-admin/post.php?post='.$product_id.'&action=edit">Tovább</a>
		<?php
		exit();
}

?>

