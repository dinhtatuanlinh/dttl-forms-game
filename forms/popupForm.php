<div id="behind_popup" onclick="closePopup()" style='display: none'>
</div>
<div id="dttlPopup" style='display: none'>
    <i class="fa fa-times close" aria-hidden="true" onclick="closePopup()"></i>
    <img src="<?php echo $this->_popupOptions['dttlPopupBackground']; ?>" alt="">
    <div id="popupForm">
        <input id="name" type="text" placeholder="Tên của bạn">
        <input id="phoneNumber" type="number" placeholder="Số điện thoại">
        <button id="submitPopup" onclick="submitPopupFunc()">Nhận ngay</button>
    </div>
</div>
<div id="voucherCode" style='display: none'>
    <i class="fa fa-times close" aria-hidden="true" onclick="closePopup()"></i>
    <img src="<?php echo $this->_popupOptions['dttlPopupVoucher']; ?>" alt="">
</div>