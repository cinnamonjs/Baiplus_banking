
function validateForm() {
  // Check if the input field is empty
  const inputField = document.getElementById("accountEndInput");
  const amountfield = document.getElementById('tranfer_amount');
  if (inputField.value.trim() === "" | amountfield.value.trim() ==='') {
    // Display an error message or perform other required validation steps
    alert("Please enter a value for the required field.");
    return false; // Prevent form submission
  }

  const checkfield = document.getElementById('result');
  console.log(checkfield.innerHTML);
  if( checkfield.innerHTML !== 'Data exists in the database.' ){
    alert("The account ID does not exits in the database. ");
    return false; // Prevent form submission
  }

  var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  if( amountfield.value > srcaccount.account_balance){
    alert("The amount is more than your balance. ");
    return false; // Prevent form submission
  }

  if( amountfield.value > 200000) { 
    alert("Unable to transfer more than 200,000 THB.");
    return false; // Prevent form submission
  }

  // console.log( typeof(amountfield)  );
  if( inputField == srcaccount.account_id ) { 
    alert("Unable to transfer funds to your own account.");
    return false; // Prevent form submission
  }
  enterCommit();
}

function validateNumericInput(event) {
  const inputValue = event.target.value;
  event.target.value = inputValue.replace(/[^0-9.]/g, "");
}

function handleKeyPress(event) {
    if (event.keyCode === 13) { // Check if Enter key is pressed (key code 13)
        console.log('wtf')
      event.preventDefault(); // Prevent form submission
      checkData(); // Call the checkData() function
    }
  }

  function handleAmountPress(event) {
    if (event.keyCode === 13) { // Check if Enter key is pressed (key code 13)
        console.log('wtf')
      event.preventDefault(); // Prevent form submission
      checkAmountData(); // Call the checkData() function
    }
  }
  
