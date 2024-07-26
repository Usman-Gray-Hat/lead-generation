<?php
include("dbConnect.php");
include("auth.php");
$id = $_GET['id']??"";
$name = $_GET['employee_name']??"";
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Record</title>
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
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">LEADS COUNT TRACK RECORD</h1>
    </section>


    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                <div class="card-header">    
                <h2 class="text-center t-bold heading text-uppercase"> LEADS COUNT TRACK RECORD OF <?php echo $name; ?> </h2>
                </div>
                    <div class="card-header d-flex justify-content-end">
                        <!-- Only For Admin And Manager -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager"): ?>
                        <!-- Date Filter -->
                        <button type="button" class="btn-spec primary me-1" title="Use date range"
                        data-bs-toggle="modal" data-bs-target="#leadCountDateRangeModal"><i class="far fa-calendar"></i> Date Filter</button>                    
                        <!-- Export As Excel -->
                        <button type="button" id="exportLeadCountbtn-spec" title="Export as excel"
                        class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class='table' style='width:100%;' id='leadCountTable'>
                            <thead>
                                <tr>
                                    <th>SR.NO</th>
                                    <th>DATE</th>
                                    <th>TOTAL LEADS</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td class="text-white text-end" colspan='2'>SUM </td>
                                    <td class="text-white" colspan='1' id="leadSum"> </td>
                                </tr>
                                <tr>
                                    <td class="text-white text-end" colspan='2'>AVERAGE </td>
                                    <td class="text-white" colspan='1' id="leadAverage"> </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Modal -->
    <div class="modal fade" id="leadCountDateRangeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">SELECT DATA RANGE</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="leadCountDateRangeForm">                       
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
                    <button type="button" class="btn-spec primary" id="leadCountDateRangebtn-spec">Search</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Hidden ID -->
    <input type="hidden" id="admin_id_FK" value="<?php echo $id; ?>">
    <!-- Admin Hidden Name -->
    <input type="hidden" id="name" value="<?php echo $name; ?>">

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
        var id = $("#admin_id_FK").val();
        var name = $("#name").val();
        var from = '';
        var to = '';

        // Load Lead Count Table On Page Initialization
        $(document).ready(function () {
            fetchLeadCount();
            getResult();
        });

        // Fetch Lead Count Table
        function fetchLeadCount()
        {
            $("#leadCountTable").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                "type": "POST",
                "url": "ajax/fetch/lead_count.php",
                "data": {id:id, from:from, to:to}
                },
            "columns": [
                { data: 'sr_no'},
                { data: 'leads_count_date'},
                { data: 'total_leads'},
            ],
            "pageLength": 10,
            "searching": true,
            });
        }

        // Date Range
        $("#leadCountDateRangebtn-spec").click(function(){
            from = $("#from").val();
            to = $("#to").val();
            if(from==="" || to==="")
            {
                toaster("Please select date range in a proper manner",5);
            }
            else
            {
                $("#leadCountDateRangeModal").modal("hide");
                $("#leadCountTable").DataTable().destroy();
                fetchLeadCount();
                getResult();

                // For Table Heading
                $.ajax({
                    type: "POST",
                    url: "ajax/headings/lead_count.php",
                    data: {from:from, to:to, name:name},
                    success: function (response) 
                    {
                        $(".heading").text(response);
                    }
                });
            }
        });

        // Export As Excel
        $("#exportLeadCountbtn-spec").click(function(){
            window.location.href=`Export/lead_count.php?id=${id}&name=${name}&from=${from}&to=${to}`;
        });

        // Get Sum Of Total Leads
        function getResult()
        {
            $.ajax({
                type: "POST",
                url: "ajax/fetch/sum_average.php",
                data: {id:id, from:from, to:to},
                success: function (response) 
                {
                    let result = JSON.parse(response);
                    $("#leadSum").text(result.sum);
                    $("#leadAverage").text(result.average); 
                }
            });
        }

    </script> 
</body>
</html>