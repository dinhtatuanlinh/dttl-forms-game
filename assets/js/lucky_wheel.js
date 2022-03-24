  // get client info
  let Dia_chi_Xu_ly = 'https://ipinfo.io/json';
  let XHTTP = new XMLHttpRequest() || ActiveXObject();
  XHTTP.open("GET", Dia_chi_Xu_ly, false);
  XHTTP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
  XHTTP.send();
  let clientInfo = JSON.parse(XHTTP.responseText);


  var OSName = "Unknown OS";
  if (navigator.appVersion.indexOf("Win") != -1) OSName = "Windows";
  if (navigator.appVersion.indexOf("Mac") != -1) OSName = "MacOS";
  if (navigator.appVersion.indexOf("X11") != -1) OSName = "UNIX";
  if (navigator.appVersion.indexOf("Linux") != -1) OSName = "Linux";






  let startRoll = document.getElementById("startRoll");
  let phoneNumber = document.getElementById("phoneNumber");
  startRoll.style.display = 'none';
  let enableStart = () => {
      startRoll.style.display = 'block';
  };
  let sendData = (data) => {
      let result = {};
      result.data = data;
      result.os = OSName;
      result.phone = phoneNumber.value;
      result.clientInfo = clientInfo;
      result = JSON.stringify(result)
      console.log(result);
  }




  var isPercentage = true;
  var prizes = [{
          text: "Quà tặng trị giá 10 triệu",
          img: "images/Ao.png",
          number: 1, // 1%,
          percentpage: 0.01 // 1%
      },
      {
          text: "Chúc bạn may mắn lần sau",
          img: "images/miss.png",
          number: 1,
          percentpage: 0.05 // 5%
      },
      {
          text: "Quà tặng trị giá 10 triệu",
          img: "images/Vong.png",
          number: 1,
          percentpage: 0.1 // 10%
      },
      {
          text: "Chúc bạn may mắn lần sau",
          img: "images/miss.png",
          number: 1,
          percentpage: 0.24 // 24%
      },
      {
          text: "Quà tặng trị giá 10 triệu",
          img: "",
          percentpage: 0.6 // 60%
      },
      {
          text: "Chúc bạn may mắn lần sau",
          img: "images/miss.png",
          percentpage: 0.6 // 60%
      },
  ];
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
                  var rand = randomIndex(prizes);
                  var chances = rand;
                  callback && callback([rand, chances]);
              },
              gotBack: function(data) {
                  if (data == null) {
                      Swal.fire(
                          'Chương trình kết thúc',
                          'Đã hết phần thưởng',
                          'error'
                      )
                  } else if (data == 'Chúc bạn may mắn lần sau') {
                      Swal.fire(
                          'Bạn không trúng thưởng',
                          data,
                          'error'
                      )
                      sendData(data);
                  } else {
                      Swal.fire(
                          'Đã trúng giải',
                          data,
                          'success'
                      )
                      sendData(data);
                  }
              }
          });
      },
      false
  );

  function randomIndex(prizes) {
      if (isPercentage) {
          var counter = 1;
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
          // console.log(rand);
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
              case rand < prizes[4].percentpage + prizes[3].percentpage + prizes[2].percentpage + prizes[1].percentpage + prizes[0].percentpage:
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
          var counter = 0;
          for (let i = 0; i < prizes.length; i++) {
              if (prizes[i].number == 0) {
                  counter++
              }
          }
          if (counter == prizes.length) {
              return null
          }
          var rand = (Math.random() * (prizes.length)) >>> 0;
          if (prizes[rand].number != 0) {
              prizes[rand].number = prizes[rand].number - 1
              return rand
          } else {
              return randomIndex(prizes)
          }
      }
  }