<?php
include("dbConnect.php");
include("auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-ups</title>
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
<body>

    <!-- GIF Loader -->
    <div id="loader-container">
        <img src="images/loader1.gif" alt="Loading...">
    </div>

    <!-- Header -->
    <section class="header-section">  
        <?php include("navbar.php"); ?>    
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">ALL FOLLOW-UPS</h1>
    </section>


    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header d-flex justify-content-end col-md-12 d-flex justify-content-end">
                                <!-- Only For Admin, Manager And Closer -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer"): ?>
                        <!-- Export As Excel -->
                        <a href="Export/follow-up.php" title="Export as excel"
                        ><button class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button></a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class='table text-center'style='width:100%;' id='followupTable'>
                            <thead>
                                <tr>
                                    <th>SR.NO</th>
                                    <th>EMPLOYEE</th>
                                    <th>ACCOUNT</th>
                                    <th>PLATFORM</th>
                                    <th>CLIENT</th>
                                    <th>CHANNEL LINK</th>
                                    <th>COMMENTS</th>
                                    <th>FOLLOW UP DATE WITH REMARKS</th>
                                </tr> 
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copied to clipboard popup -->
    <div class="copied-popup">Copied to clipboard!</div>

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

    // Fetch Follow-ups
    function fetchFollowup()
    {
        $("#followupTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
            "type": "POST",
            "url": "ajax/fetch/follow-up.php",
            },
        "columns": [
            { data: 'sr_no'},
            { data: 'employee_name'},
            { data: 'account_name'},
            { data: 'platform_name'},
            { data: 'client_username', className:'copied'},
            { data: 'channel_link', className:'copied'},
            { data: 'comments'},
            { data: 'follow_up_date_with_remarks'},
        ],
        "pageLength": 10,
        "searching": true,
        });
    }

    // Load Follow-up Table On Page Initialization
    $(document).ready(function () {
        fetchFollowup();
    });
  
    </script>
</body>
</html>