<?php
include("dbConnect.php");
include("auth.php");
date_default_timezone_set('Asia/Karachi');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Targets</title>
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
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">CUMULATIVE TARGETS</h1>
    </section>


    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <h2 class="text-center t-bold heading"> <?php echo "TARGET ACHIEVED IN THIS MONTH (".strtoupper(date('F-Y')).")"; ?>  </h2>
                    </div>
                    <div class="card-header d-flex justify-content-end">
                    <!-- Criteria Modal -->
                        <button type="button" class="btn-spec primary me-1" title="View Criteria"
                        data-bs-toggle="modal" data-bs-target="#criteriaModal"><i class="fas fa-rocket"></i> Target Criteria</button>   
                        <!-- Only for Admin -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager"): ?>
                        <!-- Date Filter -->
                        <button type="button" class="btn-spec primary me-1" title="Use date range"
                        data-bs-toggle="modal" data-bs-target="#targetDateRangeModal"><i class="far fa-calendar"></i> Date Filter</button>                    
                        <!-- Export button --> 
                        <button type="button" id="exportTargetbtn-spec" title="Export as excel"
                        class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class='table text-center' style='width:100%;' id='targetTable'>
                            <thead>
                                <tr>
                                    <th>SR.NO</th>
                                    <th>EMPLOYEE</th>
                                    <th>TARGET ACHIEVED</th>
                                    <th>REMAINING TARGET</th>
                                    <th>TOTAL TARGET</th>
                                    <th>STATUS</th>
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

    <!-- Date Range Modal -->
    <div class="modal fade" id="targetDateRangeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">SELECT DATA RANGE</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="leadDateRangeForm">                       
                        <!-- From -->
                        <div class="form-group">
                            <label for="from">From</label>
                            <input type="date" id="from" class="form-control mb-1">
                        </div>  
                        <!-- To -->
                        <div class="form-group">
                            <label for="to">To</label>
                            <input type="date" id="to" class="form-control mb-1">
                        </div>                                                                                                                                                                                                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="targetDateRangebtn-spec">Search</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Criteria Modal -->
    <div class="modal fade" id="criteriaModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">TARGETS WITH CRITERIA</h3>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Target Completion -->
                    <div class="target-label">TARGET COMPLETION: ($1000.00)</div> 
                    <div class="target-para mb-3">
                    In order to fulfill your target, it is necessary to achieve a target of $1000.00 in a month.
                    Upon reaching this milestone, you will be entitled to receive a reward as a token of achievement
                    which will increase the chances of your growth and instant promotion.</div>
                    <!-- Safe Zone -->
                    <div class="target-label">SAFE ZONE: (ABOVE $500.00) </div>
                    <div class="target-para mb-3">
                    To maintain a secure position within the performance spectrum,
                    it is recommended to aim for a target above $500.00.
                    Achieving this threshold will safeguard you from falling into 
                    the red zone and receiving any warnings.</div>   
                    <!-- Red Zone -->
                    <div class="target-label">RED ZONE: (BELOW $500.00) </div>
                    <div class="target-para">
                    Failure to meet performance expectations, particularly staying within the red zone beyond
                    the initial 20 days of the month, indicates a significant deficiency.
                    It is imperative to exert additional effort in generating high-quality leads promptly.
                    Otherwise, you may receive warnings from your team lead.
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

    var from = '';
    var to = '';
    $("#targetDateRangebtn-spec").click(function(){
        from = $("#from").val();
        to = $("#to").val();
        if(from==="" || to==="")
        {
            toaster("Please select date range in a proper manner",5);
        }
        else
        {
            $("#targetDateRangeModal").modal("hide");
            $("#targetTable").DataTable().destroy();
            fetchTarget();

            // For Table Heading
            $.ajax({
                type: "POST",
                url: "ajax/headings/target.php",
                data: {from:from, to:to},
                success: function (response) 
                {
                    $(".heading").text(response);
                }
            });
        }
    });

    // Export As Excel
    $("#exportTargetbtn-spec").click(function(){
        window.location.href=`Export/target.php?from=${from}&to=${to}`;
    });    

    // Fetch Target
    function fetchTarget()
    {
        $("#targetTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
            "type": "POST",
            "url": "ajax/fetch/target.php",
            "data": {from:from, to:to} 
            },
        "columns": [
            { data: 'sr_no'},
            { data: 'employee_name'},
            { data: 'target_achieved'},
            { data: 'remaining_target'},
            { data: 'total_target'},
            { data: 'status'},
        ],
        "pageLength": 10,
        "searching": true,
        });
    }

    // Load Target Table On Page Initialization
    $(document).ready(function () {
        fetchTarget();
    });
</script>
</body>
</html>