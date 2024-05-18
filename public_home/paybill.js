if ( document.body.id === 'paybill-html'){ 

  var buttons = document.getElementsByClassName("pay__box");
    
    // Add click event listeners to each button
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function() {
            // Remove the "selected" class from all buttons
            for (var j = 0; j < buttons.length; j++) {
                buttons[j].classList.remove("selected");
            }
            
            // Apply the "selected" class to the clicked button
            this.classList.add("selected");
        });
    }

  document.getElementById("payForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    
    // Get the selected value
    var selectedValue = "";
    var selectedText = "";
    var selectedPic = '';
    var buttons = document.getElementsByClassName("pay__box");
    for (var i = 0; i < buttons.length; i++) {
        if (buttons[i].classList.contains("selected")) {
            selectedValue = buttons[i].value;
            selectedText = buttons[i].querySelector('.info.pay__box-label').innerText;
            selectedPic = buttons[i].querySelector('img').getAttribute("src");
            console.log(selectedPic);
            console.log('select ' + selectedValue);
            break;
        }
    }

    sessionStorage.setItem('billerpic', JSON.stringify(selectedPic) );
    sessionStorage.setItem('billerid', selectedValue);
    window.location.href = "paybill_number.html?selectedText=" + encodeURIComponent(selectedText);
  });

  var buttonsU = document.getElementsByClassName("pay__box grid");
    
    // Add click event listeners to each button
    for (var i = 0; i < buttonsU.length; i++) {
        buttonsU[i].addEventListener("click", function() {
            // Remove the "selected" class from all buttons
            for (var j = 0; j < buttonsU.length; j++) {
                buttonsU[j].classList.remove("selected");
            }
            
            // Apply the "selected" class to the clicked button
            this.classList.add("selected");
        });
    }

  document.getElementById("payUtilities").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    
    // Get the selected value
    var selectedValue = "";
    var selectedText = "";
    var selectedPic = '';
    var buttonsU = document.getElementsByClassName("pay__box");
    for (var i = 0; i < buttonsU.length; i++) {
        if (buttonsU[i].classList.contains("selected")) {
            selectedValue = buttonsU[i].value;
            selectedText = buttons[i].querySelector('.info.pay__box-label').innerText;
            selectedPic = buttons[i].querySelector('img').getAttribute("src");
            console.log('select ' + selectedValue);
            break;
        }
    }
    
    // Redirect to the next HTML page with the selected value as a query parameter
    sessionStorage.setItem('billerpic', JSON.stringify(selectedPic) );
    sessionStorage.setItem('billerid', selectedValue);
    window.location.href = "paybill_number.html?selectedText=" + encodeURIComponent(selectedText);
    });

    var form = document.getElementById("payLoan");
    var loanbtn = form.querySelector(".pay__box");
    document.getElementById("payLoan").addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent form submission
      
      var loanbtn = form.querySelector(".pay__box");
      
      // Redirect to the next HTML page with the selected value as a query parameter
      // sessionStorage.setItem('billerpic', JSON.stringify(selectedPic) );
      // sessionStorage.setItem('billerid', selectedValue);
      window.location.href = "payloan_number.html" ;
      });


}

