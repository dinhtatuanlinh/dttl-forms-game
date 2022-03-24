<?php
class dttl_popup_dashboard{
    private $_popupMenuSlug = 'dttlPopupMenuSlug';
    private $_popupOptions;
    function __construct(){
        add_action('admin_menu', array($this, 'settingMainMenu'));
        add_action('admin_init', array($this, 'createFields'));

        $this->_popupOptions = get_option('popupSettingOption');
        // echo '<pre>';
        // print_r($this->_setting_options);
        // echo '</pre>';
    }
    // 1. add nhóm menu mới vào admin menu
    function settingMainMenu(){
        $popupMenuSlug = 'dttl-popup';
        add_menu_page(
            'Popup', // tên trên tab browser
            'Popup', // tên menu
            // manage_options là phân quyền cho user có thể truy cập
            'manage_options', 
            $menuSlug, 
            // mảng ở dưới là phương thức kéo vào trang setting-page.php chứ giao diện của mennu này
            array($this, 'settingPopupDisplay'), 
            DTTL_FORM_ICONS_URL . 'Bin-Empty-icon.png'
        );
    }
    // 2. giao dien setting

    public function settingPopupDisplay(){
        // kéo vào giao diện của menu này
        require DTTL_FORM_MENUS_DIR . 'popupSettingDisplay.php';
    }
    // 3. đăng ký một setting
    public function createFields(){
        // @@@@@@@@@@@@@@@@@@@@
        // REGISTER POPUP MENU
        // @@@@@@@@@@@@@@@@@@@@
        register_setting( 'dttlPopupSetting', 'popupSettingOption', array($this, 'validatePopupSetting') );
        $dttlPopupSettingSection = 'dttlPopupSettingSection';
        $createPopupForm = 'createPopupForm';
        add_settings_section($dttlPopupSettingSection, 'Popup setting section', array($this, 'main_section_view'), $this->_popupMenuSlug);
        add_settings_field( 
            'dttlEnablePopup', 
            'Enable Popup', 
            array($this, $createPopupForm), 
            $this->_popupMenuSlug, 
            $dttlPopupSettingSection, 
            array('name' => 'enablePopup') );
        // ##########################
        // thay background cho popup
        add_settings_field( 
            'dttlPopupBackground', 
            'Popup background', 
            array($this, $createPopupForm), 
            $this->_popupMenuSlug, 
            $dttlPopupSettingSection, 
            array('name' => 'popupBackgound') );
        add_settings_field( 
            'dttlPopupVoucher', 
            'Popup voucher', 
            array($this, $createPopupForm), 
            $this->_popupMenuSlug, 
            $dttlPopupSettingSection, 
            array('name' => 'popupVoucher') );
        add_settings_field( 
            'dttl_root', 
            'Mã nguồn', 
            array($this, $createPopupForm), 
            $this->_popupMenuSlug, 
            $dttlPopupSettingSection, 
            array('name' => 'root') );
        add_settings_field( 
            'dttl_delay',
            'Thời gian trễ', 
            array($this, $createPopupForm), 
            $this->_popupMenuSlug, 
            $dttlPopupSettingSection, 
            array('name' => 'delay') );
        // phương thức get settings errors sẽ lấy dữ liệu từ phương thức add_settings_error (chú ký chỉ dùng ở menu là menu chính)
        $tmp = get_settings_errors( $this->_popupMenuSlug );
    }
    // đưa tham số cuối cùng vào add_setting_field ở trên để tạo form theo điều kiện
    // hàm dùng để tạo giao diện cho dòng nhập liệu

