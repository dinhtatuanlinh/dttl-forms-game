<?php
    class dttl_create_table{
        function __construct(){

        }
        function create_table_by_dbDelta(){
            // ############################################
                    
            $table_name = $wpdb->prefix . 'dttl_forms';
            // kiểm tra trong database đã tồn tại bảng hay chưa nếu có sẽ trả về tên bảng đó
            if ($wpdb->get_var("SHOW TABLES LIKE `" . $table_name . "`") != $table_name){
                $sql = "CREATE TABLE `" . $table_name ."` (
                    `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) DEFAULT NULL,
                    PRIMARY KEY (`myid`))
                    ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
                    ";
                    // gọi các phương thức có trong file upgrade.php
                require_once ABSPATH . '/wp-admin/includes/upgrade.php';
                dbDelta($sql);// hàm thực thi câu lệnh sql
            }
        }
    }
    
?>