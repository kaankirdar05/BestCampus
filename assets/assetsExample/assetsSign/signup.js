document.getElementById('signupForm').addEventListener('submit', function(event) {
    var password = document.getElementById('floatingPassword').value;
    var regex = /(?=.*\d).{8,}/;
    var errorDiv = document.getElementById('passwordError');
    var confirmPassword = document.getElementById('floatingCPassword').value;
    var errorDiv_2 = document.getElementById('cpasswordError');
       
    if (!regex.test(password)) {
        errorDiv.innerHTML = "Password must contain at least one number and be 8 characters long!";
        errorDiv.style.display = 'block';
        event.preventDefault();
    }
    
    else if(password != confirmPassword ) {
        errorDiv_2.innerHTML = "Passwords does not match!";
        errorDiv_2.style.display = 'block';
        errorDiv.style.display = 'none';
        event.preventDefault();
    }   
    
    else {        
        errorDiv_2.style.display = 'none';
        }     
});