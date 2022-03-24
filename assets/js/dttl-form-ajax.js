let dttlForm = document.getElementById("dttl-form");
let data = { id: id, value: value }
data = JSON.stringify(data);
let Dia_chi_Xu_ly = "'. admin_url('admin-jax.php').'";
// console.log(data);
console.log(Dia_chi_Xu_ly);
let XHTTP = new XMLHttpRequest() || ActiveXObject();
XHTTP.open("POST", Dia_chi_Xu_ly, false);
XHTTP.setRequestHeader("Content-Type", "application/json; charset=utf-8");

// XHTTP.open("POST", Dia_chi_Xu_ly, false);
XHTTP.send(data);
// var backData = XHTTP.responseText;
// if (backData) {
//     location.reload();
// }
let sendAjax = (e) => {
    console.log(e.getElementById('dttl-phone').value);
}
alert('')