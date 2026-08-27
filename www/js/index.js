// document.addEventListener('deviceready', onDeviceReady, false);
onDeviceReady();

function onDeviceReady() {
    document.getElementById('btnScan').addEventListener('click', AjouterCarteCamera);
}

function AjouterCarteCamera() {
    cordova.plugins.barcodeScanner.scan(
        function(result) {
            document.getElementById('code').innerText = result.text;
        },
        function(error) {
            alert("Scanning failed: " + error);
        }, {
            preferFrontCamera: false, // iOS and Android
            showFlipCameraButton: false, // iOS and Android
            showTorchButton: true, // iOS and Android
            torchOn: false, // Android, launch with the torch switched on (if available)
            saveHistory: true, // Android, save scan history (default false)
            prompt: "Place a barcode inside the scan area", // Android
            resultDisplayDuration: 500, // Android, display scanned text for X ms. 0 suppresses it entirely, default 1500
            formats: "QR_CODE,PDF_417,EAN_13,EAN_8", // default: all but PDF_417 and RSS_EXPANDED
            // orientation: "landscape", // Android only (portrait|landscape), default unset so it rotates with the device
            disableAnimations: true, // iOS
            disableSuccessBeep: false // iOS and Android
        }
    );
}


function startLoginregister() {
    document.getElementById('loginRegisterForm').style.display = 'block';
}


function toggleLoginForm() {
    if (document.getElementById('confirmPassword').style.display === 'none') {
        // change to register
        document.getElementById('confirmPassword').style.display = 'block';
        document.getElementById('loginRegisterButton').innerText = 'Register';
    } else {
        // change to login
        document.getElementById('confirmPassword').style.display = 'none';
        document.getElementById('loginRegisterButton').innerText = 'Login';
        document.getElementById('changeType').innerText = 'Create an account';
    }
}