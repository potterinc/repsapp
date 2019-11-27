// Get the modal
var modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("my-close");

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// Open Add-Customer Modal
var customers = document.getElementsByClassName('newcustomer');
var i;
for (i = 0; i < customers.length; i++) {
    customers[i].onclick = function () {
        modal.style.display = "block";
    }
}

// Open Add-Supplier Modal
var suppliers = document.getElementsByClassName('newsupplier');
var i;
for (i = 0; i < suppliers.length; i++) {
    suppliers[i].onclick = function () {
        modal.style.display = "block";
    }
}

// Open Cancel-Sales Modal
var cansales = document.getElementsByClassName('cancelsales');
var i;
for (i = 0; i < cansales.length; i++) {
    cansales[i].onclick = function () {
        modal.style.display = "block";
    }
}

// Open Cancel-Purchase Modal
var canpurchase = document.getElementsByClassName('cancelpurchase');
var i;
for (i = 0; i < canpurchase.length; i++) {
    canpurchase[i].onclick = function () {
        modal.style.display = "block";
    }
}

