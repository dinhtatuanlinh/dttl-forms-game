<?php
/**
 * @package dttl-forms-game
 * @version 1.7.2
 */
/*
Plugin Name: dttl-forms-game popup
Plugin URI: http://noteatext.com
Description: this is plugin that helps make a form at frontend
Author: dttl
Version: 1.00
Author URI: http://noteatext.com
*/

define('DTTL_FORM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DTTL_FORM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DTTL_FORM_ASSETS_URL', DTTL_FORM_PLUGIN_URL . 'assets/');
define('DTTL_FORM_CSS_URL', DTTL_FORM_ASSETS_URL . 'css/');
define('DTTL_FORM_JS_URL', DTTL_FORM_ASSETS_URL . 'js/');
define('DTTL_FORM_ICONS_URL', DTTL_FORM_ASSETS_URL . 'icons/');
define('DTTL_FORM_IMGS_URL', DTTL_FORM_ASSETS_URL . 'imgs/');
define('DTTL_FORM_MENUS_DIR', DTTL_FORM_PLUGIN_DIR . 'menus/');
define('DTTL_FORM_FORMS_DIR', DTTL_FORM_PLUGIN_DIR . 'forms/');
define('DTTL_FORM_FORMS_URL', DTTL_FORM_PLUGIN_URL . 'forms/');
define('DTTL_FORM_LIBS_DIR', DTTL_FORM_PLUGIN_DIR . 'libs/');
define('DTTL_METABOX_PRODUCT_DIR', DTTL_FORM_PLUGIN_DIR . 'metabox/');

if(!is_admin()){
    // kiêm tra xem đã bật form chưa nếu rồi thì cho hiện form
    // giá trị này được lưu trong bảng options với option name là dttl_form_setting['dttl_enable_form']
    $options = get_option('dttl_form_game_settings', false);

    if($options !=false && $options['dttl_enable_form'] == true){
        require_once DTTL_FORM_FORMS_DIR . 'create-form.php';
        new dttl_create_form();
    }
    $popupOptions = get_option('popupSettingOption', false);

    if($popupOptions !=false && $popupOptions['dttl_enable_form'] == true){
        require_once DTTL_FORM_FORMS_DIR . 'popupCreateForm.php';
        new DttlCreatePopupForm();
    }
    
}else{
    require_once DTTL_FORM_LIBS_DIR . 'html.php';

    require_once DTTL_FORM_MENUS_DIR . 'menu_dashboard.php';
    new dttl_menu_dashboard();

    require_once DTTL_FORM_MENUS_DIR . 'popupMenu.php';
    new dttl_popup_dashboard();

    require_once DTTL_METABOX_PRODUCT_DIR . 'main.php';
    new dttl_metabox_product();
}

$options = get_option('dttl_form_game_settings', false);
// echo '<pre>';
// print_r($options);
// echo '</pre>';


// lấy dữ liệu gửi lên ajax
// post_contact là tên hàm lưu dữ liệu, tên hàm sẽ được gửi đi trong form kèm với dữ liệu truyền lên
add_action('wp_ajax_post_contact', 'post_contact');
add_action('wp_ajax_nopriv_post_contact', 'post_contact');
function post_contact() {
    global $wpdb;

    $data = [
        'name' => sanitize_text_field($_POST['name']),
    ];
    $format =  array('%s');
    $table = 'wp_dttl_pl_test';
    $query="INSERT INTO {$table} (`my_name`) VALUES (%s)";
    $prepareQuery = $wpdb->prepare($query, $data['name']);
    // $contact = $wpdb->insert($table, $data,$format);
    $result = $wpdb->query($prepareQuery);

    if ($result == 1) {
        echo json_encode(array('title'=>'Success', 'message'=>__('The message has been sent! Thank you.')));
    } else {
        echo json_encode(array('title'=>'Fail', 'message'=>__($contact)));
    }
    wp_die();
}

// chạy các hàm khi active plugin
register_activation_hook(__FILE__, 'dttl_forms_active');
function dttl_forms_active(){
    global $wpdb;
    $options = get_option('dttl_form_options', false);
    // kiểm tra
    if($options == false){
        // đưa 1 mảng vào bảng wp-option
        $dttl_form_options = array(
            'table_name' => '',
            'enable_form' => false,
            'fields' =>array(),
            'author'=> 'dttl'
        );
        // option api
        // tham so thu nhat là tên option name, tham số thứ 2 là giá trị của row này, tham số thứ 4 là yes để tự động chạy khi kích hoạt plugin
        add_option('dttl_form_options', $dttl_form_options, '', 'yes');
        // chú ý option name không được trùng với option name đã có
        // mảng được chuyển sang chuỗi được lưu dưới dạng chuỗi bằng phương thức serialize($arr) để chuyển lại thành mảng dùng unserialize($str)
    }
}   
// ################################################
// thực thi các lệnh khi deactive plugins
// dừng autoload các thông số trong bảng wp_option
register_deactivation_hook(__FILE__, 'dttl_forms_deactive');// chạy các hàm khi deactive
function dttl_forms_deactive(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'options';
    $wpdb->update(
        $table_name,
        array('autoload' => 'no'),
        array('option_name' => 'dttl_pl_option'),
        array('%s'),// khai báo cho giá trị thứ nhất (array('autoload' => 'no')) đưa vào bảng là string
        array('%s')// khai báo cho giá trị thứ hai (array('option_name' => 'dttl_pl_option')) đưa vào bảng là string
        // nếu là number thì là %n
    );
}
// ################################################
// thực thi các lệnh khi uninstall plugins
register_uninstall_hook(__FILE__, 'dttl_forms_uninstall');
function dttl_forms_uninstall(){
    // global $wpdb;
    // delete_option('dttl_forms_version');
    // $table_name = $wpdb->prefix . 'dttl_forms';
    // $sql = "DROP TABLE IF EXISTS " . $table_name;
    // $wpdb->query($sql);// thực thi sql bằng đối tượng wpdb
}
