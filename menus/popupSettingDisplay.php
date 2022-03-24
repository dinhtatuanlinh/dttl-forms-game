<div class="wrap">
    <?php
    // hiển thị thông báo của add_settings_errors (chú ký chỉ dùng ở menu là menu chính)
        settings_errors( $this->_popupMenuSlug, false, false );   
    ?>
    <h2>Popup setting</h2>
    <form method='post' action="options.php" enctype="multipart/form-data">
    <!-- action là options.php để wordpress tự động lưu dữ liệu vào bảng options -->
        <!-- phương thức  -->
        <?php // echo settings_fields('dttl_pl_options'); ?> 
        <!-- tham số truyền vào setting_fields là tên của setting đăng ký trong menu -->
        <?php echo settings_fields('dttlPopupSetting'); ?> 
        <!-- tạo ra các trường ẩn mặc định của wordpress -->
        <!-- tham số đưa vào là tham số đầu tiên của phương thức register_setting -->
        <?php echo do_settings_sections( $this->_popupMenuSlug ); ?>
        <!-- phương thức này hiển thị section ra ngoài trang menu -->
        <?php //do_settings_fields($this->_menuSlug, 'dttl_form_setting_section'); ?>

        <p class="submit">
            <input class="button button-primary" type="submit" name="submit" value="Save change">
        </p>
    </form>
</div>
