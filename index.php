<?php
/*
Plugin Name: Atomic Penguins Widget Area Everywhere
Description: Place a widget area anywhere possible.
Author: Atomic Penguin
Author URL: http://atomicpenguins.com/
Version: 1.0
Version
*/

function the_ui() {
    include('ui.php');
}

add_action('admin_menu', 'atomic_wae_addmenu');
	function atomic_wae_addmenu() {
	    add_menu_page("Atomic Penguins Widget Area Everyhwere", "Widget Everyhwere", 1, "Atomic Penguins Widget Area Everyhwere", "the_ui",plugins_url('atomic penguin.png',__FILE__));
	}
/* Check if page is Atomic Penguins WAE */
function wae_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'Atomic Penguins Widget Area Everyhwere') {
        wp_enqueue_media();
        wae_cs_js_init();
    }
}
/* add action wae_admin_scripts */
add_action('admin_enqueue_scripts', 'wae_admin_scripts');
	/* Register css and js files in admin */
	function wae_cs_js_init() {
	    wp_register_style('css_main', plugins_url('main.css', __FILE__));
	    wp_register_script('js_main', plugins_url('main.js', __FILE__));

	    wp_enqueue_style('css_main');
	    wp_enqueue_script('js_main');
	}


function getall_wae(){
		global $wpdb;
		$table_name = $wpdb->prefix . "wae";
		$sql = "SELECT * FROM $table_name";
		$row = $wpdb->get_results( $sql, 'OBJECT');
		return $row;
}
add_action( 'widgets_init','registerWae');
	function registerWae(){
		$data = getall_wae();
			//var_dump($data);
		foreach ($data as $row) {
		# code...
		createWae($row);
		}		
	}

function createWae($row){
	//register_widget( 'wp_custom_widget_area' );
	register_sidebar( array(
		'name'          => $row->wae_name,
		'id'            => $row->wae_id,
		'description'   => $row->wae_desc,
		'class'			=> $row->wae_class,
	) );
}
//add functionto save
add_action('wp_ajax_SAVE_WAE', 'saveWae');
	//save new wea
	function saveWae()
	{
		global $wpdb;
    	$table_name = $wpdb->prefix . "wae";
    	if (isset($_POST['data']['id'])) 
    	{
	        $id = sanitize_text_field($_POST['data']['id']);
	        $sql = $wpdb->get_row($wpdb->prepare("Select * from %s where wae_id = %s",$table_name,$id));
    	}
    	if (count($sql) == 0) 
    	{
    		$data = array('wae_name' =>  sanitize_text_field($_POST['data']['name']), 'wae_id'=> sanitize_text_field($_POST['data']['id']), 'wae_class' => sanitize_text_field($_POST['data']['wea_class']), 'wae_desc' => sanitize_text_field($_POST['data']['desc']));
    		$rows_affected = $wpdb->insert($table_name, $data);
	        $json = json_encode(array(
	        	'Type' => 'success'
	        ));
	        exit;
    	}
	}

add_action('wp_ajax_UPDATE_WAE','update_wae');
	function update_wae()
	{
		global $wpdb;
		$id = $_POST['id'];
    	$table_name = $wpdb->prefix . "wae";
    
    	$where = array('id' => $id);
    	$data = array('wae_name' =>  sanitize_text_field($_POST['data']['name']), 'wae_id'=> sanitize_text_field($_POST['data']['id']), 'wae_class' => sanitize_text_field($_POST['data']['wea_class']), 'wae_desc' => sanitize_text_field($_POST['data']['desc']));
    	$rows_affected = $wpdb->update($table_name, $data, $where);
	    $json = json_encode(array(
	    	'Type' => 'success'
	    ));
	    exit;
    		
	}

add_action('wp_ajax_GET_ALL_WAE', 'get_all_wae');
	function get_all_wae() {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wae";

		    $sql = "select * from " . $table_name;

		    $results = $wpdb->get_results($sql);
		    $json = json_encode($results);

		    header('Content-type: application/json');
		    echo $json;
		    exit;
	}

add_action('wp_ajax_DELETE','delete_wae');
    function delete_wae()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "wae";
        $id = $_POST['id'];
        $wpdb->delete($table_name, array('id' => $id), array('%d'));
    }

add_action('wp_ajax_GET_ONE_WAE','get_one_wae');
	function get_one_wae()
	{
		 global $wpdb;
	    $table_name = $wpdb->prefix . "wae";
	    $id = $_POST['id'];
	    //$sql = 'select * from ' . $table_name . ' where id=' . $id . '';
	    $results = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id = %d",$id));
	    $json = json_encode($results);

	    header('Content-type: application/json');
	    echo $json;
	    exit;
	}

add_shortcode('wae', 'wae_box_shortcodes');
	function wae_box_shortcodes($atts) 
	{
		ob_start();
		dynamic_sidebar( $atts['id'] );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

register_activation_hook(__FILE__, 'install_wae');
	function install_wae()
	{
	    global $wpdb;
	    $table_name = $wpdb->prefix . "wae";

	    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	    {

	         $sql = "CREATE TABLE $table_name (
	            id mediumint(9) NOT NULL AUTO_INCREMENT,
	            wae_name VARCHAR(255),
	            wae_id VARCHAR(255),
	            wae_class VARCHAR(255),
	            wae_desc VARCHAR(255),
	            UNIQUE KEY id (id)
	            );";
	            
	    }
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	}

?>