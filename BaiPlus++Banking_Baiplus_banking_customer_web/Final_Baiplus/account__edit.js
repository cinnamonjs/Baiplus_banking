const accountbtn = document.querySelector('#account').querySelectorAll('.form-control');
var firstname;
var lastname;
var email;
var phone;

// Loop through the elements in the accountbtn collection
accountbtn.forEach(element => {
  // Check the name attribute of each element
  if (element.getAttribute('name') === 'firstname') {
    firstname = element.value;
  } else if (element.getAttribute('name') === 'lastname') {
    lastname = element.value;
  } else if (element.getAttribute('name') === 'email') {
    email = element.value;
  } else if (element.getAttribute('name') === 'phone') {
    phone = element.value;
  }
});

const dateOfBirthInput = document.querySelector('.col-md-6 .form-group input[type="date"]');
var date;

dateOfBirthInput.addEventListener('change', function(event) {
  date = event.target.value;
  console.log(date);
});

const maleRadioButton = document.querySelector('#check-male');
const femaleRadioButton = document.querySelector('#check-female');
var gender;

// maleRadioButton.addEventListener('change', function() {
//   if (maleRadioButton.checked) {
//     gender = 'Male';
//     console.log(gender);
//   }
// });

// femaleRadioButton.addEventListener('change', function() {
//   if (femaleRadioButton.checked) {
//     gender = 'Female';
//     console.log(gender);
//   }
// });


var customerID;
var customer_info;

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
          customer_info = JSON.parse(getaccountRequest.responseText).data; // Retrieve the account IDs from the response and parse it
          console.log(customer_info);

          // ----------------------------- get defualt value 
          const accountbtn = document.querySelector('#account').querySelectorAll('.form-control');

          // Loop through the elements in the accountbtn collection
          accountbtn.forEach(element => {
            // Check the name attribute of each element
            if (element.getAttribute('name') === 'firstname') {
              element.value = customer_info.customer_fname;
            } else if (element.getAttribute('name') === 'lastname') {
              element.value = customer_info.customer_lname;
            } else if (element.getAttribute('name') === 'email') {
              element.value = customer_info.customer_email;
            } else if (element.getAttribute('name') === 'phone') {
              element.value = customer_info.customer_phone;
            }
          });

          const dateOfBirthInput = document.querySelector('.col-md-6 .form-group input[type="date"]');
          dateOfBirthInput.value = customer_info.customer_DOB;

          const maleRadioButton = document.querySelector('#check-male');
          const femaleRadioButton = document.querySelector('#check-female');

          if( customer_info.customer_gender === 'Male') { 
            maleRadioButton.checked = true;
          }
          else if ( customer_info.customer_gender === 'Female') { 
            femaleRadioButton.checked = true;
          }

          document.querySelector('#cus_address').value = customer_info.customer_address;
          document.querySelector('#cus_postcode').value = customer_info.customer_postcode;
          document.querySelector('#cus_salary').value = customer_info.salary;

          document.querySelector('#cus_img').src = 'Asset/img/' + customer_info.img;
          document.querySelector('#cus_fname').innerHTML = customer_info.customer_fname;
          document.querySelector('#cus_lname').innerHTML = customer_info.customer_lname;


        } else {
          console.log('Error: ' + getaccountRequest.status);
        }
      }
    };

    getaccountRequest.open('POST', 'getcustomer.php', true); // PHP script to retrieve the session data
    getaccountRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var requestACCData = 'customerID=' + encodeURIComponent(customerID);
    getaccountRequest.send(requestACCData);
  } else {
    console.log('Error: ' + srcAcRequest.status);
  }
}
};

srcAcRequest.open('POST', 'getAccountSession.php', true); // PHP script to retrieve the session data
srcAcRequest.send();



function sendAccount() {  


  const accountbtn = document.querySelector('#account').querySelectorAll('.form-control');
  var firstname;
  var lastname;
  var email;
  var phone;

// Loop through the elements in the accountbtn collection
accountbtn.forEach(element => {
  // Check the name attribute of each element
  if (element.getAttribute('name') === 'firstname') {
    firstname = element.value;
  } else if (element.getAttribute('name') === 'lastname') {
    lastname = element.value;
  } else if (element.getAttribute('name') === 'email') {
    email = element.value;
  } else if (element.getAttribute('name') === 'phone') {
    phone = element.value;
  }
});

const dateOfBirthInput = document.querySelector('.col-md-6 .form-group input[type="date"]');
var date = dateOfBirthInput.value;



const maleRadioButton = document.querySelector('#check-male');
const femaleRadioButton = document.querySelector('#check-female');
var gender;
  if( maleRadioButton.checked) {
     gender = 'Male';
  }
  else if ( femaleRadioButton.checked) { 
    gender = 'Female';
  }

  console.log(firstname + lastname + email + phone + date + gender);
  console.log(customerID);

// --------------------------- update data ------------------------
var updateRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object

updateRequest.onreadystatechange = function() {
  if (updateRequest.readyState === XMLHttpRequest.DONE) {
    if (updateRequest.status === 200) {
      var response = JSON.parse(updateRequest.responseText); // Parse the JSON response
      console.log('Response:', response.status);

      // var status = response.status;
      // var message = response.message;

      if (response.status === 'success') {
        alert('Update information success.');
        console.log('Data exists in the database.');
      } else if (response.status === 'error') {
        console.log('Data does not exist in the database.');
      }
    } else {
      console.log('Error: ' + updateRequest.status);
    }
  }
};

updateRequest.open('POST', 'updatecustomer.php', true);
updateRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
var requestData = 'firstname=' + encodeURIComponent(firstname) +
                  '&lastname=' + encodeURIComponent(lastname) +
                  '&email=' + encodeURIComponent(email) +
                  '&phone=' + encodeURIComponent(phone) +
                  '&date=' + encodeURIComponent(date) +
                  '&gender=' + encodeURIComponent(gender) + 
                  '&userInput=' + encodeURIComponent(customerID);
updateRequest.send(requestData);


}


