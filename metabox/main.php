<?php
class dttl_metabox_product{
    private $_metabox_name = "dttl_metabox_product_options";
    private $_metabox_options = array();

    public function __construct(){
        $defaultOption = array(
            'dttl_mb_featured_image' => true
        );
        $this->_metabox_options = get_option($this->_metabox_name, $defaultOption);

        $this->featured_image();
    }

    public function featured_image(){
 
        // kiem tra option dttl_mb_featured_image co duoc bat ko
        if($this->_metabox_options['dttl_mb_featured_image'] == true){

            require_once DTTL_METABOX_PRODUCT_DIR . 'featured-image.php';
            new dttl_metabox_featured_image();
        }
    }
}