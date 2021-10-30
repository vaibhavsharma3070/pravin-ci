<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" crossorigin="anonymous">
    <title>add user</title>
    <style>
        label.error {
            color: red;
        }
        div#example_length, #example_filter {
            margin-bottom: 10px;
        }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-md-center mt-5">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="float-left">Hello <?php echo $data['name']; ?></h5>
                        <a href="/user/logout" class="float-right">logout</a>                            
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <?php if(!is_null($error_msg)){ ?>
                                    <div class="alert alert-danger" id="errorAlert">
                                        <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>
                                <?php if(!is_null($success_msg)){ ?>
                                    <div class="alert alert-success" id="successAlert">
                                        <?php echo $success_msg; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-12 mb-2 text-right">
                                <button type="button" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                    Attach File
                                </button>
                            </div>
                            <hr/>
                            <div class="col-12">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>File</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $i=0;
                                            if(count($files) > 0){
                                                foreach ($files as $res) {
                                                    $i = $i+1;
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php 
                                                        if ((strpos($res['type'], 'image') !== false) || (strpos($res['type'], 'img') !== false)) {
                                                    ?>
                                                        <img height="50px" src="<?php echo base_url('/uploads/user_files/'.$res['name']);?>">

                                                    <?php 
                                                        } else {
                                                    ?> 
                                                        <a href="<?php echo base_url('/uploads/user_files/'.$res['name']);?>" target="_blank">view</a>
                                                    <?php 
                                                        }
                                                    ?> 

                                                </td>
                                                
                                                <td>
                                                    <a href="<?php echo base_url('/uploads/user_files/'.$res['name']);?>" target="_blank">file</a>
                                                </td>
                                            </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attach file</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="<?php echo base_url('user/file_upload');?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="form-group mt-1">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // $('#errorAlert').hide();
            // $('#successAlert').hide();
            
            $('#example').DataTable();
            setTimeout(
                function() {
                    $('#errorAlert').fadeOut();
                    $('#successAlert').fadeOut();
                }, 
            5000);
            
        });
    </script>
  </body>
</html>