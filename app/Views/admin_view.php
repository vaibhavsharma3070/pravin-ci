<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <title>add user</title>
    <style>
        label.error {
            color: red;
        }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-6">
                <form id="addUser" method="post">
                    <div class="card">
                        <div class="alert alert-danger" id="errorAlert"></div>
                        <div class="alert alert-success" id="successAlert"></div>
                        <div class="card-header">
                            <h5 class="float-left">Add user</h5>
                            <a href="/user/logout" class="float-right">logout</a>                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                                </div>
                                <div class="col-12 mb-3">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">                                    
                                </div>
                                <div class="col-12 mb-3">
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter phone">                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Add</button>
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
            
            $("#addUser").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength:10,
                        maxlength:10
                    }
                },

                messages: {
                    name: {
                        required: "Name is required."
                    },
                    email: {
                        required: "Email is required.",
                        email: "Email is invalid."
                    },
                    phone: {
                        required: "Phone number is required.",
                        number: "Phone number only in digit.",
                        minlength: "Enter only 10 digit.",
                        maxlength: "Enter only 10 digit."
                    },
                },

                submitHandler: function(form) {
                    $.ajax({
                        url: "/user/add_user",
                        type:"POST",
                        data:{
                            name: $('#name').val(),
                            email: $('#email').val(),
                            phone: $('#phone').val()
                        },
                        success:function(response){
                            var res = JSON.parse(response);
                            if(res.status){
                                $('#email').val('')
                                $('#errorAlert').hide();
                                $('#successAlert').fadeIn().text(res.message);
                                hideAlert()
                                $('#email').val('')
                                $('#name').val('')
                                $('#phone').val('')
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