if ( document.body.id === 'paybill_number-html') {

  // Select Account ------------------------------------------------------------------
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

    // ---------------------------------------------------------------------------------

    // ----- get biller  --------------------------------------------------------------

    const queryParams = new URLSearchParams(window.location.search);
    const selectedText = queryParams.get("selectedText");
    const billerpic = JSON.parse(sessionStorage.getItem('billerpic'));
    const billerid = sessionStorage.getItem('billerid');
    document.querySelector('#biller_name').innerHTML = selectedText;
    sessionStorage.setItem('biller_name', JSON.stringify(selectedText) );
    document.querySelector('#biller_pic').src = billerpic;

    document.querySelector('#select_biller_btn').addEventListener( 'click', () => {
      window.location.href = 'paybill.html';
    })

    // ---------- check bill id ----------------------


    function handleKeyPress(event) {
      if (event.keyCode === 13) { // Check if Enter key is pressed (key code 13)
          console.log('wtf')
        event.preventDefault(); // Prevent form submission
        checkData(); // Call the checkData() function
      }
    }


    function checkData() {
      var userInput = document.getElementById('bill_id').value; // Get the user input
      const billerid = sessionStorage.getItem('billerid');
      console.log(userInput);
      
    
      var xhr = new XMLHttpRequest(); // Create the XMLHttpRequest object
    
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText); // Parse the JSON response
            document.getElementById('result').textContent = response.message; // Display the result in the result div
            
             // Store the variable value in Local Storage
            sessionStorage.setItem('bill_info', JSON.stringify(response.data) );
            
  
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
            console.log('Error: ' + xhr.status);
          }
        }
      };
  
    
      xhr.open('POST', 'getbill.php', true); // Specify the request details
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      var requestData = 'bill_id=' + encodeURIComponent(userInput) + '&biller_id=' + encodeURIComponent(billerid);
      xhr.send(requestData); // Send the user input and choice as data
  
  
    }

    function validateForm() {
      // Check if the input field is empty
      const inputField = document.getElementById("bill_id");
      if (inputField.value.trim() === "") {
        // Display an error message or perform other required validation steps
        alert("Please enter bill number.");
        return false; // Prevent form submission
      }
    
      const checkfield = document.getElementById('result');
      console.log(checkfield.innerHTML);
      if( checkfield.innerHTML !== 'Bill exists.' ){
        alert("BIll does not exist or Bill number does not match with Biller.");
        return false; // Prevent form submission
      }
    
    
      window.location.href = 'paybill_commit.html';
    }

}


