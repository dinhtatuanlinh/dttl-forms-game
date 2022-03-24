<div id="behind_roll" onclick="close_lucky_wheel()" style='display: none'>
</div>
<div class="wrapper typo" id="roll" style='display: none'>
    <i class="fa fa-times close" aria-hidden="true" onclick="close_lucky_wheel()"></i>
    <section id="luckywheel" class="hc-luckywheel">
        <div class="hc-luckywheel-container">
            <canvas class="hc-luckywheel-canvas" width="500px" height="500px">Vòng Xoay May Mắn</canvas>
        </div>
        <a id="startRoll" class="hc-luckywheel-btn" href="javascript:;">Xoay</a>
    </section>
    <div id="getPhone">
        <?php echo $this->_setting_options['dttl_content']; ?>
        <input id="name" type="text" placeholder="Tên của bạn">
        <input id="phoneNumber" type="number" placeholder="Số điện thoại">
        <button id="startRoll" onclick="enableStart()">Quay thưởng</button>
    </div>
</div>
