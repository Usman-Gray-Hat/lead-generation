<?php
include("dbConnect.php");
if(!isset($_SESSION['token']))
{
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS v5.2.1 & Other CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-background">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card shadow login-card">
                    <div class="card-body">
                        <h2 class="text-center t-bold" style="letter-spacing: 2px;">EMERALD<img class="pb-2" src="images/icon.png" height="55" width="50" alt=""></h2>
                        <!-- Animation Flare -->
                        <!-- Reset Password Form -->
                        <form method="POST" id="resetPasswordForm">
                            <!-- New Password -->
                            <div class="form-group mb-2">
                                <label for="password" class="text-white">Create New Password (Required)</label>
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" id="password" placeholder="Enter New Password" class="form-control">
                                    <span class="input-group-text"> <i class="fa-solid fa-eye-slash" id="eyeIcon"></i> </span> 
                                </div>
                            </div>
                            <!-- New Password -->
                            <div class="form-group mb-2">
                                <label for="confirm_password" class="text-white">Confirm Password (Required)</label>
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" id="confirm_password" placeholder="Re-Enter Password" class="form-control">
                                    <span class="input-group-text"> <i class="fa-solid fa-eye-slash" id="eyeIcon3"></i> </span> 
                                </div>
                            </div>                            
                            <!-- Forget Password -->
                            <div class="form-group mb-2">
                                <a href="index.php" class="text-secondary">Back to login</a>
                            </div>
                            <!-- Login Button -->
                            <div class="form-group">
                                <button type="button" id="resetPasswordbtn-spec" class="btn-spec">
                                <i class="fas fa-sync-alt"></i> Reset </button>
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
    <script src="script.js"></script>  
    <script>
        $("#resetPasswordbtn-spec").click(function(){
            let password = $("#password").val();
            let confirm_password = $("#confirm_password").val();
            if(password==="")
            {
                toaster("Please enter your new password",5);
            }
            else
            {
                if(password.length<7)
                {
                    toaster("Too short! The password must be at least 7 characters long",5);
                }
                else
                {
                    if(confirm_password==="")
                    {
                        toaster("Please re-enter your password",5);
                    }
                    else
                    {
                        if(password!==confirm_password)
                        {
                            toaster("Password & confirm password are not identical",5);
                        }
                        else
                        {
                            $.ajax({
                                type: "POST",
                                url: "ajax/update/reset_password.php",
                                data: {password:password, confirm_password:confirm_password},
                                success: function (response) 
                                {
                                    if(response==="Your password has been reset")
                                    {
                                        $("#resetPasswordForm input").val("");
                                        toaster(response,3);
                                        setTimeout(function(){
                                            window.location.href="index.php";
                                        },3000);
                                    }
                                    else if(response==="Your password has not been reset")
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
        });
    </script>
</body>
</html>