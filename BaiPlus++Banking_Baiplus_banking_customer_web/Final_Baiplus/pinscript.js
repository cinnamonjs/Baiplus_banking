const inputs = document.querySelectorAll('.otp-field input');
let check = '123456';

inputs.forEach((input, index) => {
    input.dataset.index = index;
    input.addEventListener("paste", handleOnPaste);
    input.addEventListener("input", handleOtp); // Changed from "keyup" to "input"
    input.addEventListener("keydown", handleDelete);
});

function handleOnPaste(e) {
    const data = e.clipboardData.getData("text");
    const value = data.split("");
    if (value.length === inputs.length) {
        inputs.forEach((input, index) => {
            input.value = value[index];
        });
        console.log('paste', getOtpValue());
        submit();
    } else {
        alert("Wrong OTP size");
    }
}

function handleOtp(e) {
    const input = e.target;
    let value = input.value;
    let fieldIndex = input.dataset.index;

    // Check if value is numeric
    if (!isNumeric(value)) {
        input.value = ""; // Clear the input field
        return;
    }

    if (value.length > 0 && fieldIndex < inputs.length - 1) {
        input.nextElementSibling.focus();
        console.log('input', getOtpValue());
    }

    if (fieldIndex === inputs.length - 1) {
        submit();
    }
}

function handleDelete(e) {
    const input = e.target;
    let fieldIndex = input.dataset.index;

    if (e.key === "Backspace" && fieldIndex > 0 && input.value.length === 0) {
        input.previousElementSibling.focus();
        console.log('delete', getOtpValue());
    }
}

function submit() {
    let otp = getOtpValue();
    console.log("Submitting..." + otp);
    // inputs.forEach(input => {
    //     input.disabled = true;
    //     input.classList.add("disabled");
    // });
}

function getOtpValue() {
    let otp = "";
    inputs.forEach(input => {
        otp += input.value;
    });
    return otp;
}

function isNumeric(value) {
    return /^\d+$/.test(value);
}


function enterpin() { 


    var customerID;
    var customerPin;

  var srcAcRequest = new XMLHttpRequest();
  srcAcRequest.onreadystatechange = function() {
    if (srcAcRequest.readyState === XMLHttpRequest.DONE) {
      if (srcAcRequest.status === 200) {
        customerID = srcAcRequest.responseText; // Retrieve the customer ID from the response
        console.log(' cus id' + customerID);
        // console.log( 'i have customer =' +  customerID); // Access the customer ID in JavaScript
  
        var getaccountRequest = new XMLHttpRequest();
          getaccountRequest.onreadystatechange = function() {
              if (getaccountRequest.readyState === XMLHttpRequest.DONE) {
              if (getaccountRequest.status === 200) {
                  customerPin = getaccountRequest.responseText; // Retrieve the account IDs from the response and parse it
                  console.log( 'pin = ' +  customerPin);

                  const urlParams = new URLSearchParams(window.location.search);
                  const type = urlParams.get('type');
                  
                    var otp = getOtpValue();
                    console.log('otp' + otp + ' pin ' + customerPin);
                    if(otp === customerPin)
                    {
                      alert('Your pin correctly.')
                      if (type === 'transfer') {
                          window.location.href = 'receipt_tranfer.html';
                      }
                      else if ( type === 'withdraw') {
                          window.location.href = 'receipt_withdraw.html';
                      }
                      else if ( type === 'pay_bill') { 
                          window.location.href = 'receipt_paybill-phone.html';
                      }
                      else if (type === 'pay_loan') { 
                          window.location.href = 'receipt_payloan-phone.html';
                      }
                      
                    }
                    else { 
                      alert("Your pin doesn't match");
                  
                    }
                //   populateSelect(accountIDs); // Call the function to populate the select tag with options
              } else {
                  console.log('Error: ' + getaccountRequest.status);
              }
              }
          };
  
          getaccountRequest.open('POST', 'getpin.php', true); // PHP script to retrieve the session data
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

 




}