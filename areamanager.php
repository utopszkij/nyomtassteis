<?php
/**
* Plugin Name: Areamanager Plugin
* Plugin URI: http://www.github.com/utopszkij/aeramanager
* Description: Kiegészítés woocommerce -hez, a termék kategóriákhoz terület jellemzők adhatóak
* Version: 1.0
* Author: Fogler Tibpre
* Author URI: http://www.github.com/utopszkij
*/

define('AREAMANAGER','areamanager');

// init plugin, load languages definiton, style, javascripts
function areamanager_plugin_init(){
    load_plugin_textdomain(AREAMANAGER, false, AREAMANAGER.'/languages');
    wp_enqueue_style( 'style', get_site_url().'/wp-content/plugins/areamanager/css/areamanager.css');
}
add_action('init','areamanager_plugin_init');
add_action('admin_init','areamanager_plugin_init');
add_action('product_cat_add_form_fields', 'areamanager_extend_form', 10, 0);
add_action('product_cat_edit_form_fields', 'areamanager_extend_form', 10, 1);
add_action('edited_product_cat', 'areamanager_save_meta', 10, 1);
add_action('create_product_cat', 'areamanager_save_meta', 10, 1);

/**
 * wordpress inditó page létrehozása. Az ilyen oldalak index.php/name módon elindíthatóak.
 * rendszerint esemény kezelő van hozzájuk kapcsolva ami egy plugin rutint indit.
 * @param string $name
 */
public function areaManagerCreatePage(string $name) {
    global $wpdb;
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
}  

/**
 * extend woocommerce category form
 * @param Term | boolean $term
 */
function areamanager_extend_form($term = false) {
    if ($term) {
        $term_id = $term->term_id;
        // retrieve the existing value(s) for this meta field.
        $type = get_term_meta($term_id, 'type', true);
        $enable_start = get_term_meta($term_id, 'enable_start', true);
        $enable_end = get_term_meta($term_id, 'enable_end', true);
        $central = get_term_meta($term_id, 'central', true);
        $poligon = get_term_meta($term_id, 'poligon', true);
        $population = get_term_meta($term_id, 'population', true);
        $place = get_term_meta($term_id, 'place', true);
    } else {
        $type = 'continent';
        $enable_start = '';
        $enable_end = '';
        $central = '';
        $poligon = '';
        $population = 0;
        $place = 0;
    }
    ?>
    <div id="areamanager-category-extend">
    <div class="form-field form-type-wrap">
        <label><?php echo __('category_type',AREAMANAGER); ?></label>
        <select id="type" name="type">
        	<option value="continent"<?php if ($type == 'continent') echo ' selected="selected"'; ?>>
        		<?php echo __('continent',AREAMANAGER); ?></option>
        	<option value="country"<?php if ($type == 'country') echo ' selected="selected"'; ?>>
        		<?php echo __('country',AREAMANAGER); ?></option>
        	<option value="region_1"<?php if ($type == 'region_1') echo ' selected="selected"'; ?>>
        		<?php echo __('region_1',AREAMANAGER); ?></option>
        	<option value="region_2"<?php if ($type == 'region_2') echo ' selected="selected"'; ?>>
        		<?php echo __('region_2',AREAMANAGER); ?></option>
        	<option value="locality"<?php if ($type == 'locality') echo ' selected="selected"'; ?>>
        		<?php echo __('locality',AREAMANAGER); ?></option>
        	<option value="sublocality"<?php if ($type == 'sublocality') echo ' selected="selected"'; ?>>
        		<?php echo __('sublocality',AREAMANAGER); ?></option>
        	<option value="postalcode"<?php if ($type == 'postalcode') echo ' selected="selected"'; ?>>
        		<?php echo __('postalcode',AREAMANAGER); ?></option>
        	<option value="local_pol_zone"<?php if ($type == 'local_pol_zone') echo ' selected="selected"'; ?>>
        		<?php echo __('local_pol_zone',AREAMANAGER); ?></option>
        	<option value="country_pol_zone"<?php if ($type == 'county_pol_zone') echo ' selected="selected"'; ?>>
        		<?php echo __('country_pol_zone',AREAMANAGER); ?></option>
        </select>
    </div>
    <div class="form-field form-enable_start-wrap">
        <label><?php echo __('enable_start',AREAMANAGER); ?></label>
        <input type="text" id="enable_start" name="enable_start" value="<?php  echo $enable_start; ?>" />
	</div>
    <div class="form-field form-enable_end-wrap">
        <label><?php echo __('enable_end',AREAMANAGER); ?></label>
        <input type="text" id="enable_end" name="enable_end" value="<?php  echo $enable_end; ?>" />
	</div>
    <div class="form-field form-central-wrap">
        <label><?php echo __('central',AREAMANAGER); ?></label>
        <input type="text" id="central" name="central" value="<?php  echo $central; ?>" onchange="centralChange()" />
	</div>
    <div class="form-field form-population-wrap">
        <label><?php echo __('population',AREAMANAGER); ?></label>
        <input type="text" id="population" name="population" value="<?php  echo $population; ?>" />
	</div>
    <div class="form-field form-place-wrap">
        <label><?php echo __('place',AREAMANAGER); ?>&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="place" name="place" value="<?php  echo $place; ?>" />
	</div>
    <div class="form-field form-poligon-wrap">
        <label><?php echo __('poligon',AREAMANAGER); ?></label>
        <textarea row="20" cols="80" id="poligon" name="poligon"><?php echo $poligon; ?></textarea>
	</div>
    <div class="form-field form-map-wrap">
        <div id="map" style="width:520px; height:450px"></div>
	</div>
    <div class="form-field form-button-wrap">
        <button type="button" onclick="console.log(poligonMap.getPath().length)">poligon info</button>
	</div>
	</div>
   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1Z88sYk5uoljvlVhaLxt_TbS9MKDiDYA&callback=initMap&libraries=&v=weekly"></script>
   <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
   <script src="<?php echo get_site_url();?>/wp-content/plugins/areamanager/js/areamanager.js"></script>
	<?php
}	

/**
 * extend wooCommerce category save to database
 * @param int $term_id
 */
function areamanager_save_meta($term_id) {
    $type = filter_input(INPUT_POST, 'type');
    $enable_start = filter_input(INPUT_POST, 'enable_start');
    $enable_end = filter_input(INPUT_POST, 'enable_end');
    $central = filter_input(INPUT_POST, 'central');
    $poligon = filter_input(INPUT_POST, 'poligon');
    $population = filter_input(INPUT_POST, 'population');
    $place = filter_input(INPUT_POST, 'place');
    update_term_meta($term_id, 'type', $type);
    update_term_meta($term_id, 'enable_start', $enable_start);
    update_term_meta($term_id, 'enable_end', $enable_end);
    update_term_meta($term_id, 'central', $central);
    update_term_meta($term_id, 'poligon', $poligon);
    update_term_meta($term_id, 'population', $population);
    update_term_meta($term_id, 'place', $place);
}

?>