if( document.body.id === 'paybill_commit-html') { 


  // Select Account -------------------------------------------------------------------------------------
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


    // ---------------------------------- end select ---------------------

    // const billerid = sessionStorage.getItem('billerid');
    const billerpic = JSON.parse(sessionStorage.getItem('billerpic'));
    // const billerid = sessionStorage.getItem('billerid');
    document.querySelector('#biller_name').innerHTML = JSON.parse(sessionStorage.getItem('biller_name'));
    document.querySelector('#biller_pic').src = billerpic;
    
    const bill_info = JSON.parse(sessionStorage.getItem('bill_info'));
    document.querySelector('#bill_id').innerHTML = bill_info.bill_id ;
    document.querySelector('#bill_amount').innerHTML = parseFloat(bill_info.bill_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 
    


    //--------------------- Note -----------------
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

    const cfbtn = document.querySelector('#cfbtn');


    cfbtn.addEventListener( 'click' , () => {
      const bill_info = JSON.parse(sessionStorage.getItem('bill_info'));
      var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
      console.log( bill_info.bill_amount + ' - ' + srcaccount.account_balance);
      if( bill_info.bill_amount > srcaccount.account_balance){
        alert("The amount is more than your balance. ");
        return false; // Prevent form submission
      }
      else {
        sessionStorage.setItem('notetran', JSON.stringify(Notetran) );

        // insertdata();
        // decraseAmount();
        // increaseAmount();

          // window.location.href = 'receipt_paybill-phone.html';
          window.location.href = 'pin.html?type=pay_bill';
      }

      
    }) 

}


function insertdata () {
  var bill_info =JSON.parse(sessionStorage.getItem('bill_info'));
  const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
      var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
const endamount = bill_info.bill_amount;
      // const endamount = JSON.parse(sessionStorage.getItem('amount'));
    console.log(endamount);
    var tranfer_detail = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
    tranfer_detail.onreadystatechange = function() {
      if (tranfer_detail.readyState === XMLHttpRequest.DONE) {
        if (tranfer_detail.status === 200) {
          var response = JSON.parse(tranfer_detail.responseText); // Parse the JSON response
            sessionStorage.setItem('transaction_id', response.transaction_id);
            sessionStorage.setItem('tranfer_date', response.tran_date);
          console.log(response +  'sucess');

        } else {
          console.log('Error: ' + tranfer_detail.status);
        }
      }
    };
  
    tranfer_detail.open('POST', 'insert_transaction.php', true); // Specify the request details
    tranfer_detail.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requesttstData = 'tranamount=' + parseFloat(endamount) + '&trandetail=' + encodeURIComponent(Notetran) + '&transferor=' + encodeURIComponent(srcaccount.account_id) + '&receiver=' + encodeURIComponent(bill_info.account_id) + '&err=' + '&trans_type=Bill Payment' + '&bill_id=' + encodeURIComponent(bill_info.bill_id) + '&loan_id=';
    tranfer_detail.send(requesttstData); // Send the user input and choice as data
}

function decraseAmount()
{
  var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const endamount = JSON.parse(sessionStorage.getItem('bill_info'));
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
  var requesttstData = 'tranamount=' + parseFloat(endamount.bill_amount) + '&accountid=' + encodeURIComponent(srcaccount.account_id) ;
  tranfer_detail.send(requesttstData); // Send the user input and choice as data

}

function increaseAmount()
{

  var endaccount =JSON.parse(sessionStorage.getItem('bill_info'));
  const endamount = JSON.parse(sessionStorage.getItem('bill_info'));
  var increaseRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
  increaseRequest.onreadystatechange = function() {
    if (increaseRequest.readyState === XMLHttpRequest.DONE) {
      if (increaseRequest.status === 200) {
        var response = increaseRequest.responseText; // Parse the JSON response
        console.log(response +  'sucess');

      } else {
        console.log('Error: ' + increaseRequest.status);
      }
    }
  };

  increaseRequest.open('POST', 'increase_balance.php', true); // Specify the request details
  increaseRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var requesttstData = 'tranamount=' + parseFloat(endamount.bill_amount) + '&accountid=' + encodeURIComponent(endaccount.account_id) ;
  increaseRequest.send(requesttstData); // Send the user input and choice as data
}

if( document.body.id ===  'receipt_paybill-phone-html' ) {  


  insertdata();
  decraseAmount();
  increaseAmount();


  const endaccount =JSON.parse(sessionStorage.getItem('bill_info'));
  const endamount = endaccount.bill_amount;
  const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const datetime = sessionStorage.getItem('tranfer_date');
  const transaction_id = sessionStorage.getItem('transaction_id');
  const srcbankname = JSON.parse(sessionStorage.getItem('srcbankname'));
  console.log('src = ' + srcbankname);

  

  document.querySelector('#recipe_date').innerHTML = datetime;
  document.querySelector('#recipe_amount').innerHTML =parseFloat(endamount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 


  const srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
  document.querySelector('#srcimg').src = 'Asset/img/' + srcpic.img;
  document.querySelector('#src_accountid').innerHTML = srcaccount.account_id;
  document.querySelector('#src_name').innerHTML = srcaccount.account_name;
  document.querySelector('#src_bankname').innerHTML = srcbankname;





  const endpic = JSON.parse(sessionStorage.getItem('billerpic'));
  const endbankname = JSON.parse(sessionStorage.getItem('endbankname'));
  const biller_name = JSON.parse(sessionStorage.getItem('biller_name'));
  console.log( 'end = ' + endbankname);
  document.querySelector('#endimg').src =  endpic;
  document.querySelector('#end_billid').innerHTML = endaccount.bill_id;
  document.querySelector('#end_name').innerHTML = biller_name;
  document.querySelector('#transaction_id').innerHTML = transaction_id;



  const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
  if (typeof Notetran === "undefined") {
    document.querySelector('#recipe_note').innerHTML = '';
  } else {
    document.querySelector('#recipe_note').innerHTML = Notetran;
  }
}


if ( document.body.id === 'payloan_number-html') {

  // Select Account ------------------------------------------------------------------
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

    // ---------------------------------------------------------------------------------

    // ----- get biller  --------------------------------------------------------------

    // const queryParams = new URLSearchParams(window.location.search);
    // const selectedText = queryParams.get("selectedText");
    // const billerpic = JSON.parse(sessionStorage.getItem('billerpic'));
    // const billerid = sessionStorage.getItem('billerid');
    // document.querySelector('#biller_name').innerHTML = selectedText;
    // sessionStorage.setItem('biller_name', JSON.stringify(selectedText) );
    // document.querySelector('#biller_pic').src = billerpic;

    // document.querySelector('#select_biller_btn').addEventListener( 'click', () => {
    //   window.location.href = 'paybill.html';
    // })

    // ---------- check bill id ----------------------


    function handleKeyPress(event) {
      if (event.keyCode === 13) { // Check if Enter key is pressed (key code 13)
          console.log('wtf')
        event.preventDefault(); // Prevent form submission
        checkData(); // Call the checkData() function
      }
    }


    function checkData() {
      var userInput = document.getElementById('bill_id').value; // Get the user input
      const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
      // const billerid = sessionStorage.getItem('billerid');
      console.log(userInput);
      
    
      var xhr = new XMLHttpRequest(); // Create the XMLHttpRequest object
    
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText); // Parse the JSON response
            document.getElementById('result').textContent = response.message; // Display the result in the result div
            
             // Store the variable value in Local Storage
            sessionStorage.setItem('loan_info', JSON.stringify(response.data) );
            
  
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
            console.log('Error: ' + xhr.status);
          }
        }
      };
  
    
      xhr.open('POST', 'getloan.php', true); // Specify the request details
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      var requestData = 'loan_id=' + encodeURIComponent(userInput) + '&account_id=' + encodeURIComponent(srcaccount.account_id);
      xhr.send(requestData); // Send the user input and choice as data
  
  
    }

    function validateForm() {
      // Check if the input field is empty
      const inputField = document.getElementById("bill_id");
      if (inputField.value.trim() === "") {
        // Display an error message or perform other required validation steps
        alert("Please enter bill number.");
        return false; // Prevent form submission
      }
    
      const checkfield = document.getElementById('result');
      console.log(checkfield.innerHTML);
      if( checkfield.innerHTML !== 'Loan exists.' ){
        alert("Loan does not exist. Please enter the loan number");
        return false; // Prevent form submission
      }
    
    
      window.location.href = 'payloan_commit.html';
    }

}


