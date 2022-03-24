<?php
class dttl_menu_dashboard{
    private $_menuSlug = 'dttl-form-game-setting';
    private $_setting_options; // biến chứa dữ liệu đang được lưu trong opiton

    private $_popupMenuSlug = 'dttlPopupMenuSlug';
    private $_popupOptions;
    function __construct(){
        add_action('admin_menu', array($this, 'settingMainMenu'));
        add_action('admin_init', array($this, 'createFields'));
        $this->_setting_options = get_option('dttl_form_game_settings');
        // echo '<pre>';
        // print_r($this->_setting_options);
        // echo '</pre>';
    }
    // 1. add nhóm menu mới vào admin menu
    function settingMainMenu(){
        $menuSlug = 'dttl-forms';
        // add 1 menu vào menu chính tham
        add_menu_page(
            'DTTL forms game', // tên trên tab browser
            'DTTL forms game', // tên menu
            // manage_options là phân quyền cho user có thể truy cập
            'manage_options', 
            $menuSlug, 
            // mảng ở dưới là phương thức kéo vào trang setting-page.php chứ giao diện của mennu này
            array($this, 'settingPage'), 
            DTTL_FORM_ICONS_URL . 'Bin-Empty-icon.png'
        );
    }
    // 2. giao dien setting
    public function settingPage(){
        // kéo vào giao diện của menu này
        require DTTL_FORM_MENUS_DIR . 'view-setting-page.php';
    }
    public function settingPopupDisplay(){
        // kéo vào giao diện của menu này
        require DTTL_FORM_MENUS_DIR . 'settingPopupDisplay.php';
    }
    // 3. đăng ký một setting
    public function createFields(){
        // tạo dòng dữ liệu trên bảng option
        // đăng ký 1 setting bằng register_setting. 
        // tham số thứ nhất là tên của setting đăng ký sau sử dụng tham số này để truyền vào settingpage
        // tham số thứ 2 là tên được đưa vào cột option name của bảng options. Tên này được sử dụng làm tên của mảng lưu vào database
        // giá trị của tham số này được đặt vào trong ô input nhập liệu name="dttl_form_settings['key_luu_data']
        // tham số thứ 3 là hàm sẽ sử dùng để validate dữ liệu
        register_setting( 'dttl_create_fields', 'dttl_form_game_settings', array($this, 'validate_setting') );
        $mainSection = 'dttl_form_setting_section';
        // đăng ký vùng nhập dữ liệu bằng add_settings_section
        // tham số thứ 1 là tên của section viết liền
        //tham số thứ 2 là title của section
        // tham số thứ 3 là 
        add_settings_section($mainSection, 'Game form setting section', array($this, 'main_section_view'), $this->_menuSlug);
        // add_settings_field( 'dttl_pl_uploadFile_field', 'my uploadFile field', array($this, 'view_uploadFile_input'), $this->_menuSlug, $mainSection );
        // phương thức add_settings_field dùng để đăng ký dòng nhập liệu
        // tham số thứ 2 là title của dòng
        // tham số thứ 3 là hàm dùng để tạo giao diện cho từng dòng nhập dữ liệu
        // tham số thứ 4 là slug của menu trên url của trình duyệt ở đây là dttl-pl-my-main-menu
        // tham số thứ 5 là tên của section chứa dòng nhập liệu này
        // tham số cuối cùng của hàm add_settings_field dùng để đưa một biến vào hàm tạo form 
        // ở đây là create_form từ tham số này ta có thể in ra bất kỳ input nào mà ko cần gọi thêm hàm
                
        add_settings_field( 
            'dttl_form_enable_checkbox', 
            'Enable form', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'enableForm') );
        // ##########################
        // thay background cho game
        add_settings_field( 
            'dttl_game_background', 
            'Game background', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'gameBackgound') );
        // ##########################
        add_settings_field( 
            'dttl_prizes', 
            'Giải thưởng', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'prizes') );
        // ##########################
        add_settings_field( 
            'dttl_percent', 
            'Tỷ lệ trúng', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'percent') );
        // ##########################
        add_settings_field( 
            'dttl_color', 
            'Màu sắc của ô quay số', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'color') );
        // ##########################
        add_settings_field( 
            'dttl_root', 
            'Mã nguồn', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'root') );
        // ##########################
        add_settings_field( 
            'dttl_content', 
            'Nội dung', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'content') );
        // ##########################
        add_settings_field( 
            'dttl_css', 
            'Css', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'css') );
        // ##########################
        add_settings_field( 
            'dttl_delay', 
            'Thời gian trễ', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection, 
            array('name' => 'delay') );

        // phương thức get settings errors sẽ lấy dữ liệu từ phương thức add_settings_error (chú ký chỉ dùng ở menu là menu chính)
        $tmp = get_settings_errors( $this->_menuSlug );


    }
    // đưa tham số cuối cùng vào add_setting_field ở trên để tạo form theo điều kiện
    // hàm dùng để tạo giao diện cho dòng nhập liệu
    public function createForm($args){
        $htmlObj = new dttlFormsHtml();
        switch ($args['name']){
            case 'enableForm':
                $arr = array();
                if($this->_setting_options['dttl_enable_form']){
                    $arr = array(
                        'checked'=> '',
                    );
                }
            
                echo $htmlObj->checkbox('dttl_form_game_settings[dttl_enable_form]',true, $arr);
                echo $htmlObj->pTag('Bật tắt game',array('class'=>'description'));
                break;
            case 'gameBackgound':
                echo $htmlObj->fileupload('dttl_game_background' );
                if (!empty($this->_setting_options['dttl_game_background'])){// kiểm tra file có tồn tại ko

                    echo '<img src="' . $this->_setting_options['dttl_game_background'] . '" width="200">';
                }
                echo $htmlObj->pTag('Upload ảnh cho nền game',array('class'=>'description'));
                break;
            case 'prizes':

                $arr = array(
                    'id'=> 'dttl_prizes',
                    'style'=> 'width: 1000px'
                );
                echo $htmlObj->textbox('dttl_form_game_settings[dttl_prizes]', $this->_setting_options['dttl_prizes'], 'Nhập vào 6 giải thưởng cách nhau bằng dấu phẩy', $arr);
                echo $htmlObj->pTag('Nhập vào 6 giải thưởng cách nhau bằng dấu phẩy',array('class'=>'description'));
                break;
            case 'percent':

                $arr = array(
                    'id'=> 'dttl_percent',
                    'style'=> 'width: 300px'
                );
                echo $htmlObj->textbox('dttl_form_game_settings[dttl_percent]', $this->_setting_options['dttl_percent'], 'Nhập vào tỉ lệ trúng thưởng của từng giải cách nhau bằng dấu phẩy', $arr);
                echo $htmlObj->pTag('Nhập vào tỉ lệ trúng thưởng của từng giải cách nhau bằng dấu phẩy',array('class'=>'description'));
                break;
            case 'color':

                $arr = array(
                    'id'=> 'dttl_color',
                    'style'=> 'width: 300px'
                );
                echo $htmlObj->textbox('dttl_form_game_settings[dttl_color]', $this->_setting_options['dttl_color'], 'Nhập vào 2 mã mầu cách nhau bằng dấu phẩy', $arr);
                echo $htmlObj->pTag('Nhập vào 2 mã mầu cách nhau bằng dấu phẩy',array('class'=>'description'));
                break;
            case 'root':

                $arr = array(
                    'id'=> 'dttl_root',
                    'style'=> 'width: 300px'
                );
                echo $htmlObj->textbox('dttl_form_game_settings[dttl_root]', $this->_setting_options['dttl_root'], 'Nhập vào mã nguồn', $arr);
                echo $htmlObj->pTag('Nhập vào mã nguồn "<strong>không thay đổi thông số này</strong>"',array('class'=>'description'));
                break;
            case 'content':

                $arr = array(
                    'id'=> 'dttl_content',
                    'style'=> 'width: 500px'
                );
                echo $htmlObj->textarea('dttl_form_game_settings[dttl_content]', $this->_setting_options['dttl_content'], $arr);
                echo $htmlObj->pTag('Nội dung hiển thị bên trên 2 ô input',array('class'=>'description'));
                break;
            case 'css':

                $arr = array(
                    'id'=> 'dttl_css',
                    'style'=> 'width: 500px'
                );
                echo $htmlObj->textarea('dttl_form_game_settings[dttl_css]', $this->_setting_options['dttl_css'], $arr);
                echo $htmlObj->pTag('Css cho game',array('class'=>'description'));
                break;
            case 'delay':

                $arr = array(
                    'id'=> 'dttl_delay',
                    'style'=> 'width: 100px'
                );
                echo $htmlObj->numberbox('dttl_form_game_settings[dttl_delay]', $this->_setting_options['dttl_delay'], $arr);
                echo $htmlObj->pTag('Thời gian delay hiện game tính bằng mili giây',array('class'=>'description'));
                break;
        }
    }
    
    public function main_section_view(){
        // echo "dinh ta tuan linh";
    }
    
    public function validate_setting($data_input){ // tham số đưa vào là dữ liệu đưa vào từ ô input
        // $a = explode(',', $data_input['dttl_prizes']);
        // echo '<pre>';
        // print_r(gettype(intval($data_input['dttl_delay'])));
        // echo '</pre>';
        // die();
        // kiểm tra chiều dài của chuỗi
        $errors = array();
        if(intval($data_input['dttl_delay']) < 1000){

            $errors['dttl_delay'] = 'Dữ liệu thời gian trễ không hợp lệ';
        }
        if(strlen($data_input['dttl_content']) < 25){
            $errors['dttl_content'] = 'Dữ liệu nội dung ít hơn 25 ký tự';
        }
        if(strlen($data_input['dttl_root']) < 5){
            $errors['dttl_root'] = 'Dữ liệu mã nguồn ít hơn 5 ký tự';
        }
        if(strlen($data_input['dttl_color']) != 13 && strlen($data_input['dttl_color']) != 10 && strlen($data_input['dttl_color']) != 7){
            $errors['dttl_color'] = 'Dữ liệu màu không hợp lệ';
        }
        if(count(explode(',', $data_input['dttl_prizes'])) != 6){
            $errors['dttl_prizes'] = 'Dữ liệu giải thưởng không hợp lệ';

        }
        if(count(explode(',', $data_input['dttl_percent']))!=6){

            $errors['dttl_percent'] = 'Dữ liệu tỷ lệ trúng thưởng không hợp lệ';
        }
        // kiểm tra phần mở rộng của file
        if (!empty($_FILES['dttl_game_background']['name'])){
            if($this->fileExtionsValidate($_FILES['dttl_game_background']['name'], 'JPG|PNG|GIF') == false){
                $errors['dttl_game_background'] = 'phần mở rộng không đúng với quy định';
            }else{
                if(!empty($this->_setting_options['dttl_path_gameBackground'])){
                    // hàm unlink() dùng để xóa file đã tồn tại trong uploads @ sẽ giúp ẩn đi lỗi nếu file ko tồn tại
                    @unlink($this->_setting_options['dttl_path_gameBackground']); 
                    
                }
                
                $override = array('test_form'=>false);
                $time = null;// biến này để tạo thư mục chứa file trong thư mục uploads trong wp-content biến này có định dạng 'yyyy/mm/dd' hoặc str str chỉ được phép có 4 ký tự null là để wp tự tạo
                $tmp = wp_handle_upload($_FILES['dttl_game_background'], $override, $time); // hàm wp_handle_upload() dùng để upload file lên 
                // $tmp chưa đường dẫn vật ly c:/ và đường dẫn tương đối hostmame/
                // echo '<pre>'
                // print_r($tmp);
                // echo '</pre>';
                $data_input['dttl_game_background'] = $tmp['url'];
                $data_input['dttl_path_gameBackground'] = $tmp['file'];
            }
        }else{
            // else rơi vào trường hợp chỉ thay đổi thông tin khác ko phải file upload
            $data_input['dttl_game_background'] = $this->_setting_options['dttl_game_background'];
            $data_input['dttl_path_gameBackground'] = $this->_setting_options['dttl_path_gameBackground'];
        }
        if (count($errors)>0){
            $data_input = $this->_setting_options; // đưa vào data_input giá trị cũ do có lỗi xảy ra
            $strErrors = '';
            // chuyển dữ liệu thành chuỗi rồi gửi ra ngoài để hiện thị
            foreach ($errors as $key => $val){
                $strErrors .= $val . '<br/>';
            }
            // chú ý nếu là 1 menu chính sẽ ko hiển thị lỗi cần sử dụng get_settings_errors để hiện thị ra ngoài
            add_settings_error( $this->_menuSlug, 'my-setting', $strErrors, 'error' ); 
        }else{
            // hiển thị cập nhật thành công (chỉ dùng khi là menu chính)
            add_settings_error( $this->_menuSlug, 'my-setting', 'success', 'updated' );
        }
        // ============================================================
        // có thể lưu dữ liệu thành nhiều dòng khác nhau trên bảng option bằng hàm update_option() và đối tượng $_POST
        return $data_input;
    }
    
    // kiểm tra phần mở rộng của file
    private function fileExtionsValidate($file_name, $file_type){
        $flag = false;
        $pattern = '/^.*\.(' . strtolower($file_type) . ')$/i'; // $file_type = JPG|PNG|GIF
        if(preg_match($pattern, strtolower($file_name)) == 1){
            $flag = true;
        }
        return $flag;
    }
}
?>