<?php
include("dbConnect.php");
include("auth.php");
include("admin_auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <!-- Bootstrap CSS v5.2.1 & Other CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">     <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">     <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">     <link rel="manifest" href="/site.webmanifest">     <meta name="msapplication-TileColor" content="#da532c">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- GIF Loader -->
    <div id="loader-container">
        <img src="images/loader1.gif" alt="Loading...">
    </div> 

    <!-- Header -->
    <section class="header-section">
        <?php include("navbar.php"); ?>           
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">SOCIAL HANDLES</h1>
    </section>

    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header d-flex justify-content-end">
                    <button type="button" class="btn-spec primary" 
                title="Add new account" data-bs-toggle="modal" data-bs-target="#addAccountModal"><i class="fas fa-plus"></i> Add New</button>
            
                    </div>
                    <div class="card-body">
                        <div id="accountsTable"> <!-- Table will be rendered here --> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copied to clipboard popup -->
    <div class="copied-popup">Copied to clipboard!</div>

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">CREATE NEW ACCOUNT</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addAccountForm">
                        <!-- Account Name -->
                        <div class="form-group">
                            <label for="accountName">Account Name (Required)</label>
                            <input type="text" id="accountName" class="form-control mb-1" placeholder="Enter Account Name">
                        </div>                       
                        <!-- Gmail -->
                        <div class="form-group">
                            <label for="gmail">Gmail (Required)</label>
                            <input type="text" id="gmail" class="form-control mb-1" placeholder="Enter Gmail">
                            <input type="text" id="gmailPassword" class="form-control mb-1" placeholder="Enter Gmail Password">
                        </div>
                        <!-- Twitter -->
                        <div class="form-group">
                            <label for="twitter">Twitter (Optional)</label>
                            <input type="text" id="twitter" class="form-control mb-1" placeholder="Enter Twitter Username">
                            <input type="text" id="twitterPassword" class="form-control mb-1" placeholder="Enter Twitter Password">
                        </div>      
                        <!-- Instagram -->
                        <div class="form-group">
                            <label for="instagram">Instagram (Optional)</label>
                            <input type="text" id="instagram" class="form-control mb-1" placeholder="Enter Instagram Username">
                            <input type="text" id="instagramPassword" class="form-control mb-1" placeholder="Enter Instagram Password">
                        </div>   
                        <!-- Discord -->
                        <div class="form-group">
                            <label for="discord">Discord (Optional)</label>
                            <input type="text" id="discord" class="form-control mb-1" placeholder="Enter Email Or Cell No">
                            <input type="text" id="discordPassword" class="form-control mb-1" placeholder="Enter Discord Password">
                        </div>   
                        <!-- Twitch -->
                        <div class="form-group">
                            <label for="twitch">Twitch (Optional)</label>
                            <input type="text" id="twitch" class="form-control mb-1" placeholder="Enter Twitch Username">
                            <input type="text" id="twitchPassword" class="form-control mb-1" placeholder="Enter Twitch Password">
                        </div>                                                                                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="addAccountbtn-spec">Save</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">EDIT ACCOUNT</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editAccountForm">
                        <!-- Account Name -->
                        <div class="form-group">
                            <label for="edit_accountName">Account Name (Required)</label>
                            <input type="text" id="edit_accountName" class="form-control mb-1" placeholder="Enter Account Name">
                        </div>                       
                        <!-- Gmail -->
                        <div class="form-group">
                            <label for="edit_gmail">Gmail (Required)</label>
                            <input type="text" id="edit_gmail" class="form-control mb-1" placeholder="Enter Gmail">
                            <input type="text" id="edit_gmailPassword" class="form-control mb-1" placeholder="Enter Gmail Password">
                        </div>
                        <!-- Twitter -->
                        <div class="form-group">
                            <label for="edit_twitter">Twitter (Optional)</label>
                            <input type="text" id="edit_twitter" class="form-control mb-1" placeholder="Enter Twitter Username">
                            <input type="text" id="edit_twitterPassword" class="form-control mb-1" placeholder="Enter Twitter Password">
                        </div>      
                        <!-- Instagram -->
                        <div class="form-group">
                            <label for="edit_instagram">Instagram (Optional)</label>
                            <input type="text" id="edit_instagram" class="form-control mb-1" placeholder="Enter Instagram Username">
                            <input type="text" id="edit_instagramPassword" class="form-control mb-1" placeholder="Enter Instagram Password">
                        </div>   
                        <!-- Discord -->
                        <div class="form-group">
                            <label for="edit_discord">Discord (Optional)</label>
                            <input type="text" id="edit_discord" class="form-control mb-1" placeholder="Enter Email Or Cell No">
                            <input type="text" id="edit_discordPassword" class="form-control mb-1" placeholder="Enter Discord Password">
                        </div>   
                        <!-- Twitch -->
                        <div class="form-group">
                            <label for="edit_twitch">Twitch (Optional)</label>
                            <input type="text" id="edit_twitch" class="form-control mb-1" placeholder="Enter Twitch Username">
                            <input type="text" id="edit_twitchPassword" class="form-control mb-1" placeholder="Enter Twitch Password">
                        </div>                                                                                        
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- Hidden Account ID -->
                    <input type="hidden" id="hidden_account_id">
                    <button type="button" class="btn-spec primary" id="updateAccountbtn-spec">Update</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
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
</body>
</html>