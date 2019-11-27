var totalcountex=document.getElementById('totalcountex').innerHTML; 

  document.getElementById("sumLowQty").innerHTML=totalcountex; 

  var totalcountindex=document.getElementById('totalcountindex').innerHTML; 
  var allQty=0; 
  var indQty; 

  for (f=1; f<=totalcountindex; f++){
    indQty=document.getElementById('totalQty'+f).innerHTML; 
    //alert(indAmount); 
    indQty=parseInt(indQty);  
    allQty+=indQty; 

   if(f==totalcountindex){
    document.getElementById("sumSales").innerHTML=allQty; 
    //alert(totalAmount); 
   }
  } 

  var totalcountind=document.getElementById('totalcountind').innerHTML; 
  var allInc=0; 
  var indInc; 
  var allCred=0; 
  var indCred; 

  for (l=1; l<=totalcountind; l++){

    indInc=document.getElementById('totalInc'+l).innerHTML; 

    indCred=document.getElementById('totalCred'+l).innerHTML; 
    //alert(indAmount); 
    indInc=parseInt(indInc); 
    indCred=parseInt(indCred); 
    allInc+=indInc; 
    allCred+=indCred; 

   if(l==totalcountind){

    document.getElementById("sumIncome").innerHTML="&#8358; " + allInc; 

    document.getElementById("sumCred").innerHTML="&#8358; " + allCred; 
    //alert(totalAmount); 
   }
  } 

  var totalcountind1=document.getElementById('totalcountind1').innerHTML; 
  var allInc1=0; 
  var indInc1; 

  for (m=1; m<=totalcountind1; m++){

    indInc1=document.getElementById('totalInc1'+m).innerHTML; 
    indInc1=parseInt(indInc1); 
    allInc1+=indInc1; 

   if(m==totalcountind1){

    document.getElementById("sumIncome1").innerHTML="&#8358; " + allInc1; 
   }
  } 

  var accR= document.getElementById("accessR").innerHTML; 
  if (accR=="Supervisor" || accR=="Sales Person"){
    document.getElementById("mnguser").style.display = "none"; 
    document.getElementById("report").style.display = "none"; 
  } 

  if (accR=="Sales Person"){
    document.getElementById("larger").style.display = "none"; 
    document.getElementById("purchases").style.display = "none"; 
    document.getElementById("accounts").style.display = "none"; 
    document.getElementById("expense").style.display = "none"; 
    document.getElementById("balance").style.display = "none"; 
    document.getElementById("credits").style.display = "none"; 
    document.getElementById("returns").style.display = "none"; 
    document.getElementById("customers").style.display = "none"; 
    document.getElementById("suppliers").style.display = "none"; 
  } 
 
            $(document).ready(function () {
                var account = document.getElementById("privilege").value;
                switch (account) {
                    case "Supervisor":
                        $("#admin").hide();
                        $("#reporting").hide();
                        break;
                    case "Sales Person":
                        $("#admin").hide();
                        $("#reporting").css("display","none");
                        $("#order").hide();
                        $("#Ac").hide();
                        $("#cred").hide();
                        $("#ret").hide();
                        $("#cust").hide();
                        $("#sup").hide();
                        break;
                    default:
                        ;
                }
            });