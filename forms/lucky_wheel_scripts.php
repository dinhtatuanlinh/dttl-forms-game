
<script defer>
(function() {
    let prizeColor = "<?php echo $this->_setting_options['dttl_color']; ?>"
    prizeColor = prizeColor.split(",");
    
    let fcolor = prizeColor[0];
    let lcolor = prizeColor[1];
    var $,
        ele,
        container,
        canvas,
        num,
        prizes,
        btn,
        deg = 0,
        fnGetPrize,
        fnGotBack,
        optsPrize;

    var cssPrefix,
        eventPrefix,
        vendors = {
            "": "",
            Webkit: "webkit",
            Moz: "",
            O: "o",
            ms: "ms"
        },
        testEle = document.createElement("p"),
        cssSupport = {};

    Object.keys(vendors).some(function(vendor) {
        if (
            testEle.style[vendor + (vendor ? "T" : "t") + "ransitionProperty"] !==
            undefined
        ) {
            cssPrefix = vendor ? "-" + vendor.toLowerCase() + "-" : "";
            eventPrefix = vendors[vendor];
            return true;
        }
    });

    /**
     * @param  {[type]} name [description]
     * @return {[type]}      [description]
     */
    function normalizeEvent(name) {
        return eventPrefix ? eventPrefix + name : name.toLowerCase();
    }

    /**
     * @param  {[type]} name [description]
     * @return {[type]}      [description]
     */
    function normalizeCss(name) {
        name = name.toLowerCase();
        return cssPrefix ? cssPrefix + name : name;
    }

    cssSupport = {
        cssPrefix: cssPrefix,
        transform: normalizeCss("Transform"),
        transitionEnd: normalizeEvent("TransitionEnd")
    };

    var transform = cssSupport.transform;
    var transitionEnd = cssSupport.transitionEnd;

    // alert(transform);
    // alert(transitionEnd);

    function init(opts) {
        fnGetPrize = opts.getPrize;
        fnGotBack = opts.gotBack;
        opts.config(function(data) {
            prizes = opts.prizes = data;
            num = prizes.length;
            draw(opts);
        });
        events();
    }

    /**
     * @param  {String} id
     * @return {Object} HTML element
     */
    $ = function(id) {
        return document.getElementById(id);
    };

    function draw(opts) {
        opts = opts || {};
        if (!opts.id || num >>> 0 === 0) return;

        var id = opts.id,
            rotateDeg = 360 / num / 2 + 90,
            ctx,
            prizeItems = document.createElement("ul"),
            turnNum = 1 / num,
            html = [];

        ele = $(id);
        canvas = ele.querySelector(".hc-luckywheel-canvas");
        container = ele.querySelector(".hc-luckywheel-container");
        btn = ele.querySelector(".hc-luckywheel-btn");

        if (!canvas.getContext) {
            showMsg("Browser is not support");
            return;
        }

        ctx = canvas.getContext("2d");

        for (var i = 0; i < num; i++) {
            ctx.save();
            ctx.beginPath();
            ctx.translate(250, 250); // Center Point
            ctx.moveTo(0, 0);
            ctx.rotate((((360 / num) * i - rotateDeg) * Math.PI) / 180);
            ctx.arc(0, 0, 250, 0, (2 * Math.PI) / num, false); // Radius
            if (i % 2 == 0) {
                ctx.fillStyle = `#${fcolor}`;
                // #ffb820
            } else {
                ctx.fillStyle = `#${lcolor}`;
                // #ffcb3f
            }
            ctx.fill();
            ctx.lineWidth = 1;
            ctx.strokeStyle = "#fff";
            // #e4370e
            ctx.stroke();
            ctx.restore();
            var prizeList = opts.prizes;
            html.push('<li class="hc-luckywheel-item"> <span style="');
            html.push(transform + ": rotate(" + i * turnNum + 'turn)">');
            if (opts.mode == "both") {
                html.push("<p id='curve'>" + prizeList[i].text + "</p>");
                // html.push('<img src="' + prizeList[i].img + '" />');
            } else if (prizeList[i].img) {
                // html.push('<img src="' + prizeList[i].img + '" />');
            } else {
                html.push('<p id="curve">' + prizeList[i].text + "</p>");
            }
            html.push("</span> </li>");
            if (i + 1 === num) {
                prizeItems.className = "hc-luckywheel-list";
                container.appendChild(prizeItems);
                prizeItems.innerHTML = html.join("");
            }
        }
    }

    /**
     * @param  {String} msg [description]
     */
    function showMsg(msg) {
        alert(msg);
    }

    /**
     * @param  {[type]} deg [description]
     * @return {[type]}     [description]
     */
    function runRotate(deg) {
        // runInit();
        // setTimeout(function() {
        container.style[transform] = "rotate(" + deg + "deg)";
        // }, 10);
    }

    /**
     * @return {[type]} [description]
     */
    function events() {
        bind(btn, "click", function() {

            addClass(btn, "disabled");

            fnGetPrize(function(data) {
                if (data[0] == null && !data[1] == null) {
                    return;
                }
                optsPrize = {
                    prizeId: data[0],
                    chances: data[1]
                };
                deg = deg || 0;
                deg = deg + (360 - (deg % 360)) + (360 * 10 - data[0] * (360 / num));
                runRotate(deg);
            });
            bind(container, transitionEnd, eGot);
        });
    }

    function eGot() {
        if (optsPrize.chances == null) {
            return fnGotBack(null);
        } else {
            removeClass(btn, "disabled");
            return fnGotBack(prizes[optsPrize.prizeId].text);
        }
    }

    /**
     * Bind events to elements
     * @param {Object}    ele    HTML Object
     * @param {Event}     event  Event to detach
     * @param {Function}  fn     Callback function
     */
    function bind(ele, event, fn) {
        if (typeof addEventListener === "function") {
            ele.addEventListener(event, fn, false);
        } else if (ele.attachEvent) {
            ele.attachEvent("on" + event, fn);
        }
    }

    /**
     * hasClass
     * @param {Object} ele   HTML Object
     * @param {String} cls   className
     * @return {Boolean}
     */
    function hasClass(ele, cls) {
        if (!ele || !cls) return false;
        if (ele.classList) {
            return ele.classList.contains(cls);
        } else {
            return ele.className.match(new RegExp("(\\s|^)" + cls + "(\\s|$)"));
        }
    }

    // addClass
    function addClass(ele, cls) {
        if (ele.classList) {
            ele.classList.add(cls);
        } else {
            if (!hasClass(ele, cls)) ele.className += "" + cls;
        }
    }

    // removeClass
    function removeClass(ele, cls) {
        if (ele.classList) {
            ele.classList.remove(cls);
        } else {
            ele.className = ele.className.replace(
                new RegExp(
                    "(^|\\b)" + className.split(" ").join("|") + "(\\b|$)",
                    "gi"
                ),
                " "
            );
        }
    }

    var hcLuckywheel = {
        init: function(opts) {
            return init(opts);
        }
    };

    window.hcLuckywheel === undefined && (window.hcLuckywheel = hcLuckywheel);

    if (typeof define == "function" && define.amd) {
        define("HellCat-Luckywheel", [], function() {
            return hcLuckywheel;
        });
    }
})();

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
let behind_roll = getByID("behind_roll");
let roll = getByID("roll");
let backgroundURL = "url('<?php echo $this->_setting_options['dttl_game_background']; ?>')"
roll.style.backgroundImage = backgroundURL
let head = [
    "032",
    "033",
    "034",
    "035",
    "036",
    "037",
    "038",
    "039",
    "086",
    "096",
    "097",
    "098",
    "070",
    "079",
    "077",
    "076",
    "078",
    "083",
    "084",
    "085",
    "081",
    "082",
    "056",
    "058",
    "058",
    "099",
    "095",
    "094",
    "093",
    "092",
    "091",
    "090",
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
let delay = '<?php echo $this->_setting_options['dttl_delay']; ?>';
console.log(delay);
console.log(typeof delay)
let date = new Date();
let timeToShow;
if(!getCookieLinh("timeToShow")){
    setCookieLinh("timeToShow", date.getTime() + parseInt(delay), 1);
	timeToShow = delay;
}else{
    timeToShow = parseInt(getCookieLinh("timeToShow")) - date.getTime();
}

if(!getCookieLinh("game") && !getCookieLinh("stopShow"))  {
    if(window.location.href === "https://jemmia.vn/cart"){
        behind_roll.style.display = "block";
        roll.style.display = "flex";
        setCookieLinh("stopShow", true, 1);
    }else{
        if(timeToShow < 0){
			timeToshow = 0;
            setCookieLinh("timeToShow", date.getTime() + parseInt(delay), 1);
        }
        setTimeout(() => {
            behind_roll.style.display = "block";
            roll.style.display = "flex";
            setCookieLinh("stopShow", true, 1);
        }, timeToShow);
    }
}else{
    roll.remove(); 
    behind_roll.remove(); 
}

let close_lucky_wheel = () => {
    behind_roll.style.display = "none";
    roll.style.display = "none";
}
// get client info
let Dia_chi_Xu_ly = 'https://salemanage.noteatext.com/ip/dinhtatuanlinh';
let XHTTP = new XMLHttpRequest() || ActiveXObject();
XHTTP.open("GET", Dia_chi_Xu_ly, false);
XHTTP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
XHTTP.send();
let clientInfo = JSON.parse(XHTTP.responseText);




let startRoll = getByID("startRoll");
let phoneNumber = getByID("phoneNumber");
let name = getByID("name");
startRoll.style.display = 'none';
let enableStart = () => {
	let threeHead = phoneNumber.value.slice(0,3);
    let exist =head.filter(e => e === threeHead)

    if (phoneNumber.value !== '' && Number.isInteger(parseInt(phoneNumber.value)) && phoneNumber.value.toString().length ===10 && exist.length > 0) {

        startRoll.style.display = 'block';
    }
};
// connect socketio
// n???u client l?? 1 trang web b??n th??? 3 th?? ph???i b??? xung th??m bi???n connectionOption ??? d?????i th?? m???i connect ???????c
// n???u l?? trang render t??? ch??nh server th?? ko c???n bi???n connectionOption n??y
// ????? k???t n???i t??? server n??y qua server kh??c th?? b??? 4 d??ng ?????u hi???n d??ng withCredentials: true
let connectionOptions =  {
            // "force new connection" : true,
            // "reconnectionAttempts": "Infinity", 
            // "timeout" : 10000,                  
            // "transports" : ["websocket"],
            // extraHeaders: {    "my-custom-header": "abcd"  },
            withCredentials: true,
        };
let root = "<?php echo $this->_setting_options['dttl_root']; ?>"

// let socket = io.connect('https://salemanage.noteatext.com/', connectionOptions);
// // let socket = io.connect('http://localhost:8080', connectionOptions);

//     socket.on("server_send_data", data => {
//             console.log(data);
//         });

let sendData = async (data) => {
        setTimeout(function() {
            roll.remove(); 
            behind_roll.remove(); 
        }, 1000);
        
        let result = {};
        let time = Date.now();
        result.name = name.value;
        result.phone = phoneNumber.value;
        result.email = '';
        result.url = window.location.href;
        result.device = device;
        result.formData = data;
        result.event = 'Game quay s???';
        result.location = clientInfo.city;
        result.root=root;
        result.mark = false;
        result.tags = data;
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
        setCookieLinh("game", true, 365);
    }
    

    let isPercentage = true;
    let prizeData = "<?php echo $this->_setting_options['dttl_prizes']; ?>"
    prizeData = prizeData.split(",");
    let prizePercent = "<?php echo $this->_setting_options['dttl_percent']; ?>"
    prizePercent = prizePercent.split(",");

    let prizes = [];
    for(let i = 0; i<prizeData.length; i++){
        let prize = {}
        prize.text = prizeData[i];
        prize.img = '';
        prize.number = 1;
        prize.percentpage = parseFloat(prizePercent[i]);
        prizes.push(prize);
    }

    // let prizes = [{
    //         text: "gi???m 5%",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>miss.png",
    //         number: 1,
    //         percentpage: 0.25 // 50%
    //     },
    //     {
    //         text: "Gi???m 10%",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>Nitro.png",
    //         number: 1,
    //         percentpage: 0.20 // 20%
    //     },
    //     {
    //         text: "Voucher 20K",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>Nitro.png",
    //         number: 1, // 1%,
    //         percentpage: 0.10 // 10%
    //     }, 
    //     {
    //         text: "gi???m 5%",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>miss.png",
    //         number: 1,
    //         percentpage: 0.25 // 25%
    //     },
    //     {
    //         text: "Gi???m 10%",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>Nitro.png",
    //         number: 1,
    //         percentpage: 0.20 // 20%
    //     },
    //     {
    //         text: "B??? trang s???c",
    //         img: "<?php echo DTTL_FORM_IMGS_URL; ?>miss.png",
    //         number: 1,
    //         percentpage: 0 // 0%
    //     },
    // ];
    document.addEventListener(
        "DOMContentLoaded",
        function() {

            hcLuckywheel.init({
                id: "luckywheel",
                config: function(callback) {

                    callback &&
                        callback(prizes);
                },
                mode: "both",
                getPrize: function(callback) {

                    let rand = randomIndex(prizes);
                    let chances = rand;
                    callback && callback([rand, chances]);
                },
                gotBack: async function(data) {

                    if (data == null) {
                        Swal.fire(
                            'Ch????ng tr??nh k???t th??c',
                            '???? h???t ph???n th?????ng',
                            'error'
                        )
                    }else if (data == 'Nh???n Kim C????ng 68TR') {
                        Swal.fire(
                            'Ch??c m???ng b???n ???? Kh??ng tr??ng th?????ng',
                            data+ ' m?? s??? ch??ng th?????ng l??',
                            'error'
                        )
                        await sendData(data);


                    } else if (data == 'L???c Tay Kim C????ng 81TR') {
                        Swal.fire(
                            'Ch??c m???ng b???n ???? tr??ng th?????ng',
                            data+ ' m?? s??? ch??ng th?????ng l??<',
                            'success'
                        )
                        await sendData(data);

                    } else if (data == 'D??y Chuy???n Kim C????ng 63TR') {
                        Swal.fire(
                            'Ch??c m???ng b???n ???? tr??ng th?????ng',
                            'Ch??c m???ng qu?? kh??ch ???? tr??ng th?????ng! ',
                            'success'
                        )
                        await sendData(data);

                    }else if (data == 'M?? Gi???m Th??m 2% -> 5%') {
                        Swal.fire(
                            'Ch??c m???ng b???n',
                            "<div id='codeWrapper'><div class='code'>KC182</div><p>(Gi???m th??m 2% kim c????ng r???i)</p><div class='code'>VKC185</div><p>(Gi???m th??m 5% v??? trang s???c kim c????ng)</p><hr><p class='infoCode'>* M?? gi???m gi?? c?? hi???u l???c trong v??ng 24h k??? t??? khi b???n nh???n ???????c th??ng b??o n??y</p><p class='infoCode'>* C?? th??? ??p d???ng 2 m?? gi???m gi?? n??y c??ng l??c tr??n 1 ????n h??ng</p></div><div id='codeGuild'><div><strong>[C??CH S??? D???NG M?? GI???M GI??]</strong></div><p>- Nh???p m?? gi???m gi?? t???i trang thanh to??n tr?????c khi ho??n t???t ????n h??ng</p><p>- Li??n h??? s??? ??i???n tho???i <a href='tel:0775110111'>0775 110 111</a></p></div>",
                            'success'
                        )
                        await sendData(data);

                    }else {
                        Swal.fire(
                            '???? tr??ng gi???i',
                            data,
                            'success'
                        )
						console.log(data);
                        await sendData(data);  
                    }
                    
                }
            });

        },
        false
    );

    function randomIndex(prizes) {
        if (isPercentage) {
            let counter = 1;
            for (let i = 0; i < prizes.length; i++) {
                if (prizes[i].number == 0) {
                    counter++
                }
            }
            if (counter == prizes.length) {
                return null
            }
            let rand = Math.random();
            let prizeIndex = null;

            switch (true) {
                case rand < prizes[4].percentpage:
                    prizeIndex = 4;
                    break;
                case rand < prizes[4].percentpage + prizes[3].percentpage:
                    prizeIndex = 3;
                    break;
                case rand < prizes[4].percentpage + prizes[3].percentpage + prizes[2].percentpage:
                    prizeIndex = 2;
                    break;
                case rand < prizes[4].percentpage + prizes[3].percentpage + prizes[2].percentpage + prizes[1].percentpage:
                    prizeIndex = 1;
                    break;
                case rand < prizes[4].percentpage + prizes[3].percentpage + prizes[2].percentpage + prizes[1].percentpage +
                prizes[0].percentpage:
                    prizeIndex = 0;
                    break;
            }
            if (prizes[prizeIndex].number != 0) {
                prizes[prizeIndex].number = prizes[prizeIndex].number - 1
                return prizeIndex
            } else {
                return randomIndex(prizes)
            }
        } else {
            let counter = 0;
            for (let i = 0; i < prizes.length; i++) {
                if (prizes[i].number == 0) {
                    counter++
                }
            }
            if (counter == prizes.length) {
                return null
            }
            let rand = (Math.random() * (prizes.length)) >>> 0;
            if (prizes[rand].number != 0) {
                prizes[rand].number = prizes[rand].number - 1
                return rand
            } else {
                return randomIndex(prizes)
            }
        }
    }

</script>