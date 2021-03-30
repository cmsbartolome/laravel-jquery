var myPass = document.getElementById("password");
var passConf = document.getElementById("password-confirm");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
var special = document.getElementById("special"); // When the user clicks on the password field, show the message box

myPass.onfocus = function () {
    document.getElementById("message").style.display = "block";
}; // When the user clicks outside of the password field, hide the message box


myPass.onblur = function () {
    document.getElementById("message").style.display = "none";
}; // When the user starts to type something inside the password field


myPass.onkeyup = function () {
    // Validate lowercase letters
    var lowerCaseLetters = /[a-z]/g;

    if (myPass.value.match(lowerCaseLetters)) {
        myPass.style.borderColor = '#ced4da';
        letter.classList.remove("invalid");
        letter.classList.add("valid");
    } else {
        myPass.style.borderColor = 'red';
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    } // Validate capital letters


    var upperCaseLetters = /[A-Z]/g;

    if (myPass.value.match(upperCaseLetters)) {
        myPass.style.borderColor = '#ced4da';
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    } else {
        myPass.style.borderColor = 'red';
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    } // Validate numbers


    var numbers = /[0-9]/g;

    if (myPass.value.match(numbers)) {
        myPass.style.borderColor = '#ced4da';
        number.classList.remove("invalid");
        number.classList.add("valid");
    } else {
        myPass.style.borderColor = 'red';
        number.classList.remove("valid");
        number.classList.add("invalid");
    } // Validate length


    if (myPass.value.length >= 8) {
        myPass.style.borderColor = '#ced4da';
        length.classList.remove("invalid");
        length.classList.add("valid");
    } else {
        myPass.style.borderColor = 'red';
        length.classList.remove("valid");
        length.classList.add("invalid");
    } // Validate special characters


    var specialchar = /^(.*\W){1}.*$/g;

    if (myPass.value.match(specialchar)) {
        myPass.style.borderColor = '#ced4da';
        special.classList.remove("invalid");
        special.classList.add("valid");
    } else {
        special.classList.remove("valid");
        special.classList.add("invalid");
    }

    if (myPass.value != passConf.value) {
        document.getElementById("msg").style.display = "block";
        passConf.style.borderColor = 'red';
    } else {
        passConf.style.borderColor = '#ced4da';
        document.getElementById("msg").style.display = "none";

    }
};

passConf.onkeyup = function () {
    if (passConf.value != myPass.value) {
        document.getElementById("msg").style.display = "block";
        passConf.style.borderColor = 'red';
    } else {
        passConf.style.borderColor = '#ced4da';
        document.getElementById("msg").style.display = "none";
    }

    if (passConf.value == '') {
        document.getElementById("msg").style.display = "block";
        passConf.style.borderColor = 'red';
    }
};

