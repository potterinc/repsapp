
// SHOW DATE
// document.getElementById("dateholder").innerHTML = new Date().toUTCString();

// ACCORDION: Toggle on And off
function myFunction(id) {
    document.getElementById(id).classList.toggle("my-show");
    document.getElementById(id).previousElementSibling.classList.toggle("my-Khaki");
}

// Sales
var totalcount = document.getElementById('totalcount');
totalcount = 0;
var totalAmount = 0;
var indAmount;
for (k = 1; k <= totalcount; k++) {
    indAmount = document.getElementById('totalSale' + k).innerHTML;
    //alert(indAmount); 
    indAmount = parseInt(indAmount);
    totalAmount += indAmount;

    if (k == totalcount) {
        document.getElementById("resultSumValue").innerHTML = totalAmount;
        //alert(totalAmount); 
    }
}

//setTimeout(function(){Sum();},3000);

//var tot = document.getElementById('total').value;
function debitCash() {
  var paidCash= document.getElementById("paidCash").value; 
  var paidTran= document.getElementById("paidTran").value; 
  var total= document.getElementById("total").value;
  paidCash=parseInt(paidCash); 
  paidTran=parseInt(paidTran); 
  total=parseInt(total); 
  var totalcredt=total- (paidCash+paidTran); 

  document.getElementById("totalCredit").value=totalcredt; 
  //alert(totalPay);
    TotalAmountPaids();
}

function debitTran(){
  var paidCash= document.getElementById("paidCash").value; 
  var paidTran= document.getElementById("paidTran").value; 
  var total= document.getElementById("total").value;
  paidCash=parseInt(paidCash); 
  paidTran=parseInt(paidTran); 
  total=parseInt(total); 
  var totalcredt=total- (paidCash+paidTran); 

  document.getElementById("totalCredit").value=totalcredt; 
  //alert(totalPay);
    TotalAmountPaids();
}

function creditChange() {
  var paidCash= document.getElementById("paidCash").value; 
  var paidTran= document.getElementById("paidTran").value; 
  var total= document.getElementById("total").value;
  paidCash=parseInt(paidCash); 
  paidTran=parseInt(paidTran); 
  total=parseInt(total);
  var totalcredt=total- (paidCash+paidTran); 

  document.getElementById("totalCredit").value=totalcredt; 
}

function TotalAmountPaids() {
    var paidCash = document.getElementById("paidCash").value;
    var paidTran = document.getElementById("paidTran").value;
    paidCash = parseInt(paidCash);
    paidTran = parseInt(paidTran);
    var Myresults = paidCash + paidTran;

    document.getElementById("totalAmountPaid").value = Myresults;
}


