if( document.body.id === "withdraw-html" )
{
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
                console.log( accountIDs);
                populateSelect(accountIDs); // Call the function to populate the select tag with options
            } else {
                console.log('Error: ' + getaccountRequest.status);
            }
            }
        };

        getaccountRequest.open('POST', 'getaccount.php', true); // PHP script to retrieve the session data
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



function populateSelect(accountIDs) {
    var select = document.getElementById('account__id'); // Get the select element by its ID
  
    for (var i = 0; i < accountIDs.length; i++) {
      var accountID = accountIDs[i].account_id.trim(); // Trim the account ID to remove leading/trailing spaces
  
      var option = document.createElement('option'); // Create a new option element
      option.value = accountID; // Set the value of the option
      option.text = accountID; // Set the text of the option
      select.appendChild(option); // Append the option to the select element
    }
  
    var selectedValue = select.value;
    console.log("Selected value: " + selectedValue);
    var bankName = null;

    for (var i = 0; i < accountIDs.length; i++) {
    if (accountIDs[i].account_id === selectedValue) {
        bankName = accountIDs[i].bank_name;
        break;
    }
    }

    console.log('Bank Name: ' + bankName);

    var select = document.getElementById('account__id'); // Get the select element by its ID
    select.addEventListener('change', function() {
        var selectedOption = select.options[select.selectedIndex]; // Get the currently selected option
        var selectedValue = selectedOption.value; // Get the value of the selected option
        //   var selectedText = selectedOption.text; // Get the text of the selected option
        console.log("Selected value: " + selectedValue);
    
        var bankName = null;
    
        for (var i = 0; i < accountIDs.length; i++) {
        if (accountIDs[i].account_id === selectedValue) {
            bankName = accountIDs[i].bank_name;
            break;
        }
        }
    
        console.log('Bank Name: ' + bankName);
    
        fetchsrc(selectedValue, bankName);
        //   console.log("Selected text: " + selectedText);
    });

    fetchsrc(selectedValue, bankName);

  }
  
  

 
    
  function fetchsrc(srcaccountid, srcaccountbank) {
    var srcac = new XMLHttpRequest(); // Create the XMLHttpRequest object
    sessionStorage.setItem('srcbankname', JSON.stringify(srcaccountbank) );
  
    srcac.onreadystatechange = function() {
      if (srcac.readyState === XMLHttpRequest.DONE) {
        if (srcac.status === 200) {
          var response = JSON.parse(srcac.responseText); // Parse the JSON response
          document.querySelector('#src_name').innerHTML = response.data.account_name ;
          document.querySelector('#src_amount').innerHTML =  parseFloat(response.data.account_balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 
        //   console.log(response.data.account_name +  'sucess');
        //   document.getElementById('result').textContent = response.message; // Display the result in the result div
          
           // Store the variable value in Local Storage
          sessionStorage.setItem('srcaccount', JSON.stringify(response.data) );
          

          // Access the variables from the JSON response
          var status = response.status;
          var message = response.message;
          
          // Perform further actions based on the response values
          if (status === 'success') {
            console.log('Data exists in the database.');
          } else if (status === 'error') {
            console.log('Data does not exist in the database.');
          }
        } else {
          console.log('Error: ' + srcac.status);
        }
      }
    };
  
    srcac.open('POST', 'check_accountend.php', true); // Specify the request details
    srcac.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requestSRCData = 'userInput=' + encodeURIComponent(srcaccountid) + '&choice=' + encodeURIComponent(srcaccountbank);
    srcac.send(requestSRCData); // Send the user input and choice as data


    var image_request = new XMLHttpRequest(); // Create the XMLHttpRequest object
    image_request.open('POST', 'getimage.php', true);
    image_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requestImage = 'accountID=' + encodeURIComponent(srcaccountid);
    image_request.send(requestImage);

    image_request.onreadystatechange = function() {
      if (image_request.readyState === XMLHttpRequest.DONE) {
        if (image_request.status === 200) {
          var customerENDimg = JSON.parse(image_request.responseText); // Parse the JSON response          
          document.querySelector('#src_img').src = 'Asset/img/' + customerENDimg.data.img;
           // Store the variable value in Local Storage
          sessionStorage.setItem('srcpic', JSON.stringify(customerENDimg.data) );
          

          // Access the variables from the JSON response
          var status = customerENDimg.status;
          var message = customerENDimg.message;
          
          // Perform further actions based on the response values
          if (status === 'success') {
            console.log('get img success.');
          } else if (status === 'error') {
            console.log('fail to get img.');
          }
        } else {
          console.log('Error: ' + image_request.status);
        }
      }
    };
  }
   

  const pinDisplay = document.querySelector('.money-display');
  pinDisplay.value = money;

  function checkAmountData() {
    var amountInput  = document.querySelector('.money-display').value; // Get the user input

    var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  if( amountInput > srcaccount.account_balance){
    alert("The amount is more than your balance. ");
    event.preventDefault();
    return false; // Prevent form submission
  }

    console.log(amountInput);
    sessionStorage.setItem('amount', JSON.stringify(amountInput) );

  
  }

  // const summitbtn = document.querySelector('#INSERT');
  // summitbtn.addEventListener( 'click', () => {
  //    checkAmountData();
  //    window.location.href = 'withdraw_commit.html';
  // });

}

 if(document.body.id === "withdraw_commit-html" ) 
 {
    let attempts = 0;

    const amount = JSON.parse(sessionStorage.getItem('amount'));
    const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
    const srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
    console.log('pic = ' + srcpic.img);


    document.querySelector('#src_img').src = 'Asset/img/' + srcpic.img ;
    document.querySelector('#src_id').innerHTML = srcaccount.account_id;
    document.querySelector('#src_name').innerHTML = srcaccount.account_name;
    document.querySelector('#amount').innerHTML = amount;

    var Notetran = null;
    function handleNotePress(event) {
      if (event.keyCode === 13) { // Check if Enter key is pressed (key code 13)
          console.log('wtf')
        event.preventDefault(); // Prevent form submission
        checkNoteData(); // Call the checkData() function
      }
    }
    
  function checkNoteData() {
      Notetran = document.getElementById('tranfer_note').value; // Get the user input
      // console.log(userInput);
    }


    // Get the button element
var confirmButton = document.getElementById("cfbtn");

// Add a click event listener to the button
confirmButton.addEventListener("click", function(event) {
  event.preventDefault(); // Prevent form submission
  

  // Show the success popup

  var randomNum = generateRandomNumber();
  if (attempts >= 5) {
    alert("Maximum attempts OTP for this transaction, please go back to home page.");
    window.location.href = 'withdraw.html';
}
  else{

    alert("Your OTP is " + randomNum);
  var successPopup = document.getElementById("success-popup");
  var count=0;
  var attempts=0;
  enterOTP(); // Call enterOTP again to reset the input popup
  
  // alert('foo');

  // alertBox.addEventListener( 'click', () => {
  //   console.log('here we are');
  // });
  
    

  
  function enterOTP() {
    successPopup.style.display = "block";

    var numericInput = document.getElementById("input_otp");
    count = 0;
    attempts = 0;
    document.addEventListener("click", closePopupOutside);

    numericInput.addEventListener("input", function(event) {
        var inputValue = event.target.value;
      
        // Remove any non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
      
        // Truncate the value to a maximum length of 6 numbers
        var truncatedValue = numericValue.substring(0, 6);
      
        // Update the input field value with the truncated numeric value
        numericInput.value = truncatedValue;
      });
  
      var confirmOTPButton = document.getElementById("cfotp");
      
  
      confirmOTPButton.addEventListener("click", function(event) {
          event.preventDefault(); // Prevent default link behavior
  
          var inputValue = numericInput.value;
          console.log(inputValue + ' attemp ' + attempts);
  
          // Check if the input matches the random number
          if (inputValue === randomNum.toString()) {
              alert("OTP matched successfully!");
              closePopup();
              sessionStorage.setItem('notetran', JSON.stringify(Notetran) );
              // insertdata();
              // decraseAmount();
              // window.location.href = 'receipt_withdraw.html';
              window.location.href = 'pin.html?type=withdraw';
          } else {
              attempts+=1;
  
              // Check if maximum attempts reached
              if (attempts >= 5) {
              alert("Maximum attempts reached. Closing the input pop-up.");
              closePopup();
              window.location.href = 'withdraw.html';

          }
      }
      });
  
  
      function closePopup() {
        // Code to close the input pop-up
        numericInput.value = ''; // Clear the input field
        successPopup.style.display = "none"; // Hide the pop-up
        document.removeEventListener("click", closePopupOutside); // Remove the event listener
      }

      function closePopupOutside(event) {

        count+=1;
        console.log('count = ' + count);
      

        if(count > 1) {
          if (!successPopup.contains(event.target)) {
            console.log('in if');
            closePopup();
          }
        }
       
        
      }

   
  }

  }

  

 
    
});


  



 }

 if( document.body.id === "receipt_withdraw-html") {

  insertdata();
  decraseAmount();

  const endamount = JSON.parse(sessionStorage.getItem('amount'));
  const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const datetime = JSON.parse(sessionStorage.getItem('tranfer_date'));
  const srcbankname = JSON.parse(sessionStorage.getItem('srcbankname'));
  expcode = new Date(JSON.parse(sessionStorage.getItem('tranfer_date')));
  expcode.setMinutes(expcode.getMinutes() + 2);

  
  document.querySelector('#recipe_date').innerHTML = datetime;
  document.querySelector('#recipe_amount').innerHTML =parseFloat(endamount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 

  document.querySelector('#with_code').innerHTML = generateRandomNumber();
  document.querySelector('#with_code_exp').innerHTML = expcode;

  document.querySelector('#src_id').innerHTML = srcaccount.account_id;
  document.querySelector('#src_name').innerHTML = srcaccount.account_name;
  document.querySelector('#src_bankname').innerHTML = srcbankname;
  document.querySelector('#src_type').innerHTML = srcaccount.account_type;

 }

    


 
 function insertdata () {
      var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
      const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
      const endamount = JSON.parse(sessionStorage.getItem('amount'));
    console.log(endamount);
    var tranfer_detail = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
    tranfer_detail.onreadystatechange = function() {
      if (tranfer_detail.readyState === XMLHttpRequest.DONE) {
        if (tranfer_detail.status === 200) {
          var response = tranfer_detail.responseText; // Parse the JSON response
          sessionStorage.setItem('tranfer_date', JSON.stringify(response) );
          console.log(response +  'sucess');

        } else {
          console.log('Error: ' + tranfer_detail.status);
        }
      }
    };
  
    tranfer_detail.open('POST', 'insert_transaction.php', true); // Specify the request details
    tranfer_detail.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requesttstData = 'tranamount=' + parseFloat(endamount) + '&trandetail=' + encodeURIComponent(Notetran) + '&transferor=' + encodeURIComponent(srcaccount.account_id) + '&receiver='  + '&err=' + '&trans_type=Withdrawal' + '&bill_id=' + '&loan_id=';
    tranfer_detail.send(requesttstData); // Send the user input and choice as data
}

function decraseAmount()
{
  var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const endamount = JSON.parse(sessionStorage.getItem('amount'));
  var tranfer_detail = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
  tranfer_detail.onreadystatechange = function() {
    if (tranfer_detail.readyState === XMLHttpRequest.DONE) {
      if (tranfer_detail.status === 200) {
        var response = tranfer_detail.responseText; // Parse the JSON response
        console.log(response +  'sucess');

      } else {
        console.log('Error: ' + tranfer_detail.status);
      }
    }
  };

  tranfer_detail.open('POST', 'decrease_balance.php', true); // Specify the request details
  tranfer_detail.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var requesttstData = 'tranamount=' + parseFloat(endamount) + '&accountid=' + encodeURIComponent(srcaccount.account_id) ;
  tranfer_detail.send(requesttstData); // Send the user input and choice as data

}

function generateRandomNumber() {
  var min = 100000; // Minimum value (inclusive)
  var max = 999999; // Maximum value (inclusive)

  var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
  return randomNumber;
}