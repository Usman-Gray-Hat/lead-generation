<?php
include("dbConnect.php");
include("auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security & Privacy</title>
    <!-- Bootstrap CSS v5.2.1 & Other CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-background">

    <!-- GIF Loader --> 
    <div id="loader-container">
        <img src="images/loader1.gif" alt="Loading...">
    </div>

    <!-- Header -->
    <section class="header-section">  
        <?php include("navbar.php"); ?>    
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">CHANGE PASSWORD & STAY SECURE</h1>
    </section>

    <!-- Change Password Form -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card shadow changePassword-card">
                    <div class="card-body">
                    <div class="text-center">
                        <img src="images/logo.png" height="120">
                        </div>
                        <!-- Change Password Form -->
                        <form method="POST" id="changePasswordForm">
                            <!-- Old Password -->
                            <div class="form-group mb-2">
                                <label for="current_password" class="text-white">Current Password (Required)</label>
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" id="current_password" placeholder="Enter Your Current Password" class="form-control">
                                    <span class="input-group-text"> <i class="fa-solid fa-eye-slash" id="eyeIcon1"></i> </span> 
                                </div>
                            </div>
                            <!-- New Password -->
                            <div class="form-group mb-2">
                                <label for="new_password" class="text-white">New Password (Required)</label>
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" id="new_password" placeholder="Enter Your New Password" class="form-control">
                                    <span class="input-group-text"> <i class="fa-solid fa-eye-slash" id="eyeIcon2"></i> </span> 
                                </div>
                            </div>
                            <!-- Confirm Password -->
                            <div class="form-group mb-2">
                                <label for="confirm_password" class="text-white">Confirm Password (Required)</label>
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" id="confirm_password" placeholder="Re-Enter Your Password" class="form-control">
                                    <span class="input-group-text"> <i class="fa-solid fa-eye-slash" id="eyeIcon3"></i> </span> 
                                </div>
                            </div>                            
                            <div class="form-group">
                                <button type="button" id="updatePasswordbtn-spec" class="btn-spec primary w-100"><i class="fa fa-key"></i> Update </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JQuery & Other CDNs -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> 
    <script src="dist/sweetalert2.all.min.js"></script>
    <script src="script.js"></script> 
    <script>

// Change Password
$("#updatePasswordbtn-spec").click(function(){
    let current_password = $("#current_password").val();
    let new_password = $("#new_password").val();
    let confirm_password = $("#confirm_password").val();
    if(current_password==="")
    {
        toaster("Please enter your current password",5);
    }
    else
    {
        if(new_password==="")
        {
            toaster("Please enter your new password",5);
        }
        else
        {
            if(confirm_password==="")
            {
                toaster("Please Re-enter your password",5);
            }
            else
            {
                if(new_password.length<7)
                {
                    toaster("Too short! Your new password must be at least 7 characters long",5);
                }
                else
                {
                    if(new_password!==confirm_password)
                    {
                        toaster("The new password & confirm password are not identical",5)
                    }
                    else
                    {
                        $.ajax({
                            type: "POST",
                            url: "ajax/fetch/privacy.php",
                            data: {current_password:current_password, new_password:new_password},
                            success: function (response) 
                            {
                                if(response==="Current password is incorrect")
                                {
                                    toaster(response,5);
                                }
                                else if(response==="Your password has been updated")
                                {
                                    $("#changePasswordForm input").val("");
                                    swal.fire({
                                        title: "PASSWORD UPDATED",
                                        text: "Your password has been changed",
                                        icon: 'success',
                                        confirmButtonColor: 'blue',
                                        showCloseButton: true,
                                        allowOutsideClick: false,                                    
                                        timer:2000,
                                    }).then(function(){
                                        window.location.href="home.php";
                                    });
                                }
                                else if(response==="Your password has not been updated")
                                {
                                    toaster(response,5);
                                }
                                else
                                {
                                    toaster("An unknown error has occured",5);
                                }
                            }
                        });
                    }
                }
            }
        }
    }
});

</script> 
</body>
</html>