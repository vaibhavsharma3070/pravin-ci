<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <title>Phone</title>
    <style>
        label#phone-error {
            color: red;
        }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-6">
                <form id="verifyPhone" method="post">
                    <div class="card">
                        <div class="alert alert-danger" id="errorAlert"></div>
                        <div class="alert alert-success" id="successAlert"></div>
                        <div class="card-header">
                            <h4>Login via OTP</h4>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-md-center">
                                <div class="mb-3">
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">send otp</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <!-- //===================== Firebase ====================== -->
    <!-- <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyAlwHV3n9lUVGRkenZ1XHPPz4ZKYfPecLY",
            authDomain: "ci4-firebase-2e60a.firebaseapp.com",
            projectId: "ci4-firebase-2e60a",
            storageBucket: "ci4-firebase-2e60a.appspot.com",
            messagingSenderId: "779596661525",
            appId: "1:779596661525:web:5a935a586177cb68695f1c",
            measurementId: "G-XVFG819TS1"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script> -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#errorAlert').hide();
            $('#successAlert').hide();
            $("#verifyPhone").validate({
                rules: {
                    phone: {
                        required: true,
                        number: true,
                        minlength:10,
                        maxlength:10
                    }
                },

                messages: {
                    phone: {
                        required: "Phone number is required.",
                        number: "Phone number only in digit.",
                        minlength: "Enter only 10 digit.",
                        maxlength: "Enter only 10 digit."
                    },
                },

                submitHandler: function(form) {
                    $.ajax({
                        url: "/user/verify_phone",
                        type:"POST",
                        data:{
                            phone: $('#phone').val()
                        },
                        success:function(response){
                            var res = JSON.parse(response);
                            if(res.status){
                                $('#phone').val('')
                                $('#errorAlert').hide();
                                $('#successAlert').fadeIn().text(res.message);
                                hideAlert()
                                window.location.href = '/user/verify_otp';
                            }else{
                                $('#successAlert').hide();
                                $('#errorAlert').fadeIn().text(res.message);
                                hideAlert()
                            }
                        },
                        error: function(err) {
                            $('#successAlert').hide();
                            $('#errorAlert').fadeIn().text(err);
                            hideAlert()
                            console.log(err);return;
                        }
                    });
                }
            });

            function hideAlert() {
                setTimeout(
                    function() {
                        $('#errorAlert').fadeOut();
                        $('#successAlert').fadeOut();
                    }, 
                5000);
            }
        
            
        
        
        });
    </script>
  </body>
</html>