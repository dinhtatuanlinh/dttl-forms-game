
<script>
    let name = document.getElementById("dttl-form-name");
    let url = "<?php echo admin_url('admin-ajax.php');?>";//dữ liệu sẽ được truyền tới
    let sendAjax = () => {
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "'.admin_url('admin-ajax.php').'",
                data: {
                    action: "post_contact",//post_contact là tên của hàm dùng để lấy data truyền tới ajax
                    "name": name.value,// các trường phía dưới là data truyền lên server
        
                },
                beforeSend: function() {
                    $("#btnContactUs").text("Sending...");
                    $("#btnContactUs").attr("disabled", !0)
                },
                success: function(data) {
                    $("#btnContactUs").text("Send Message");
                    $("#btnContactUs").attr("disabled", !1);
                    alert(data.message);
                    // app.trigger("reset");
                },
                error: function(error) {
                    console.log(error)
                }
            })
    }
</script>
