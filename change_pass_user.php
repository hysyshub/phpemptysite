<?php 
session_start();
if($_SESSION['user']=='')
{
    header('Location: login.php');
}
else
{
    error_reporting(0);
    date_default_timezone_set('Asia/Calcutta');

?>
<html>
<head>
<title>Change App User Password</title>

</head>
<body>
<?php

include 'header.php';
include 'php/config.php';

$conn = pg_connect($conn_string);

if(!$conn)
{
    echo "ERROR : Unable to open database";
    exit;
}
?>
<!-- Page Content  start-->
        <div id="content" style="overflow: auto;">

            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width:100%">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info" style='background:#030dcf;'>
                        <i class="fas fa-align-left"></i>
                        
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse pull-right" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a href="userinfo.php" style="color:blue;text-align:right;" class="nav-link">App Users</a>
                            </li>
                            <li class="nav-item">
                                <a href="admininfo.php" style="color:blue;text-align:right;" class="nav-link">Admins</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>  
        <div  class="col-md-10">
            <div  class="col-md-3">
            </div>
            <div  class="col-md-6">
        <h3>Change App User Password</h3>
            <?php
                $userid = $_GET['userid'];
            ?>
            <form>
                 <input type='hidden' class='userid' value="<?php echo $userid; ?>">
                  <div class="form-group">
                      <input class="form-control form-control-sm new_password" id="new_password" type="password" placeholder="New Password" name="new_password" >
                  </div>
                  <div class="form-group">
                      <input class="form-control form-control-sm confirm_password" id="confirm_password" type="password" placeholder="Confirm New Password" name="confirm_password" >
                    </div>
                  </div>
                    <div class="form-group">
                        <center><img src="images/loading.gif" class='img-responsive loading_img' id='loading_img' style='widht:100px;height:100px;display:none;'/></center>
                    </div>
                
                <div class="col-md-8">
                    <div class="col-xs-8 col-sm-8 col-md-8 status">
                        
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <button type="button" class="btn btn-sm btn-info btn_update">Update</button>
                    </div>
                </div>
                  
            </div>
        </div>
                

            </form>
            </div>
            <div  class="col-md-3">
            </div>
        </div>
    </div>
        

<?php include 'footer.php'; }?>

<script>
$(document).ready(function(){
        
    $('.btn_update').click(function(){               // btn_update click
        
        var userid = $('.userid').val();
        var new_password = $('.new_password').val();
        var confirm_password = $('.confirm_password').val();
        var task = "change_user_password";
        if(new_password == '' || new_password == null)
        {
            $('.status').html("<div class='alert alert-danger'><strong>Empty field!</strong> Please enter new password.</div>");
            return false;
        }
        if (new_password.length < 6)
        {
            $('.status').html("<div class='alert alert-danger'><strong>Password Error! </strong>Password should be of minimum 6 characters</div>");
            return false;
        }
        if (new_password.length > 15)
        {
            $('.status').html("<div class='alert alert-danger'><strong>Password Error! </strong>Password should be of maximum 15 characters</div>");
            return false;
        }
        if(confirm_password == '' || confirm_password == null)
        {
            $('.status').html("<div class='alert alert-danger'><strong>Empty field!</strong> Please Repeat your New Password!</div>");
            return false;
        }
        if(new_password != confirm_password)
        {
            $('.status').html("<div class='alert alert-danger'><strong>Password Match Error! </strong>New password & confirm password not match</div>");
            return false;
        }
        $('.loading_img').show();
        $.ajax({
            type : 'post',
            url : 'updation_helper.php',
            data : 'userid='+userid+'&new_password='+new_password+'&confirm_password='+confirm_password+'&task='+task,
            success : function(res)
            {
                $('.loading_img').hide();
                if(res == 'success')
                {
                    $('.status').html("<div class='alert alert-success'><strong>Success! </strong>Password changed successfully</div>");
                    return false;
                }
                else
                {
                    $('.status').html("<div class='alert alert-danger'><strong>Fail! </strong>"+res+"</div>");
                    return false;
                }
            }
        });
    });
});
</script>
</body>
</html>