// PAss word ----------------------------------------------------


function updatepass() {  

  const passbtn = document.querySelector('#password').querySelectorAll('.form-control');
  var oldpass;
  var newpass;
  var newpassconfirm;
  var oldpin;
  var newpin;
  var newpinconfirm;
  console.log(customerID + 'cus');


// Loop through the elements in the accountbtn collection
passbtn.forEach(element => {
  // Check the name attribute of each element
  if (element.getAttribute('name') === 'old_password') {
    oldpass = element.value;
  } else if (element.getAttribute('name') === 'new_password') {
    newpass = element.value;
  } else if (element.getAttribute('name') === 'new_confirm_password') {
    newpassconfirm = element.value;
  } else if (element.getAttribute('name') === 'old_pin') {
    oldpin = element.value;
  } else if (element.getAttribute('name') === 'new_pin') {
    newpin = element.value;
  } else if (element.getAttribute('name') === 'new_confirm_pin') {
    newpinconfirm = element.value;
  }
});

  var updateRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object

updateRequest.onreadystatechange = function() {
  if (updateRequest.readyState === XMLHttpRequest.DONE) {
    if (updateRequest.status === 200) {
      var response = JSON.parse(updateRequest.responseText); // Parse the JSON response
      console.log('Response:'+ response.message);
      

      // var status = response.status;
      // var message = response.message;

      if (response.status === 'success') {
        alert(response.message);
        console.log('Data exists in the database.');
      } else if (response.status === 'error') {
        alert(response.message);
        console.log('Data does not exist in the database.');
      }
    } else {
      console.log('Error: ' + updateRequest.status);
    }
  }
};

updateRequest.open('POST', 'updatepass.php', true);
updateRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
var requestData = 'old_password=' + encodeURIComponent(oldpass) +
                  '&new_password=' + encodeURIComponent(newpass) +
                  '&new_confirm_password=' + encodeURIComponent(newpassconfirm) +
                  '&old_pin=' + encodeURIComponent(oldpin) +
                  '&new_pin=' + encodeURIComponent(newpin) +
                  '&new_confirm_pin=' + encodeURIComponent(newpinconfirm) +
                  '&customer_id=' + encodeURIComponent(customerID);
updateRequest.send(requestData);


}

// --------------------------- address ---------------------------------------

function updateAddress() {  
  var addressvalue = document.querySelector('#cus_address').value;
  var postcodevalue = document.querySelector('#cus_postcode').value;

  var updateRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object

  updateRequest.onreadystatechange = function() {
    if (updateRequest.readyState === XMLHttpRequest.DONE) {
      if (updateRequest.status === 200) {
      var response = JSON.parse(updateRequest.responseText); // Parse the JSON response
  
      if (response.status === 'success') {
        alert(response.message);
        console.log('Data exists in the database.');
      } else {
        alert(response.message);
        console.log('Data does not exist in the database.');
      }
    } else {
      console.log('Error: ' + updateRequest.status);
    }
    }
  };
  
  updateRequest.open('POST', 'updateaddress.php', true);
  updateRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var requestData = '&address=' + encodeURIComponent(addressvalue) +
                    '&postcode=' + encodeURIComponent(postcodevalue) +
                    '&customer_id=' + encodeURIComponent(customerID);
  updateRequest.send(requestData);
}

const inputElement = document.getElementById("uploadBtn");
var fileName ='';
  inputElement.addEventListener("change", function() {
  const fileList = inputElement.files;
  fileName = fileList[0].name;
  
  console.log(fileName); // Output the file name to the console
});

function updatesalary()  { 

  
  var salary = document.querySelector('#cus_salary').value;
  

var updateRequest = new XMLHttpRequest(); // Create the XMLHttpRequest object

updateRequest.onreadystatechange = function() {
  if (updateRequest.readyState === XMLHttpRequest.DONE) {
    if (updateRequest.status === 200) {
    var response = JSON.parse(updateRequest.responseText); // Parse the JSON response

    if (response.status === 'success') {
      alert(response.message);
      console.log('Data exists in the database.');
    } else {
      alert(response.message);
      console.log('Data does not exist in the database.');
    }
  } else {
    console.log('Error: ' + updateRequest.status);
  }
  }
};

updateRequest.open('POST', 'updatesalary.php', true);
updateRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
var requestData = '&salary=' + encodeURIComponent(salary) +
                  '&salary_file=' + encodeURIComponent(fileName) +
                  '&customer_id=' + encodeURIComponent(customerID);
updateRequest.send(requestData);


}