if( document.body.id === 'payloan_commit-html') { 


  // Select Account -------------------------------------------------------------------------------------
  
  const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const srcbankname = JSON.parse(sessionStorage.getItem('srcbankname'));
  const srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
  document.querySelector('#src_img').src = 'Asset/img/' + srcpic.img;
  document.querySelector('#account__id').innerHTML = srcaccount.account_id;
  document.querySelector('#src_name').innerHTML = srcaccount.account_name;
  document.querySelector('#src_amount').innerHTML = srcaccount.account_balance;



    // ---------------------------------- end select ---------------------

    const loan_info = JSON.parse(sessionStorage.getItem('loan_info'));
    document.querySelector('#bill_id').innerHTML = loan_info.loan_id ;
    // const bill_info = JSON.parse(sessionStorage.getItem('bill_info'));
    document.querySelector('#bill_amount').innerHTML = parseFloat(loan_info.loan_amount / loan_info.loan_duration).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 
    


    //--------------------- Note -----------------
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

    const cfbtn = document.querySelector('#cfbtn');


    cfbtn.addEventListener( 'click' , () => {
      const bill_info = JSON.parse(sessionStorage.getItem('loan_info'));
      var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
      console.log( bill_info.loan_amount + ' - ' + srcaccount.account_balance);
      if( bill_info.loan_amount / bill_info.loan_duration > srcaccount.account_balance){
        alert("The amount is more than your balance. ");
        return false; // Prevent form submission
      }
      else {
        sessionStorage.setItem('notetran', JSON.stringify(Notetran) );

        // insertloandata();
        // decraseloanAmount();
      

          // window.location.href = 'receipt_payloan-phone.html';
          window.location.href = 'pin.html?type=pay_loan';
      }

      
    }) 

}

