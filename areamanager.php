<?php
/**
* Plugin Name: Areamanager Plugin
* Plugin URI: http://www.github.com/utopszkij/aeramanager
* Description: Kiegészítés woocommerce -hez, a termék kategóriákhoz terület jellemzők adhatóak
* Version: 1.0
* Requires at least: 4.4 
* Requires PHP:      7.2
* Author: Fogler Tibpre
* Author URI: http://www.github.com/utopszkij
* Text Domain:       areamanager
* Domain Path:       /languages
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
*/


define('AREAMANAGER','areamanager');

/**
 * create AreaManager starting page if not exists
 * start plugin:   <myDomain>/index.php/areamanager
 * @var unknown $w
 */
$w = get_posts( array(
    'name' => $name,
    'post_type' => 'page',
    'post_status' => 'publish',
    'posts_per_page' => 1
));
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

/**
 * init plugin, load languages definiton, style
 */ 
add_action('init','areamanager_plugin_init');
function areamanager_plugin_init(){
    load_plugin_textdomain(AREAMANAGER, false, AREAMANAGER.'/languages');
    wp_enqueue_style( 'style', get_site_url().'/wp-content/plugins/areamanager/css/areamanager.css');
}

/**
 * ez a plugin admin oldali fő programja,
 * beépítve az admin oldal Beállítások menü alá
 */
function areamanager_admin() {
    include_once __DIR__.'/class.areamanager.php';
    $area = new Area();
    $area->adminPanel();
}
function areamanager_plugin_create_menu() {
    add_options_page("AreaManager WordPress bővítmény", "Area Manager WordPress bővítmény", 1,
        AREAMANAGER, "areamanager_admin");

    add_menu_page('Areamanager Info' ,'Areamanager setup','manage_options',
            'areamanager-info','areamanager_admin','', 11 );
    // add_submenu_page('areamanager-info', 'almenu-page-title', 'almenu', 'manage_options',
    //    'almenu-slug','areamanager_admin', 1);
}
add_action('admin_menu', 'areamanager_plugin_create_menu');

/**
 * Areamanager frontend main page
 * url: <mainUrl>/index.php/areamanager
 */
add_action( 'the_content', 'areamanager_main');
add_action('admin_init','areamanager_plugin_init');
function areamanager_main(string $content): string {
    global $post;
    if ($post->post_name == AREAMANAGER) {
            // plugin main inditása $content törlése
            $content = 'Area manager program main page';
    }
    return $content;
}

// =============== extend woocommerce category ==============================
add_action('product_cat_add_form_fields', 'areamanager_extend_form', 10, 0);
add_action('product_cat_edit_form_fields', 'areamanager_extend_form', 10, 1);
add_action('edited_product_cat', 'areamanager_save_meta', 10, 1);
add_action('create_product_cat', 'areamanager_save_meta', 10, 1);

/**
 * extend woocommerce category form
 * @param Term | boolean $term
 */
function areamanager_extend_form($term = false) {
    include_once __DIR__.'/class.areamanager.php';
    $area = new Area();
    $area->init();
    if ($term) {
        $area->read($term->term_id);
    }
    $area->addForm(true);
 }	

/**
 * extend wooCommerce category save to database
 * @param int $term_id
 */
function areamanager_save_meta($term_id) {
    include_once __DIR__.'/class.areamanager.php';
    $area = new Area();
    $area->init();
    $area->id = $term_id;
    $area->type = filter_input(INPUT_POST, 'type');
    $area->enableStart = filter_input(INPUT_POST, 'enableStart');
    $area->enableEnd = filter_input(INPUT_POST, 'enableEnd');
    $area->central = filter_input(INPUT_POST, 'central');
    $area->poligon = filter_input(INPUT_POST, 'poligon');
    $area->population = filter_input(INPUT_POST, 'population');
    $area->place = filter_input(INPUT_POST, 'place');
    $area->modify(true);
}

?>