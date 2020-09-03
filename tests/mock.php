<?php

global $database, $wpdb;
$database = new stdClass();
// terms:[{term_id,name, description, parent, taxonomia},...]
// termmeta:[{term_id, name, value}]

function databaseInit() {
    global $database;
    $database->terms = [];
    $database->termmeta = [];
}

databaseInit();

//define ('INPUT_POST','');

class Wpdb {
    public function escape_by_ref($id) {
        return $id;
    }
}
$wpdb = new Wpdb();

//wp functions
function load_plugin_textdomain(string $domain, bool $par, string $path) {}
function wp_enqueue_style(string $type, string $url) {}
function add_action(string $hook, string $funName, int $prior, int $parnum) {}
function get_template_directory(): string {return '/tmpldir';}
function get_posts($params) {return [];}
function wp_insert_post($params) {};
function update_option(string $name, string $value) {}
function get_option(string $name, $param=null): string {return 'option_'.$name;}

/**
 * @return string
 */
function get_site_url() {
    return 'http://localhost/wordpress';
}


/**
 * @param string $fieldName
 * @param unknown $fieldValue
 * @param string $taxonomia
 * @return {"term_id":#,....}|false
 */
function get_term_by(string $fieldName, $fieldValue, string $taxonomia) {
    
    global $database;
    $result = false;
    foreach ($database->terms as $term) {
        if (($term->$fieldName === $fieldValue) & ($term->taxonomia == $taxonomia)) {
            $result = $term;
        }
    }
    return $result;
}

/**
 * @param int $term_id
 * @param unknown $name
 * @param bool $single
 * @return array|value|false
 */
function get_term_meta(int $term_id, $name, bool $single = false) {
    global $database;
    $result = false;
    if (!$single) {
        $result = [];
    }
    foreach ($database->termmeta as $termmeta) {
        if (($termmeta->term_id == $term_id) & ($termmeta->name == $name)) {
            if ($single) {
                $result = $termmeta->value;
            } else {
                $result[] = $termmeta->value;
            }
        }
    }
    return $result;
}

/**
 * @params string $taxanomia
 * @params array $params ["parent" => #]
 * @params bool $hide_empty
 * @return array of {id,name,description, parent}
 */
function get_terms(string $taxonomia, array $params = []):array {
    global $database;
    $result = [];
    foreach ($database->terms as $term) {
        if ($term->parent == $params['parent']) {
            $result[] = $term;
        }
    }
    return $result;
}

/**
 * @param int $term_id
 * @param string $name
 * @param string $value
 * @return bool
 */
function delete_term_meta(int $term_id, string $name, $value = ''):bool {
    global $database;
    $result = true;
    foreach ($database->termmeta as $termmeta) {
        if ($termmeta->term_id == $term_id) {
            $termmeta->term_id = '';
        }
    }
    return $result;
}

/**
 * @param int $term_id
 * @param string $taxonomia
 * @return bool
 */
function wp_delete_term(int  $term_id, string $taxonomia):bool {
    global $database;
    $result = true;
    foreach ($database->terms as $term) {
        if ($term->term_id == $term_id) {
            $term->term_id = '_deleted';
            $term->parent = 0;
        }
    }
    return $result;
}

/**
 * @param string $name
 * @param string $taxonomia_name
 * @param array $params [description, parent]
 * @return array ["term_id"=>#]|WP_error
 */
function wp_insert_term(string  $name, string $taxonomia, array $params = []):array {
        global $database;
        $result = true;
        $term = new stdClass();
        $term->term_id = count($database->terms);
        $term->name = $name;
        $term->description = $params['description'];
        $term->parent = $params['parent'];
        $term->taxonomia = $taxonomia;
        $database->terms[] = $term;
        return ["term_id" => $term->term_id];
}
    
/**
 * @param int $term_id
 * @param string $name
 * @param unknown $value
 * @return bool
 */
function add_term_meta(int $term_id, string $name, $value):bool {
    global $database;
    $result = true;
    $termmeta = new stdClass();
    $termmeta->term_id = $term_id;
    $termmeta->name = $name;
    $termmeta->value = $value;
    $database->termmeta[] = $termmeta;
    return true;
}

/**
 * @param int $term_id
 * @param string $taxanomia_name
 * @param array $params ["name" => "", "description" => "", "parent" => #]
 * @return array ["term_id"]|WP_error
 */
function wp_update_term( int $term_id, string $taxanomia, array $params = []) {
        global $database;
        $result = ["term_id" => $term_id];
        foreach ($database->terms as $term) {
            if ($term->term_id == $term_id) {
                $term->name = $params['name'];
                $term->description = $params['description'];
                $term->parent = $params['parent'];
                return ["term_id" => $term->term_id];
            }
        }
    }

/**
 * update or insert meta 
 * @param int $term_id
 * @param string $name
 * @param unknown $value
 * @return bool
 */
function update_term_meta(int $term_id, string $name, $value):bool {
    global $database;
    $result = false;
    $i = 0;
    foreach ($database->termmeta as $termmeta) {
        if (($termmeta->term_id == $term_id) & ($termmeta->name == $name)) {
            $database->termmeta[$i]->value = $value;
            $result = true;
        }
        $i++;
    }
    if (!$result) {
		$result = add_term_meta($term_id, $name, $value);
    }
    return $result;
}

?>
