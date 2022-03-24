<?php
class dttl_create_form {
    private $_setting_options; // biến chứa dữ liệu đang được lưu trong opiton
    function __construct(){
        add_action('wp_body_open', array($this,'get_phone'), 50);
        add_action( 'wp_enqueue_scripts', array($this,'add_css'));
        add_action('wp_head', array($this,'add_style'), 50);
        add_action( 'wp_enqueue_scripts', array($this,'add_js_libs'));
        add_action('wp_footer', array($this,'script_tags'), 61);
        $this->_setting_options = get_option('dttl_form_game_settings');
    }
    function get_phone(){

        require DTTL_FORM_FORMS_DIR . 'form.php';
    }
    // phải sử dụng jquery để thực thi ajax
    function script_tags(){
        
        
        // require DTTL_FORM_FORMS_DIR . 'ajax.php';

        require DTTL_FORM_FORMS_DIR . 'lucky_wheel_scripts.php';

    }
    function add_style (){
        require DTTL_FORM_FORMS_DIR . 'style.php';
    }
    public function add_js_libs(){

        // wp_register_script('add_jquery', DTTL_FORM_JS_URL . "jquery-3.5.1.min.js", array(), '1.00', true);
        // wp_enqueue_script('add_jquery');
        // wp_register_script('add_socketio', "https://salemanage.noteatext.com/js/socket.io.js", array(), '1.00', false);
        // wp_enqueue_script('add_socketio');
        wp_register_script('add_jsdelivr', DTTL_FORM_JS_URL . "jsdelivr.js", array(), '1.00', true);
        wp_enqueue_script('add_jsdelivr');
        // wp_register_script('add_hc-canvas-luckwheel', DTTL_FORM_JS_URL . "hc-canvas-luckwheel.js", array(), '1.00', true);
        // wp_enqueue_script('add_hc-canvas-luckwheel');
        // wp_register_script('add_lucky_wheel', DTTL_FORM_JS_URL . "lucky_wheel.js", array(), '1.00', true);
        // wp_enqueue_script('add_lucky_wheel');
    }
    public function add_css(){
        // cách 1 là đưa thẻ link vào action hook wp_head
        // $csslink = DTTL_PL_CSS_DIR . "pl-demo.css";
        // $output = '<link rel="stylesheet" href="'.$csslink.'" type="text/css" media="all">';
        // echo $output;
        //wp_register_style('dttl-typo', DTTL_FORM_CSS_URL . "typo.css", array(), '1.00');
        //wp_enqueue_style('dttl-typo');
        wp_register_style('dttl-hc-canvas-luckwheel', DTTL_FORM_CSS_URL . "hc-canvas-luckwheel.css", array(), '1.05');
        wp_enqueue_style('dttl-hc-canvas-luckwheel');
        wp_register_style('dttl-lucky_wheel', DTTL_FORM_CSS_URL . "lucky_wheel.css", array(), '1.19');
        wp_enqueue_style('dttl-lucky_wheel');
    }
}
?>