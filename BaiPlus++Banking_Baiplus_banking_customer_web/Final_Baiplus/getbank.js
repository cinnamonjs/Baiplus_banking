// Get the choice parameter from the URL
const urlParams = new URLSearchParams(window.location.search);
const choice = urlParams.get('choice');
console.log('choice = ', choice);

// Update the corresponding button based on the choice
const bankButton = document.querySelector('#bank_button');
console.log(bankButton);
bankButton.querySelector('#name_bank').innerHTML = choice;
sessionStorage.setItem('endbankname', JSON.stringify(choice) );
if (choice === 'Baiplus Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/Baiplus_final.png';
} else if (choice === 'Kasikorn Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/kbank.png';
} else if (choice === 'Bangkok Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/bangkok-bank-public-company-limited--600.png';
} else if (choice === 'Krungthai Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/Krung_Thai_Bank_logo.png';
} else if (choice === 'Siam Commercial Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/SCB.png';
} else if (choice === 'TMBThanachart Bank') {
    bankButton.querySelector('.withdraw__img ').src = 'asset/img/tmb-thanachart-bank-logo.png';
} 


// const srcaccountid = 0000000002;
// const srcaccountbank = 'Baiplus Bank';
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
    var select = document.getElementById('account_id_select'); // Get the select element by its ID
  
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

    var select = document.getElementById('account_id_select'); // Get the select element by its ID
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
   