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
        label#otp-error {
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
                            <h4>Verify OTP</h4>
                            <?php //echo $phone; ?>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-md-center">
                                <div class="mb-3">
                                    <input type="text" name="otp" class="form-control" id="otp" placeholder="Enter otp">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="/user" class="mr-1">Resend otp</a>
                            <button type="submit" class="btn btn-primary">verify otp</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#errorAlert').hide();
            $('#successAlert').hide();
            
            // setTimeout(function() {
            //     var msg = 'Your OTP is ' + "<?php echo $otp; ?>" + '.';
            //     alert(msg);
            //     $('#otp').val("<?php echo $otp; ?>");
            // }, 2000);
            
            $("#verifyPhone").validate({
                rules: {
                    otp: {
                        required: true,
                        number: true,
                        minlength:4,
                        maxlength:4
                    }
                },

                messages: {
                    phone: {
                        required: "otp is required.",
                        number: "otp only in digit.",
                        minlength: "Enter only 4 digit.",
                        maxlength: "Enter only 4 digit."
                    },
                },

                submitHandler: function(form) {
                    $.ajax({
                        url: "/user/check_otp",
                        type:"POST",
                        data:{
                            otp: $('#otp').val(),
                            phone: "<?php echo $phone; ?>",                            
                        },
                        success:function(response){
                            var res = JSON.parse(response);
                            if(res.status){
                                $('#email').val('')
                                $('#errorAlert').hide();
                                $('#successAlert').fadeIn().text(res.message);
                                hideAlert()
                                window.location.href = '/user/';
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