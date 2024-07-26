<?php
include("dbConnect.php");
include("auth.php");
$employee_id = $_SESSION['employee_id'];
$query = "SELECT account1, account2 FROM employees WHERE id='$employee_id'";
$query2 = "SELECT name FROM account_credentials ORDER BY name";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS v5.2.1 & Other CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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

    <!-- Notification Message -->
    <div class="notification-container">
        <div id="notification" class="notification">
            <h5 class="text-center py-3 t-bold" id="notification-text"></h5>
        </div>
    </div>

    <!-- Header -->
    <section class="header-section">  
        <?php include("navbar.php"); ?>   
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">LET'S GENERATE RESULT ORIENTED LEADS</h1>
    </section>


    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header">
                    <div class="col-md-12 d-flex justify-content-end">

                        <!-- Target Result -->
                        <button type="button" class="btn-spec gren me-1" title="Check Result"
                        data-bs-toggle="modal" data-bs-target="#resultModal"><i class="fas fa-award"></i> Monthly Competition</button>

                        <!-- Add Lead Modal -->
                        <button type="button" class="btn-spec primary me-1" title="Add new lead"
                        data-bs-toggle="modal" data-bs-target="#addLeadModal"><i class="fas fa-plus"></i> Add New</button>


                        <!-- Only For Admin, Manager & Closer -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager" || $_SESSION['role']==="Closer"): ?>
                        <!-- Date Filter -->
                        <button type="button" class="btn-spec primary me-1" title="Use date range"
                        data-bs-toggle="modal" data-bs-target="#leadDateRangeModal"><i class="far fa-calendar"></i> Date Filter</button>
                        <!-- Export button -->
                        <button type="button" id="exportLeadbtn-spec" title="Export as excel"
                        class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button>
                        <?php endif; ?>
                    </div>
                    </div>
                    <div class="card-body">
                        <table class='table text-center'
                        <?php if($_SESSION['login_type']==2 && $_SESSION['role']!=="Manager") { echo "style='width:100%'"; } ?>
                        style='width:120%;' id='leadsTable'>
                            <thead>
                                <tr>
                                    <th>HIDDEN ID</th>
                                    <th>ADMIN HIDDEN ID</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:9%;'"; } ?> >TIMESTAMP</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:9%;'"; } ?> >EMPLOYEE</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:7%;'"; } ?> >ACCOUNT</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:3%;'"; } ?> >PLATFORM</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:4%;'"; } ?> >CLIENT</th>
                                    <th <?php if($_SESSION['login_type']==1){ echo "style='width:8%;'"; } ?> >CHANNEL LINK</th>
                                    <th style='width:8%;'>COMMENTS</th>
                                    <th style="width:4%;">STATUS</th>
                                    <th style="width:8%;">REJECTION</th>
                                    <th style="width:5%;">QUALITY</th>
                                    <th>OPERATIONS</th>
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

    <!-- Result Modal -->
    <div class="modal fade" id="resultModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-center" style="margin-left: auto;"> <?php echo strtoupper(date('F-Y')); ?> <i class="fas fa-trophy" style="color:gold;"></i></h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Query to calculate Number of employees in each team -->
                <?php
                    $query_members = "SELECT 
                    COALESCE(COUNT(CASE WHEN e.shift = 'Morning' THEN 1 END),0) AS morning,
                    COALESCE(COUNT(CASE WHEN e.shift = 'Night' THEN 1 END),0) AS night
                    FROM employees e INNER JOIN admins a
                    ON e.id = a.employee_id_FK
                    WHERE a.type=2";
                    $result_members = mysqli_query($conn,$query_members);
                    $data_members = mysqli_fetch_assoc($result_members);
                ?>
                <div class="modal-body">
                    <?php
                        // For Morning (Query to calculate team target)
                        $query_morning = "SELECT COALESCE(SUM(t.amount),0) AS amount
                        FROM targets t INNER JOIN employees e 
                        ON e.id = t.employee_id_FK
                        WHERE e.shift='Morning'
                        AND MONTH(t.date_created) = MONTH(CURRENT_DATE()) AND YEAR(t.date_created) = YEAR(CURRENT_DATE())";
                        $result_morning = mysqli_query($conn,$query_morning);
                        $data_morning = mysqli_fetch_assoc($result_morning);

                        // For Night (Query to calculate team target)
                        $query_night = "SELECT COALESCE(SUM(t.amount),0) AS amount
                        FROM targets t INNER JOIN employees e 
                        ON e.id = t.employee_id_FK
                        WHERE e.shift='Night'
                        AND MONTH(t.date_created) = MONTH(CURRENT_DATE()) AND YEAR(t.date_created) = YEAR(CURRENT_DATE())";
                        $result_night = mysqli_query($conn,$query_night);
                        $data_night = mysqli_fetch_assoc($result_night);
                    ?>
                    <!-- Morning -->
                    <div class="card shadow morningcard">
                        <div class="card-header">
                            <u>  <h2 class="text-center">TEAM MORNING</h2> </u>
                            <h5 class="text-center">(11:00 AM To 8:00 PM)</h5>
                            <h5 class="text-center">Total Members: <?php echo $data_members['morning']; ?></h5>
                            <h5 class="text-center">Target Achieved: $<?php echo $data_morning['amount']; ?></h5>
                        </div>
                    </div>

                    <h1 class="text-center" style="font-weight: 900;"> VS </h1>

                    <!-- Night -->
                    <div class="card shadow nightcard">
                        <div class="card-header">
                            <u> <h2 class="text-center">TEAM NIGHT</h2></u>
                            <h5 class="text-center">(8:00 PM To 5:00 AM)</h5>
                            <h5 class="text-center">Total Members: <?php echo $data_members['night']; ?></h5>
                            <h5 class="text-center">Target Achieved: $<?php echo $data_night['amount']; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                   
                </div>
            </div>
        </div>
    </div>    

    <!-- Add Lead Modal -->
    <div class="modal fade" id="addLeadModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">ADD YOUR LEAD</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addLeadForm">
                        <!-- Account Name (Required) -->
                        <div class="form-group mb-1">
                            <label for="account_name">Account Name (Required)</label>
                            <select id="account_name" class="form-control">
                                <option value="">Select Account</option>
                                <?php

                                    $result2 = mysqli_query($conn,$query2);
                                    while($data2 = mysqli_fetch_assoc($result2))
                                    {
                                        echo "<option value='$data2[name]'>".$data2['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>                       
                        <!-- Platform Name (Required) -->
                        <div class="form-group mb-1">
                            <label for="platform_name">Platform Name (Required)</label>
                            <select id="platform_name" class="form-control">
                                <option value="">Select Platform</option>
                                <option value="Discord">Discord</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Twitter">Twitter</option>
                            </select>
                        </div>
                        <!-- Client Username Link (Required) -->
                        <div class="form-group mb-1">
                            <label for="client_username">Client Username (Required)</label>
                            <input type="text" id="client_username" placeholder="Enter Client Username" class="form-control">
                        </div>                         
                        <!-- Twitch Channel Link (Optional) -->
                        <div class="form-group mb-1">
                            <label for="channel_link">Twitch Channel Link (Optional)</label>
                            <input type="text" id="channel_link" placeholder="Enter Twitch Channel Link" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="addLeadbtn-spec">Add</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Lead Modal -->
    <div class="modal fade" id="editLeadModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">EDIT LEAD INFO</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editLeadForm">
                        <!-- Account Name -->
                        <div class="form-group mb-1">
                            <label for="edit_account_name">Account Name (Required)</label>
                            <select id="edit_account_name" class="form-control">
                                <option value="">Select Account</option>
                                <?php

                                    $result2 = mysqli_query($conn,$query2);
                                    while($data2 = mysqli_fetch_assoc($result2))
                                    {
                                        echo "<option value='$data2[name]'>".$data2['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>                       
                        <!-- Platform Name -->
                        <div class="form-group mb-1">
                            <label for="edit_platform_name">Platform Name (Required)</label>
                            <select id="edit_platform_name" class="form-control">
                                <option value="">Select Platform</option>
                                <option value="Discord">Discord</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Twitter">Twitter</option>
                            </select>
                        </div>
                        <!-- Client Username -->
                        <div class="form-group mb-1">
                            <label for="edit_client_username">Client Username (Required)</label>
                            <input type="text" id="edit_client_username" placeholder="Enter Client Username" class="form-control">
                        </div>                         
                        <!-- Twtich Channel Link -->
                        <div class="form-group mb-1">
                            <label for="edit_channel_link">Twitch Channel Link (Optional)</label>
                            <input type="text" id="edit_channel_link" placeholder="Enter Twitch Channel Link" class="form-control">
                        </div>
                        <!-- For Admin & Manager -->
                        <div class="<?php if($_SESSION['login_type']==2 && $_SESSION['role']==="Lead Generation"){ echo "d-none"; } ?>">
                        <!-- Comments -->
                        <div class="form-group mb-1">
                            <label for="comments">Comments (Optional)</label>
                            <textarea id="comments" cols="30" rows="2" placeholder="Enter Comments" class="form-control"></textarea>
                        </div>
                        <!-- Status -->
                        <div class="form-group mb-1">
                            <label for="status">Status (Required)</label>
                            <select id="status" class="form-control">
                                <option value="">Select</option>
                                <option value="Pending">Pending</option>
                                <option value="Closed">Closed</option>
                                <option value="Follow up date">Follow up date</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div> 
                        <!-- Items -->
                        <div class="form-group mb-1" id="items_field">
                            <label for="items">Stream Designs (Required)</label>
                            <textarea id="items" cols="30" rows="2" placeholder="Enter Items" class="form-control"></textarea>
                        </div>                         
                        <!-- Amount -->
                        <div class="form-group mb-1" id="amount_field">
                            <label for="amount">Closed Amount (Required)</label>
                            <input type="number" id="amount" placeholder="Enter Amount" class="form-control">
                        </div>                         
                        <!-- Follow-up Date -->
                        <div class="form-group mb-1" id="followup_field">
                            <label for="follow_up_date">Follow-up-date With Remarks (Required)</label>
                            <textarea id="follow_up_date" cols="30" rows="2" placeholder="Enter Follow-up-date With Remarks" class="form-control"></textarea>
                        </div>                         
                        <!-- Reason Of Rejection -->
                        <div class="form-group mb-1" id="rejection_field">
                            <label for="reason_of_rejection">Reason Of Rejection (Required)</label>
                            <input type="text" id="reason_of_rejection" placeholder="Please Specify Reason" class="form-control">
                        </div>  
                        </div>                     
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- Hidden Fields -->
                    <input type="hidden" id="hiddenLeadId">
                    <input type="hidden" id="hiddenAdminId">
                    <input type="hidden" id="hiddenEmployeeId">
                    <input type="hidden" id="hiddenEmployeeName">
                    <input type="hidden" id="quality_of_lead">
                    <button type="button" class="btn-spec primary" id="updateLeadbtn-spec">Update</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Modal -->
    <div class="modal fade" id="leadDateRangeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
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
                    <button type="button" class="btn-spec primary" id="leadDateRangebtn-spec">Search</button>
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
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>

    <script src="dist/sweetalert2.all.min.js"></script>
    <script src="script.js"></script> 
    <script>
    var status = '';
    var from = '';
    var to = '';

    $(document).ready(function () {
        $("#items_field").hide(); 
        $("#amount_field").hide();
        $("#followup_field").hide();
        $("#rejection_field").hide();     

        // Show Fields According To The Status
        $("#status").change(function(){
            status = $("#status").val();

            // If status is (Closed)
            if(status==="Closed")
            {
                $("#items_field").slideDown('fast');
                $("#amount_field").slideDown('fast');           
            }
            else
            {
                $("#items_field").slideUp('fast');
                $("#amount_field").slideUp('fast');               
            }

            // If status is (Follow up)
            if(status==="Follow up date")
            {
                $("#followup_field").slideDown('fast');
            }
            else
            {
                $("#followup_field").slideUp('fast');
            }

            // If status is (Rejected)
            if(status==="Rejected")
            {
                $("#rejection_field").slideDown('fast');
            }
            else
            {
                $("#rejection_field").slideUp('fast');
            }
        });      
    });

    // Date Filter
    $("#leadDateRangebtn-spec").click(function(){
        from = $("#from").val();
        to = $("#to").val();
        if(from==="" || to==="")
        {
            toaster("Please select date range in a proper manner",5);
        }
        else
        {
            $("#leadDateRangeModal").modal("hide");
            $("#leadsTable").DataTable().destroy();
            fetchLeads();

            // For Table Heading
            $.ajax({
                type: "POST",
                url: "ajax/headings/lead.php",
                data: {from:from, to:to},
                success: function (response) 
                {
                    $(".t-bold").text(response);
                }
            });
        }
    });

    // Export As Excel
    $("#exportLeadbtn-spec").click(function(){
        window.location.href=`Export/lead.php?from=${from}&to=${to}`;
    });    

    // Fetch Lead Generation
    function fetchLeads()
    {
        $("#leadsTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
            "type": "POST",
            "url": "ajax/fetch/lead.php",
            "data": {from:from, to:to} 
            },
        "columns": [
            { data: 'id', className:'d-none'}, // Lead Hidden ID
            { data: 'admin_id_FK', className:'d-none'}, // Admin Hidden ID
            { data: 'lead_timestamp'},
            { data: 'employee_name'},
            { data: 'account_name'},
            { data: 'platform_name'},
            { data: 'client_username', className:'copied'},
            { data: 'channel_link', className:'copied'},
            { data: 'comments'},
            { data: 'status'},
            { data: 'reason_of_rejection'},
            { data: 'quality_of_lead'},
            { 
                "render": function (data, type, full, meta) 
                {
                    var buttons = '';

                    <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Lead Generation" || $_SESSION['role']==="Manager"): ?>
                    buttons += `<button type="button" class="btn-spec primary me-1" title="Edit Record"
                    onclick="editLead(${full.id})"><i class="fas fa-edit"></i> Edit</button>`;
                    <?php endif; ?>

                    // Delete Button Access Only For Admin 
                    <?php if($_SESSION['login_type']==1): ?>
                    buttons += `<button type='button' class='btn-spec btn-spec-danger-spec me-1' title='Delete record' 
                    onclick="deleteLead(${full.id})"><i class="fas fa-trash-alt"></i> Delete</button>`;
                    <?php endif; ?>                    

                    <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager"): ?>
                    buttons += `<button type='button' class='btn-spec qualitybtn-spec gren me-1'
                    data-id="${full.id}" data-quality-of-lead="Qualified"
                    title='Mark as qualified'><i class="fas fa-check-circle"></i> Qualified</button>`; 

                    buttons += `<button type='button' class='btn-spec btn-spec-danger-spec qualitybtn-spec'
                    data-id="${full.id}" data-quality-of-lead="Disqualified" 
                    title='Mark as disqualified'><i class="fas fa-ban"></i> Disqualified</button>`;
                    <?php endif; ?>

                    return buttons;
                }, <?php if($_SESSION['login_type']==2 && $_SESSION['role']==="Closer"): ?>
                    className: 'd-none'
                    <?php endif; ?>
                    className: 'operations-column'
            },
            
        ],
        "pageLength": 10,
        "searching": true,
        });
    }
    $("#toggleDarkMode").on("click", function () {
    $("#leadsTable").toggleClass("dark-mode");
});
    // Load Lead Generation Table On Page Initialization
    $(document).ready(function () {
        fetchLeads();
    });

    // Add New Lead
    $("#addLeadbtn-spec").click(function(){
        let account_name = $("#account_name").val();
        let platform_name = $("#platform_name").val();
        let client_username = $("#client_username").val();
        let channel_link = $("#channel_link").val();
  
        if(account_name==="" || account_name==="Not Assigned")
        {
            toaster("Please select account name",5);
        }
        else
        {
            if(platform_name==="")
            {
                toaster("Please select Platform",5);
            }
            else
            {
                if($.trim(client_username)==="")
                {
                    toaster("Please enter client's username",5);
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        url: "ajax/add/lead.php",
                        data: { account_name:account_name, platform_name:platform_name, 
                        client_username:client_username, channel_link:channel_link },
                        success: function (response) 
                        {
                            if(response==="Your lead has been added")
                            {
                                let currentPage = $("#leadsTable").DataTable().page();
                                $("#leadsTable").DataTable().destroy();
                                fetchLeads();
                                toaster(response,5);
                                $("#account_name, #platform_name, #client_username, #channel_link").val("");
                                $("#addLeadModal").modal("hide");

                                // Set the current page after reinitializing DataTable
                                $("#leadsTable").DataTable().one('draw', function () {
                                    $("#leadsTable").DataTable().page(currentPage).draw('page');
                                }); 
                            }
                            else if(response==="Your lead has not been added")
                            {
                                toaster(response,5);
                            }
                            else
                            {
                                toaster(response,5);
                            }
                        }
                    });
                }
            }
        }
    });

    // Edit Lead
    function editLead(id)
    {
        $("#hiddenLeadId").val(id);
        $.ajax({
            type: "POST",
            url: "ajax/edit/lead.php",
            data: {id:id},
            success: function (response) 
            {
                var lead = JSON.parse(response);
                // For User
                $("#edit_account_name").val(lead.account_name);
                $("#edit_platform_name").val(lead.platform_name);
                $("#edit_client_username").val(lead.client_username);
                $("#edit_channel_link").val(lead.channel_link);

                // For Hidden
                $("#hiddenAdminId").val(lead.admin_id_FK);
                $("#hiddenEmployeeId").val(lead.employee_id_FK);
                $("#hiddenEmployeeName").val(lead.employee_name);
                $("#quality_of_lead").val(lead.quality_of_lead);

                // For Admin
                $("#comments").val(lead.comments);
                $("#status").val(lead.status);
                $("#follow_up_date").val(lead.follow_up_date_with_remarks);
                $("#reason_of_rejection").val(lead.reason_of_rejection);
                $("#items").val(lead.items);
                $("#amount").val(lead.amount);

                // If status is (Closed)
                if(lead.status==="Closed")
                {
                    $("#items_field").show();
                    $("#amount_field").show();
                }
                else
                {
                    $("#amount_field").hide();
                    $("#items_field").hide();
                }

                // If status is (Follow up)
                if(lead.status==="Follow up date")
                {
                    $("#followup_field").show();
                }
                else
                {
                    $("#followup_field").hide();
                }

                // If status is (Rejected)
                if(lead.status==="Rejected")
                {
                    $("#rejection_field").show();
                }
                else
                {
                    $("#rejection_field").hide();
                }
                $("#editLeadModal").modal("show");
            }
        });
    }

    // Update Lead
    $("#updateLeadbtn-spec").click(function(){

        let id = $("#hiddenLeadId").val();
        let admin_id = $("#hiddenAdminId").val();
        let employee_id = $("#hiddenEmployeeId").val();
        let employee_name = $("#hiddenEmployeeName").val();
        let account_name = $("#edit_account_name").val();
        let platform_name = $("#edit_platform_name").val();
        let client_username = $("#edit_client_username").val();
        let channel_link = $("#edit_channel_link").val();
        let comments = $("#comments").val();
        let status = $("#status").val();
        let follow_up_date_with_remarks = $("#follow_up_date").val();
        let reason_of_rejection = $("#reason_of_rejection").val();
        let items = $("#items").val();
        let quality_of_lead = $("#quality_of_lead").val();
        let amount = $("#amount").val();

        function UpdateLead()
        {
            $.ajax({
                type: "POST",
                url: "ajax/update/lead.php",
                data: {id:id, admin_id:admin_id ,employee_id:employee_id, 
                employee_name:employee_name ,account_name:account_name, 
                platform_name:platform_name, client_username:client_username,
                channel_link:channel_link, comments:comments, status:status, 
                follow_up_date_with_remarks:follow_up_date_with_remarks, 
                reason_of_rejection:reason_of_rejection, items:items,
                quality_of_lead:quality_of_lead, amount:amount},
                success: function (response) 
                {
                    if(response==="Deal closed")
                    {
                        swal.fire({
                            title: "CONGRATULATIONS",
                            text: "Deal has been closed",
                            icon: 'success',
                            confirmButtonColor: 'blue',
                            showCloseButton: true,
                            allowOutsideClick: false,                                    
                            timer:3000,
                        });
                        let currentPage = $("#leadsTable").DataTable().page();
                        $("#leadsTable").DataTable().destroy();
                        fetchLeads();
                        $("#editLeadModal").modal("hide");

                        // Set the current page after reinitializing DataTable
                        $("#leadsTable").DataTable().one('draw', function () {
                            $("#leadsTable").DataTable().page(currentPage).draw('page');
                        }); 
                    }
                    else if(response==="Lead has been updated")
                    {
                        let currentPage = $("#leadsTable").DataTable().page();
                        $("#leadsTable").DataTable().destroy();
                        fetchLeads();
                        toaster(response,5);
                        $("#editLeadModal").modal("hide");

                        // Set the current page after reinitializing DataTable
                        $("#leadsTable").DataTable().one('draw', function () {
                            $("#leadsTable").DataTable().page(currentPage).draw('page');
                        }); 
                    }
                    else if(response==="Lead has not been updated")
                    {
                        toaster(response,5);
                    }
                    else
                    {
                        toaster(response,5);
                    }
                }
            });
        }
  
        if(account_name==="")
        {
            toaster("Please select account name",5);
        }
        else
        {
            if(platform_name==="")
            {
                toaster("Please select Platform",5);
            }
            else
            {
                if($.trim(client_username)==="")
                {
                    toaster("Please enter client's username",5);
                }
                else
                {
                    if(status==="")
                    {
                        toaster("Please specify the current status of this lead",5);
                    }
                    else
                    {
                        // (IF STATUS IS CLOSED)
                        if(status==="Closed")
                        {
                            if($.trim(items)==="" || items===null || items===undefined || items==="-")
                            {
                                toaster("Please enter closed items",5);
                            }
                            else
                            {
                                if(amount==0 || amount<1 || amount==null || amount==undefined || amount=="")
                                {
                                    toaster("Please enter closed amount. The closed amount must be greater than 0",5);
                                }
                                else
                                {
                                    UpdateLead();
                                }                            
                            }
                        }
                        // IF STATUS IS (FOLLOW UP)
                        else if(status==="Follow up date")
                        {
                            if($.trim(follow_up_date_with_remarks)==="")
                            {
                                toaster("Please specify follow up date with remarks",5);
                            }
                            else
                            {
                                UpdateLead();                         
                            }
                        }
                        // IF STATUS IS (REJECTED)
                        else if(status==="Rejected")
                        {
                            if($.trim(reason_of_rejection)==="")
                            {
                                toaster("Please specify reason of rejection",5);
                            }
                            else
                            {
                                UpdateLead(); 
                            }
                        }
                        // IF STATUS IS (PENDING)
                        else if(status==="Pending")
                        {
                            UpdateLead();
                        }
                    }
                }
            }
        }
    });

    // Delete Lead
    function deleteLead(id)
    {
        swal.fire({
            title: "ARE YOU SURE?",
            text: "Once deleted, you won't be able to revert this action!",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            cancelButtonColor: 'red',
            confirmButtonText: 'Yes! Delete it',
            confirmButtonColor: 'blue',
            showCloseButton: true,
            allowOutsideClick: false
        }).then((result => {
            if(result.isConfirmed)
            {
                $.ajax({
                    type: "POST",
                    url: "ajax/delete/lead.php",
                    data: {id:id},
                    success: function (response) 
                    {
                        if(response==="Lead has been deleted")
                        {
                            let currentPage = $("#leadsTable").DataTable().page();
                            $("#leadsTable").DataTable().destroy();
                            fetchLeads();
                            toaster(response,5);

                            // Set the current page after reinitializing DataTable
                            $("#leadsTable").DataTable().one('draw', function () {
                                $("#leadsTable").DataTable().page(currentPage).draw('page');
                            }); 
                        }
                        else if(response==="Lead has not been deleted")
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
        }));
    }

    // Lead Approval
    $(document).on("click",".qualitybtn-spec",function(){
        let id = $(this).attr("data-id");
        let quality = $(this).attr("data-quality-of-lead");
        if(quality==="Qualified")
        {
            // Qualified
            $.ajax({
                type: "POST",
                url: "ajax/update/qualified.php",
                data: {id:id, quality:quality},
                success: function (response) 
                {
                    let currentPage = $("#leadsTable").DataTable().page();
                    $("#leadsTable").DataTable().destroy();
                    fetchLeads();
                    toaster(response);

                    // Set the current page after reinitializing DataTable
                    $("#leadsTable").DataTable().one('draw', function () {
                        $("#leadsTable").DataTable().page(currentPage).draw('page');
                    }); 
                }
            });
        }
        else
        {
            // Disqualified
            $.ajax({
                type: "POST",
                url: "ajax/update/disqualified.php",
                data: {id:id, quality:quality},
                success: function (response) 
                {
                    let currentPage = $("#leadsTable").DataTable().page();
                    $("#leadsTable").DataTable().destroy();
                    fetchLeads();
                    toaster(response);

                    // Set the current page after reinitializing DataTable
                    $("#leadsTable").DataTable().one('draw', function () {
                        $("#leadsTable").DataTable().page(currentPage).draw('page');
                    });                     
                }
            });
        }
    });
    <?php if($_SESSION['login_type']==2 && $_SESSION['role']=="Lead Generation"): ?>
    //Show Total Leads Notification
    function showNotification() 
    {
        let notification = 'notification';
        $.ajax({
            url: 'ajax/fetch/notification.php',
            type: 'POST',
            data:{notification:notification},
            success: function(response) 
            {
                if(response!=="false")
                {
                    $('#notification-text').text("YOU ARE "+response+" LEADS AWAY TO GET 1ST POSITION");
                    $('.notification').fadeIn('slow').delay(60000).fadeOut('slow');
                }
                else if(response==="false")
                {
                    $('.notification').fadeOut('slow');
                }
                else
                {
                    $('.notification').fadeOut('slow');
                }
            }
        });
    }

    setInterval(function() {
        showNotification();
    }, 60000*15); 
<?php endif; ?>    
</script>
</body>
</html>