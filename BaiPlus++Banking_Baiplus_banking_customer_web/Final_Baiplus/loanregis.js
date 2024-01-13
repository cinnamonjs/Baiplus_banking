var rate = document.querySelector('#loan-rate-value').innerHTML;
var selectElement = document.getElementById("loan_duration");
selectElement.addEventListener("change", function() {
    var selectedValue = selectElement.value;
    console.log(selectedValue);
  });

  var rangeElement = document.getElementById("loan-amount");

rangeElement.addEventListener("input", function() {
  var selectedValue = rangeElement.value;
  console.log(selectedValue);
});


var customerID

var srcAcRequest = new XMLHttpRequest();
srcAcRequest.onreadystatechange = function() {
  if (srcAcRequest.readyState === XMLHttpRequest.DONE) {
    if (srcAcRequest.status === 200) {
      customerID = srcAcRequest.responseText; // Retrieve the customer ID from the response
      console.log( 'i have customer =' +  customerID); // Access the customer ID in JavaScript

      var getaccountRequest = new XMLHttpRequest();
        getaccountRequest.onreadystatechange = function() {
            if (getaccountRequest.readyState === XMLHttpRequest.DONE) {
            if (getaccountRequest.status === 200) {
                var accountIDs = JSON.parse(getaccountRequest.responseText); // Retrieve the account IDs from the response and parse it
                console.log( accountIDs.Amount * 5);
                // accountIDs = 100;
                var rangeElement = document.getElementById("loan-amount");
                if( accountIDs.Amount > 25000 ) { 
                    rangeElement.setAttribute('step', 1000);
                }
                else if ( accountIDs.Amount > 100000) { 
                    rangeElement.setAttribute('step', 10000);
                }
                else if ( accountIDs.Amount > 1000000) { 
                    rangeElement.setAttribute('step', 100000);
                }
                else if( accountIDs.Amount > 10000000) { 
                    rangeElement.setAttribute('step', 1000000);
                }
                rangeElement.setAttribute('max', accountIDs.Amount * 5);

            } else {
                console.log('Error: ' + getaccountRequest.status);
            }
            }
        };

        getaccountRequest.open('POST', 'getamount.php', true); // PHP script to retrieve the session data
        getaccountRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var requestACCData = 'customerID=' + customerID ;
        getaccountRequest.send(requestACCData);
    } else {
      console.log('Error: ' + srcAcRequest.status);
    }
  }
};

srcAcRequest.open('POST', 'getAccountSession.php', true); // PHP script to retrieve the session data
srcAcRequest.send();


function submitloan()  { 
    var amount =  document.getElementById("loan-amount").value;
    var rate = document.querySelector('#loan-rate-value').innerHTML;
    var duration = document.querySelector('#loan_duration').value;

    console.log( duration);


    var getaccountRequest = new XMLHttpRequest();
        getaccountRequest.onreadystatechange = function() {
            if (getaccountRequest.readyState === XMLHttpRequest.DONE) {
            if (getaccountRequest.status === 200) {
                var response = getaccountRequest.responseText; // Retrieve the account IDs from the response and parse it
                console.log( response);
               
                if(response) { 
                    alert('Your loan will add to account' + response );
                }
                // alert('Your loan will add to account' + accountIDs );
            } else {
                console.log('Error: ' + getaccountRequest.status);
            }
            }
        };

        getaccountRequest.open('POST', 'insert_loan.php', true); // PHP script to retrieve the session data
        getaccountRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var requestACCData = 'customerID=' + customerID + '&amount=' + parseFloat(amount) + '&rate=' + parseFloat(rate) + '&duration=' + duration;
        getaccountRequest.send(requestACCData);


        

}