    public function createPopupForm($args){
        
        $htmlObj = new dttlFormsHtml();
        switch ($args['name']){
            case 'enablePopup':
                $arr = array();
                if($this->_popupOptions['dttl_enable_form']){
                    $arr = array(
                        'checked'=> '',
                    );
                }
            
                echo $htmlObj->checkbox('popupSettingOption[dttl_enable_form]',true, $arr);
                echo $htmlObj->pTag('Bật tắt popup',array('class'=>'description'));
                break;
            case 'popupBackgound':
                echo $htmlObj->fileupload('dttlPopupBackground');
                if (!empty($this->_popupOptions['dttlPopupBackground'])){// kiểm tra file có tồn tại ko

                    echo '<img src="' . $this->_popupOptions['dttlPopupBackground'] . '" width="200">';
                }
                echo $htmlObj->pTag('Upload ảnh cho nền popup',array('class'=>'description'));
                break;
            case 'popupVoucher':
                echo $htmlObj->fileupload('dttlPopupVoucher');
                if (!empty($this->_popupOptions['dttlPopupVoucher'])){// kiểm tra file có tồn tại ko

                    echo '<img src="' . $this->_popupOptions['dttlPopupVoucher'] . '" width="200">';
                }
                echo $htmlObj->pTag('Upload ảnh cho voucher',array('class'=>'description'));
                break;
            case 'delay':
                $arr = array(
                    'id'=> 'dttl_delay',
                    'style'=> 'width: 100px'
                );
                echo $htmlObj->numberbox('popupSettingOption[dttl_delay]', $this->_popupOptions['dttl_delay'], $arr);
                echo $htmlObj->pTag('Thời gian delay hiện popup tính bằng mili giây',array('class'=>'description'));
                break;
            case 'root':

                $arr = array(
                    'id'=> 'dttl_root',
                    'style'=> 'width: 300px'
                );
                echo $htmlObj->textbox('popupSettingOption[dttl_root]', $this->_popupOptions['dttl_root'], 'Nhập vào mã nguồn', $arr);
                echo $htmlObj->pTag('Nhập vào mã nguồn "<strong>không thay đổi thông số này</strong>"',array('class'=>'description'));
                break;
        }
    }
    public function main_section_view(){
        // echo "dinh ta tuan linh";
    }

    
    public function validatePopupSetting($data_input){
        // echo '<pre>';
        // print_r($data_input);

        // echo '</pre>';
        // die('stop');
        $errors = array();
        if(intval($data_input['dttl_delay']) < 1000){

            $errors['dttl_delay'] = 'Dữ liệu thời gian trễ không hợp lệ';
        }
        if(strlen($data_input['dttl_root']) < 5){
            $errors['dttl_root'] = 'Dữ liệu mã nguồn ít hơn 5 ký tự';
        }
        // kiểm tra phần mở rộng của file
        if (!empty($_FILES['dttlPopupBackground']['name'])){
            if($this->fileExtionsValidate($_FILES['dttlPopupBackground']['name'], 'JPG|PNG|GIF') == false){
                $errors['dttlPopupBackground'] = 'phần mở rộng không đúng với quy định';
            }else{
                if(!empty($this->_popupOptions['dttlPopupBackground'])){
                    // hàm unlink() dùng để xóa file đã tồn tại trong uploads @ sẽ giúp ẩn đi lỗi nếu file ko tồn tại
                    @unlink($this->_popupOptions['dttlPopupBackground']); 
                }
                $override = array('test_form'=>false);
                $time = null;// biến này để tạo thư mục chứa file trong thư mục uploads trong wp-content biến này có định dạng 'yyyy/mm/dd' hoặc str str chỉ được phép có 4 ký tự null là để wp tự tạo
                $tmp = wp_handle_upload($_FILES['dttlPopupBackground'], $override, $time); // hàm wp_handle_upload() dùng để upload file lên 
                // $tmp chưa đường dẫn vật ly c:/ và đường dẫn tương đối hostmame/
                // echo '<pre>'
                // print_r($tmp);
                // echo '</pre>';
                $data_input['dttlPopupBackground'] = $tmp['url'];
                $data_input['dttlPathPopupBackground'] = $tmp['file'];
            }
        }else{
            // else rơi vào trường hợp chỉ thay đổi thông tin khác ko phải file upload
            $data_input['dttlPopupBackground'] = $this->_popupOptions['dttlPopupBackground'];
            $data_input['dttlPathPopupBackground'] = $this->_popupOptions['dttlPathPopupBackground'];
        }

        if (!empty($_FILES['dttlPopupVoucher']['name'])){
            if($this->fileExtionsValidate($_FILES['dttlPopupVoucher']['name'], 'JPG|PNG|GIF') == false){
                $errors['dttlPopupVoucher'] = 'phần mở rộng không đúng với quy định';
            }else{
                if(!empty($this->_popupOptions['dttlPopupVoucher'])){
                    // hàm unlink() dùng để xóa file đã tồn tại trong uploads @ sẽ giúp ẩn đi lỗi nếu file ko tồn tại
                    @unlink($this->_popupOptions['dttlPopupVoucher']); 
                }
                $override = array('test_form'=>false);
                $time = null;// biến này để tạo thư mục chứa file trong thư mục uploads trong wp-content biến này có định dạng 'yyyy/mm/dd' hoặc str str chỉ được phép có 4 ký tự null là để wp tự tạo
                $tmp = wp_handle_upload($_FILES['dttlPopupVoucher'], $override, $time); // hàm wp_handle_upload() dùng để upload file lên 
                // $tmp chưa đường dẫn vật ly c:/ và đường dẫn tương đối hostmame/
                // echo '<pre>'
                // print_r($tmp);
                // echo '</pre>';
                $data_input['dttlPopupVoucher'] = $tmp['url'];
                $data_input['dttlPathPopupVoucher'] = $tmp['file'];
            }
        }else{
            // else rơi vào trường hợp chỉ thay đổi thông tin khác ko phải file upload
            $data_input['dttlPopupVoucher'] = $this->_popupOptions['dttlPopupVoucher'];
            $data_input['dttlPathPopupVoucher'] = $this->_popupOptions['dttlPathPopupVoucher'];
        }
        
        if (count($errors)>0){
            $data_input = $this->_popupOptions; // đưa vào data_input giá trị cũ do có lỗi xảy ra
            $strErrors = '';
            // chuyển dữ liệu thành chuỗi rồi gửi ra ngoài để hiện thị
            foreach ($errors as $key => $val){
                $strErrors .= $val . '<br/>';
            }
            // chú ý nếu là 1 menu chính sẽ ko hiển thị lỗi cần sử dụng get_settings_errors để hiện thị ra ngoài
            add_settings_error( $this->_popupMenuSlug, 'my-setting', $strErrors, 'error' ); 
        }else{
            // hiển thị cập nhật thành công (chỉ dùng khi là menu chính)
            add_settings_error( $this->_popupMenuSlug, 'my-setting', 'success', 'updated' );
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