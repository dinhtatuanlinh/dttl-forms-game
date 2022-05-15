<?php
class dttl_metabox_featured_image{
    public function __construct(){
        add_action( 'add_meta_boxes', array($this, "create") );
        add_action('save_post_product', array($this, "save"));
    }

    public function create(){
        
        // phuong thuc metabox
        // tham so 1 la id cua metabox
        // tieu de cua metabox
        // la ham hien thi noi dung cua metabox
        // tham so thu 4 la noi hien thi vi du muon hien thi o post thi dat truyen vao la 'post'
        add_meta_box('dttl-mb-feature-image', 'Linh metabox', array($this, "display"), "product");
    }

    public function display($post){
        $htmlObj = new dttlFormsHtml();

        $arr = array(
            'id'=> 'dttl_percent',
            'style'=> 'width: 300px'
        );
        $value = get_post_meta($post->ID, '_dttl_mb_product')[0];
        // echo '<pre>';
        // print_r($value);
        // echo '</pre>';
        echo $htmlObj->textbox('dttl_mb_product[dttl_percent]', $value['dttl_percent'], '', $arr);
        echo $htmlObj->pTag('Nhập vào tỉ lệ trúng thưởng của từng giải cách nhau bằng dấu phẩy',array('class'=>'description'));
       
    }

    public function save($post_id){
        update_post_meta($post_id, "_dttl_mb_product", sanitize_text_field($_POST['dttl_mb_product']));
    }
}
?>