function checkData() {
    var userInput = document.getElementById('accountEndInput').value; // Get the user input
    
  
    var xhr = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText); // Parse the JSON response
          document.getElementById('result').textContent = response.message; // Display the result in the result div
          
           // Store the variable value in Local Storage
          sessionStorage.setItem('endaccount', JSON.stringify(response.data) );
          

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

    // xhr.onreadystatechange = function() {
    //     var response = xhr.responseText; // Get the response from the PHP script
    //     document.getElementById('result').textContent = response; // Display the result in the result div
        
    //     // You can perform further actions based on the response value
    //     if (response === 'Data exists in the database.') {
    //         console.log('Data exists in the database.')
            
        
    //     } else {
           
    //     }
    // };
  
    xhr.open('POST', 'check_accountend.php', true); // Specify the request details
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requestData = 'userInput=' + encodeURIComponent(userInput) + '&choice=' + encodeURIComponent(choice);
    xhr.send(requestData); // Send the user input and choice as data

    var image_request = new XMLHttpRequest(); // Create the XMLHttpRequest object
    image_request.open('POST', 'getimage.php', true);
    image_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requestImage = 'accountID=' + encodeURIComponent(userInput);
    image_request.send(requestImage);

    image_request.onreadystatechange = function() {
      if (image_request.readyState === XMLHttpRequest.DONE) {
        if (image_request.status === 200) {
          var customerENDimg = JSON.parse(image_request.responseText); // Parse the JSON response          
           // Store the variable value in Local Storage
          sessionStorage.setItem('endpic', JSON.stringify(customerENDimg.data) );
          

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
    // xhr.send('userInput=' + encodeURIComponent(userInput)); // Send the user input as data
    // xhr.send('choice=' + encodeURIComponent(choice));
  }

  function checkAmountData() {
    var amountInput = document.getElementById('tranfer_amount').value; // Get the user input
    console.log(amountInput);
    sessionStorage.setItem('amount', JSON.stringify(amountInput) );

  
  }


  function enterCommit(){
    const submitButton = document.getElementById('insert');
    submitButton.addEventListener('click', () => {
    console.log("everything is OK");
    // const variableValue = 'example';

   
    window.location.href = 'tranfer_commit.html';
    // console.log(endtransfer);
  });
  }


if (document.body.id === 'tranfer_commit-html') {
  // src account -----------------------------------
  var srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  document.querySelector('#src_account_id').innerHTML = srcaccount.account_id;
  document.querySelector('#src_account_name').innerHTML = srcaccount.account_name;
  // document.querySelector('#src_account_amount').innerHTML = srcaccount.account_balance;
  document.querySelector('#src_account_amount').innerHTML = parseFloat(srcaccount.account_balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 
  var srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
  document.querySelector('#src_img').src = 'Asset/img/' + srcpic.img;


    // end account -------------------------------------
    var endaccount =JSON.parse(sessionStorage.getItem('endaccount'));
    console.log(endaccount.account_id);
    const endid = document.querySelector('#accountEndID');
    endid.innerHTML = endaccount.account_id;
    const endname = document.querySelector('#accountEndName');
    endname.innerHTML = endaccount.account_name;

    const endamount = JSON.parse(sessionStorage.getItem('amount'));
    console.log(endamount);
    const btnamount = document.querySelector('#commit_amount');
    btnamount.innerHTML = parseFloat(endamount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 
    // console.log(endaccount.account_id);

    const endpicshow = document.querySelector('#endpic');
    const endpic = JSON.parse(sessionStorage.getItem('endpic'));
    console.log(endpic.img);
    endpicshow.src = 'Asset/img/' + endpic.img;
    

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

    // next button -----------------------------------------
    const cfbtn = document.querySelector('#cfbtn');


    cfbtn.addEventListener( 'click' , () => {

      sessionStorage.setItem('notetran', JSON.stringify(Notetran) );

      // insertdata();
      // decraseAmount();
      // increaseAmount();

      // window.location.href = 'receipt_tranfer.html?type=tranfer';
      window.location.href = 'pin.html?type=transfer';
    }) 

  }
 


function insertdata () {
  var endaccount =JSON.parse(sessionStorage.getItem('endaccount'));
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
    var requesttstData = 'tranamount=' + parseFloat(endamount) + '&trandetail=' + encodeURIComponent(Notetran) + '&transferor=' + encodeURIComponent(srcaccount.account_id) + '&receiver=' + encodeURIComponent(endaccount.account_id) + '&err=' + '&trans_type=Transfer' + '&bill_id=' + '&loan_id=';
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

function increaseAmount()
{

  var endaccount =JSON.parse(sessionStorage.getItem('endaccount'));
  const endamount = JSON.parse(sessionStorage.getItem('amount'));
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
  var requesttstData = 'tranamount=' + parseFloat(endamount) + '&accountid=' + encodeURIComponent(endaccount.account_id) ;
  increaseRequest.send(requesttstData); // Send the user input and choice as data
}

if (document.body.id === 'receipt_tranfer-html') {

  insertdata();
  decraseAmount();
  increaseAmount();

  const endaccount =JSON.parse(sessionStorage.getItem('endaccount'));
  const endamount = JSON.parse(sessionStorage.getItem('amount'));
  const srcaccount =JSON.parse(sessionStorage.getItem('srcaccount'));
  const srcbankname = JSON.parse(sessionStorage.getItem('srcbankname'));
  console.log('src = ' + srcbankname);

  

  document.querySelector('#recipe_amount').innerHTML =parseFloat(endamount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + " bath"; 


  const srcpic = JSON.parse(sessionStorage.getItem('srcpic'));
  document.querySelector('#srcimg').src = 'Asset/img/' + srcpic.img;
  document.querySelector('#src_accountid').innerHTML = srcaccount.account_id;
  document.querySelector('#src_name').innerHTML = srcaccount.account_name;
  document.querySelector('#src_bankname').innerHTML = srcbankname;





  const endpic = JSON.parse(sessionStorage.getItem('endpic'));
  const endbankname = JSON.parse(sessionStorage.getItem('endbankname'));
  console.log( 'end = ' + endbankname);
  document.querySelector('#endimg').src = 'Asset/img/' + endpic.img;
  document.querySelector('#end_accountid').innerHTML = endaccount.account_id;
  document.querySelector('#end_name').innerHTML = endaccount.account_name;
  document.querySelector('#end_bankname').innerHTML = endbankname;


  const datetime = JSON.parse(sessionStorage.getItem('tranfer_date'));
  document.querySelector('#recipe_date').innerHTML = datetime;



  const Notetran = JSON.parse(sessionStorage.getItem('notetran'));
  if (typeof Notetran === "undefined") {
    document.querySelector('#recipe_note').innerHTML = '';
  } else {
    document.querySelector('#recipe_note').innerHTML = Notetran;
  }
  


  
  


  // var increaseRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object
  
  // increaseRequest.onreadystatechange = function() {
  //   if (increaseRequest.readyState === XMLHttpRequest.DONE) {
  //     if (increaseRequest.status === 200) {
  //       var response = increaseRequest.responseText; // Parse the JSON response
  //       console.log(response +  'sucess');

  //     } else {
  //       console.log('Error: ' + increaseRequest.status);
  //     }
  //   }
  // };

  // increaseRequest.open('POST', 'increase_balance.php', true); // Specify the request details
  // increaseRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  // var requesttstData = 'tranamount=' + parseFloat(endamount) + '&accountid=' + encodeURIComponent(endaccount.account_id) ;
  // increaseRequest.send(requesttstData); // Send the user input and choice as data
  

}