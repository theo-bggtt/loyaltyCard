function goBack() {
    window.location.href = 'index.html';
}

function toggleLoginForm() {
    if (document.getElementById('confirmPassword').style.display === 'none') {
        // change to register
        document.getElementById('confirmPassword').style.display = 'block';
        document.getElementById('confirmPassword').required = true;
        document.getElementById('formInputType').value = 'Register';
        document.getElementById('loginRegisterButton').innerText = 'Register';
        document.getElementById('changeType').innerText = 'I already have an account';
    } else {
        // change to login
        document.getElementById('confirmPassword').style.display = 'none';
        document.getElementById('confirmPassword').required = false;
        document.getElementById('formInputType').value = 'Login';
        document.getElementById('loginRegisterButton').innerText = 'Login';
        document.getElementById('changeType').innerText = 'Create an account';
    }
}