function insertloandata () {
  var loan_info =JSON.parse(sessionStorage.getItem('loan_info'));
  const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
      var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
const endamount = loan_info.loan_amount / loan_info.loan_duration;
      // const endamount = JSON.parse(sessionStorage.getItem('amount'));
    console.log(endamount);
    var tranfer_detail = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
    tranfer_detail.onreadystatechange = function() {
      if (tranfer_detail.readyState === XMLHttpRequest.DONE) {
        if (tranfer_detail.status === 200) {
          var response = JSON.parse(tranfer_detail.responseText); // Parse the JSON response
            sessionStorage.setItem('transaction_id', response.transaction_id);
            sessionStorage.setItem('tranfer_date', response.tran_date);
          console.log(response +  'sucess');

        } else {
          console.log('Error: ' + tranfer_detail.status);
        }
      }
    };
  
    tranfer_detail.open('POST', 'insert_transaction.php', true); // Specify the request details
    tranfer_detail.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requesttstData = 'tranamount=' + parseFloat(endamount) + '&trandetail=' + encodeURIComponent(Notetran) + '&transferor=' + encodeURIComponent(srcaccount.account_id) + '&receiver=' + '&err=' + '&trans_type=Loan Installment Payment' + '&bill_id='  + '&loan_id=' +  encodeURIComponent(loan_info.loan_id);
    tranfer_detail.send(requesttstData); // Send the user input and choice as data
}

function decraseloanAmount()
{
  var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const endamount = JSON.parse(sessionStorage.getItem('loan_info'));
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
  var requesttstData = 'tranamount=' + parseFloat(endamount.loan_amount / endamount.loan_duration) + '&accountid=' + encodeURIComponent(srcaccount.account_id) ;
  tranfer_detail.send(requesttstData); // Send the user input and choice as data

}

if( document.body.id ===  'receipt_payloan-phone-html' ) {  

  insertloandata();
  decraseloanAmount();
  
  const endaccount =JSON.parse(sessionStorage.getItem('loan_info'));
  const endamount = endaccount.loan_amount / endaccount.loan_duration;
  const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const datetime = sessionStorage.getItem('tranfer_date');
  const transaction_id = sessionStorage.getItem('transaction_id');
  const srcbankname = JSON.parse(sessionStorage.getItem('srcbankname'));
  console.log('src = ' + srcbankname);

  

  document.querySelector('#recipe_date').innerHTML = datetime;
  document.querySelector('#recipe_amount').innerHTML =parseFloat(endamount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 


  const srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
  document.querySelector('#srcimg').src = 'Asset/img/' + srcpic.img;
  document.querySelector('#src_accountid').innerHTML = srcaccount.account_id;
  document.querySelector('#src_name').innerHTML = srcaccount.account_name;
  document.querySelector('#src_bankname').innerHTML = srcbankname;





  // const endpic = JSON.parse(sessionStorage.getItem('billerpic'));
  // const endbankname = JSON.parse(sessionStorage.getItem('endbankname'));
  // const biller_name = JSON.parse(sessionStorage.getItem('biller_name'));
  // console.log( 'end = ' + endbankname);
  // document.querySelector('#endimg').src =  endpic;
  document.querySelector('#end_billid').innerHTML = endaccount.loan_id;
  document.querySelector('#loan_type').innerHTML = endaccount.loan_type;
  // console.log(transaction_id);
  // document.querySelector('#transaction_id').innerHTML = transaction_id;



  const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
  if (typeof Notetran === "undefined") {
    document.querySelector('#recipe_note').innerHTML = '';
  } else {
    document.querySelector('#recipe_note').innerHTML = Notetran;
  }
}