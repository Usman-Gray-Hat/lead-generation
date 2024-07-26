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
    <title>Scoreboard</title>
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
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2"> MONTHLY SCORE AND RANKING</h1>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header">
                    <h2 class="text-center t-bold heading"> <?php echo "RANKING OF MONTH - (".strtoupper(date('F-Y')).")"; ?> </h2>

                    </div>
                    <div class="card-header d-flex justify-content-end">
                    <!-- Criteria Modal -->
                        <button type="button" class="btn-spec primary me-1" title="View Criteria"
                        data-bs-toggle="modal" data-bs-target="#criteriaModal"><i class="fas fa-ribbon"></i> Ranking Criteria</button>                
                        <!-- Only for Admin -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager"): ?>
                        <!-- Date Filter -->
                        <button type="button" class="btn-spec primary me-1" title="Use date range"
                        data-bs-toggle="modal" data-bs-target="#totalLeadsDateRangeModal"><i class="far fa-calendar"></i> Date Filter</button>                    
                        <!-- Export button -->
                        <button type="button" id="exportTotalLeads" title="Export as excel"
                        class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class='table text-center' style='width:100%;' id='totalLeadsTable'>
                            <thead>
                                <tr>
                                    <th>HIDDEN ADMIN ID FK</th>
                                    <th>SR.NO</th>
                                    <th>EMPLOYEE</th>
                                    <th>TOTAL LEADS</th>
                                    <th>AVERAGE</th>
                                    <th>POSITIONS</th>
                                    <th>RANKS</th>
                                    <th>TRACK RECORD</th>
                                </tr> 
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Criteria Modal -->
    <div class="modal fade" id="criteriaModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">RANKS WITH CRITERIA</h3>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <!-- Platinum -->
                    <div class="rank-label" style="font-weight: bolder;">Platinum: </div>
                    <div class="rank-para mb-3"> <i class="fas fa-crown rank-icon platinum-icon"></i> - Minimum 170 leads</div>

                    <!-- Diamond -->
                    <div class="rank-label" style="font-weight: bolder;">Diamond:</div>
                    <div class="rank-para mb-3"> <i class="fas fa-gem rank-icon diamond-icon"></i>   - Minimum 120 leads</div> 

                    <!-- Gold -->
                    <div class="rank-label" style="font-weight: bolder;">Gold:</div>
                    <div class="rank-para mb-3"> <i class="fas fa-trophy rank-icon gold-icon"></i> - Minimum 80 leads</div>

                    <!-- Silver -->
                    <div class="rank-label" style="font-weight: bolder;">Silver:</div>
                    <div class="rank-para mb-3"> <i class="fas fa-trophy rank-icon silver-icon"></i> - Minimum 40 leads</div>

                    <!-- Bronze -->
                    <div class="rank-label" style="font-weight: bolder;">Bronze:</div>
                    <div class="rank-para"> <i class="fas fa-medal rank-icon bronze-icon"></i> - Minimum 20 leads</div>                                                      
                </div>
                <div class="modal-footer">
                    <p class="text-white ms-2" style="font-size: 17px;">Note: The rankings are determined by the total number of 
                        leads generated by each member within a current month.
                        Ranks will be reset on the 1st of every month.
                    </p>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Modal -->
    <div class="modal fade" id="totalLeadsDateRangeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">SELECT DATA RANGE</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="totalLeadsDataRangeForm">                       
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
                    <button type="button" class="btn-spec primary" id="totalLeadsDateRangebtn-spec">Search</button>
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
    <script>

        var from = '';
        var to = '';

        // Fetch Score Table
        function fetchScore()
        {
            $("#totalLeadsTable").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                "type": "POST",
                "url": "ajax/fetch/score.php",
                "data": {from:from, to:to} 
                },
                "columns": [
                { data: 'admin_id_FK', className:'d-none'}, // Hidden Admin ID FK
                { data: 'sr_no'},
                { data: 'name'},
                { data: 'total_leads'},
                { data: 'average_leads'},
                { data: 'position'},
                { data: 'rank'},
                {
                    "render":function(data,type,full,meta) 
                    {
                        var button = `<a  title='View Track Record'
                        href='lead_count.php?id=${full.admin_id_FK}&employee_name=${full.name}'><button class='btn-spec primary'><i style="font-size:17px;" class="fa fa-eye" aria-hidden="true"> </i> View </button></a>`;
                        return button;
                    }, className:'operations-column'
                }
                ],pageLength: 100
            });
        }

        // Load Score Table On Page Initialization
        $(document).ready(function () {
            fetchScore();
        });

        // Date Filter
        $("#totalLeadsDateRangebtn-spec").click(function(){
            from = $("#from").val();
            to = $("#to").val();
            if(from==="" || to==="")
            {
                toaster("Please select date range in a proper manner",5);
            }
            else
            {
                $("#totalLeadsDateRangeModal").modal("hide");
                $("#totalLeadsTable").DataTable().destroy();
                fetchScore();

                // For Table Heading
                $.ajax({
                    type: "POST",
                    url: "ajax/headings/score.php",
                    data: {from:from, to:to},
                    success: function (response) 
                    {
                        $(".heading").text(response);
                    }
                });
            }
        });

        // Export As Excel
        $("#exportTotalLeads").click(function(){
            window.location.href=`Export/score.php?from=${from}&to=${to}`;
        });
    </script>
</body>
</html>