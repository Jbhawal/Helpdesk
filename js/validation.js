function validatePasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;
    var errorMessage = document.getElementById("passwordMatchError");

    if (password === "" || confirmPassword === "") {
        errorMessage.textContent = ""; 
        return true;
    }

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords do not match!";
        return false;
    } 
    else{
        errorMessage.textContent = ""; 
        return true;
    }
}

function validateEmail() {
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    // Basic email regex: something@something.something
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        emailError.textContent = ""; 
        return false; 
    }

    if (!emailPattern.test(email)) {
        emailError.textContent = "Invalid email format.";
        return false;
    } else {
        emailError.textContent = "";
        return true;
    }
}

const isEmailValid = validateEmail();
    if (!isEmailValid) {
        isValid = false;
        if (isValid) { 
            document.getElementById('email').focus();
        }
    }


function validatePhoneNumber() {
    const phone = document.getElementById('phn').value;
    const phoneError = document.getElementById('phoneError');
    const phonePattern = /^\d{10}$/; // Regular expression for exactly 10 digits

    if (phone === "") {
        phoneError.textContent = ""; 
        return false; 
    }

    if (!phonePattern.test(phone)) {
        phoneError.textContent = "Must be 10 digits.";
        return false;
    } else {
        phoneError.textContent = "";
        return true;
    }
}

// Validate Phone Number
    const isPhoneValid = validatePhoneNumber();
    if (!isPhoneValid) {
        isValid = false;
        
        if (document.getElementById('phone').value === "") {
            document.getElementById('phoneError').textContent = "Phone number is required.";
        }
        if (isValid) { // Only focus if no prior invalid fields
            document.getElementById('phone').focus();
        }
    }
