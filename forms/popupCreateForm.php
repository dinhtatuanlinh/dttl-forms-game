<?php
class DttlCreatePopupForm {
    private $_popupOptions; // biến chứa dữ liệu đang được lưu trong opiton
    public function __construct(){
        add_action('wp_body_open', array($this, 'popupForm'), 50);
        add_action( 'wp_enqueue_scripts', array($this, 'add_css'));
        // add_action('wp_head', array($this, 'add_style'), 50);
        // add_action( 'wp_enqueue_scripts', array($this, 'add_js_libs'));
        add_action('wp_footer', array($this, 'script_tags'), 61);
        $this->_popupOptions = get_option('popupSettingOption', false);
    }
    public function popupForm(){

        require DTTL_FORM_FORMS_DIR . 'popupForm.php';
    }
    // phải sử dụng jquery để thực thi ajax
    public function script_tags(){
        // echo __METHOD__;
        ?>
            <script defer>
                function setCookieLinh(cname, cvalue, exdays) {
                    const d = new Date();
                    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                };

                function getCookieLinh(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for (let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                };

                let getByID = document.getElementById.bind(document);
                let getByClass = document.getElementById.bind(document);
                let behind_popup = getByID("behind_popup");
                let dttlPopup = getByID("dttlPopup");
                let voucherCode = getByID("voucherCode");
                let head = [
                    "032","033","034","035","036","037",
                    "038","039","086","096","097","098",
                    "070","079","077","076","078","083",
                    "084","085","081","082","056","058",
                    "058","099","095","094","093","092",
                    "091","090",
                ];

                // check mobile or desktop
                let brow = navigator.userAgent;
                let device = '';
                if (/mobi/i.test(brow)) {
                    device = 'Mobile';
                    // Do something for mobile
                } else {
                    device = 'Desktop';
                }
                let closePopup = () => {
                    behind_popup.style.display = "none";
                    dttlPopup.style.display = "none";
                    voucherCode.style.display = "none";
                }
                let delay = '<?php echo $this->_popupOptions['dttl_delay']; ?>';
                let date = new Date();
                let timeToShow;
                if(!getCookieLinh("timeToShowPopup")){
                    setCookieLinh("timeToShowPopup", date.getTime() + parseInt(delay), 1);
                    timeToShow = delay;
                }else{
                    timeToShow = parseInt(getCookieLinh("timeToShowPopup")) - date.getTime();
                }
                
                if(!getCookieLinh("stopShowPopup") && !getCookieLinh("submittedForm"))  {
                    if(window.location.href === "https://jemmia.vn/cart"){

                        behind_popup.style.display = "block";
                        dttlPopup.style.display = "block";

                        setCookieLinh("stopShowPopup", true, 1);
                    }
                    else{
                        if(timeToShow < 0){
                            timeToshow = 0;
                            setCookieLinh("timeToShowPopup", date.getTime() + parseInt(delay), 1);
                        }
                        setTimeout(() => {
                            behind_popup.style.display = "block";
                            dttlPopup.style.display = "flex";
                            setCookieLinh("stopShowPopup", true, 1);
                        }, timeToShow);
                    }
                }else{
                    dttlPopup.remove(); 
                    behind_popup.remove(); 
                }
                // get client info
                let Dia_chi_Xu_ly = 'https://salemanage.noteatext.com/ip/dinhtatuanlinh';
                let XHTTP = new XMLHttpRequest() || ActiveXObject();
                XHTTP.open("GET", Dia_chi_Xu_ly, false);
                XHTTP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
                XHTTP.send();
                let clientInfo = JSON.parse(XHTTP.responseText);

                let submitPopup = getByID("submitPopup");
                let phoneNumber = getByID("phoneNumber");
                let name = getByID("name");
                
                // connect socketio
                // nếu client là 1 trang web bên thứ 3 thì phải bổ xung thêm biến connectionOption ở dưới thì mới connect được
                // nếu là trang render từ chính server thì ko cần biến connectionOption này
                // để kết nối từ server này qua server khác thì bỏ 4 dòng đầu hiện dòng withCredentials: true
                let connectionOptions =  {
                            // "force new connection" : true,
                            // "reconnectionAttempts": "Infinity", 
                            // "timeout" : 10000,                  
                            // "transports" : ["websocket"],
                            // extraHeaders: {    "my-custom-header": "abcd"  },
                            withCredentials: true,
                        };
                let root = "<?php echo $this->_popupOptions['dttl_root']; ?>"

                // let socket = io.connect('https://salemanage.noteatext.com/', connectionOptions);
                // // let socket = io.connect('http://localhost:8080', connectionOptions);

                //     socket.on("server_send_data", data => {
                //             console.log(data);
                //         });

                let sendData = async () => {
                        
                        let result = {};
                        let time = Date.now();
                        result.name = name.value;
                        result.phone = phoneNumber.value;
                        result.email = '';
                        result.url = window.location.href;
                        result.device = device;
                        result.formData = '';
                        result.event = 'Give voucher';
                        result.location = clientInfo.city;
                        result.root=root;
                        result.mark = false;
                        result.tags = '';
                        result.createdtime = `${time}`;

                        // await socket.emit('send_customer_data_from_jemmia', result); 
                        console.log(result);
                        let sendGameDataUrl = 'https://salemanage.noteatext.com/gameluckywheel';
                        result = JSON.stringify(result)
                        let XHTTP = new XMLHttpRequest() || ActiveXObject();
                        XHTTP.open("POST", sendGameDataUrl, false);
                        XHTTP.setRequestHeader("Content-Type", "application/json; charset=utf-8");
                        XHTTP.send(result);
                        let backData = XHTTP.responseText;
                        console.log(backData);
                    }
                let submitPopupFunc = () => {
                    let threeHead = phoneNumber.value.slice(0,3);
                    let exist =head.filter(e => e === threeHead)

                    if (phoneNumber.value !== '' && Number.isInteger(parseInt(phoneNumber.value)) && phoneNumber.value.toString().length ===10 && exist.length > 0) {

                        dttlPopup.remove(); 
                        voucherCode.style.display = "block";
                        setCookieLinh("submittedForm", true, 365);
                        sendData();
                    }else{
                        alert('Xin vui lòng nhập đúng số điện thoại của bạn!');
                    }
                };
            </script>
        <?php
    }
    public function add_style (){
        // require DTTL_FORM_FORMS_DIR . 'style.php';
    }
    public function add_js_libs(){
        
    }
    public function add_css(){
        
        wp_register_style('dttlPopup', DTTL_FORM_CSS_URL . "popup.css", array(), '1.11');
        wp_enqueue_style('dttlPopup');
    }
}
?>