<?php
include("dbConnect.php");
include("auth.php");
$id = $_GET['id']??"";
$name = $_GET['name']??"";
if($id==="" || $name==="")
{
    header("Location:all_attendance.php");
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
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
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">OVERALL ATTENDANCE HISTORY</h1>
    </section>



    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                <div class="card-header">
                <h2 class="text-center t-bold heading"> <?php echo strtoupper($name); ?> REPORT </h2>
                </div>    
                    <div class="card-header d-flex justify-content-end">
                         <!-- Only For Admin -->
                        <?php if($_SESSION['login_type']==1): ?>
                        <!-- Mark Absent -->
                        <button type="button" class="btn-spec primary me-1" title="Mark Absent"
                        data-bs-toggle="modal" data-bs-target="#markAbsentModal"><i class="fas fa-pen"></i> Mark Absent </button>
                        <?php endif; ?>  
                        
                        <!-- Only For Admin & Manager -->
                        <?php if($_SESSION['login_type']==1 || $_SESSION['role']==="Manager"): ?>
                        <!-- Date Filter -->
                        <button type="button" class="btn-spec primary me-1" title="Use date range"
                        data-bs-toggle="modal" data-bs-target="#attendanceDateRangeModal"><i class="far fa-calendar"></i> Date Filter</button>                     
                        <!-- Export As Excel -->
                        <button type="button" id='exportAttendancebtn-spec' title="Export as excel"
                        class='btn-spec gren me-1'><i class="fas fa-file-excel"></i> Export</button>          
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class='table text-center' id='attendanceTable' style='width:100%;'>
                            <thead> 
                                <tr>
                                    <th>HIDDEN ID</th>
                                    <th>SR.NO</th>
                                    <th>DATE OF ABSENCE</th>
                                    <th>TYPE OF ABSENCE</th>
                                    <th>REASON OF ABSENCE</th>
                                    <th>ABSENT MARKED BY</th>
                                    <th>OPERATIONS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark Absent Modal -->
    <div class="modal fade" id="markAbsentModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">MARK ABSENT</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="markAbsentForm">
                        <!-- Employee ID (Hidden) -->
                        <input type="hidden" id="employee_id" value="<?php echo $id; ?>" readonly>
                        <!-- Employee Name (Hidden) -->
                        <input type="hidden" id="employee_name" value="<?php echo $name; ?>" readonly>
                        <!-- Type Of Absence -->
                        <div class="form-group">
                            <label for="type">Type Of Absence (Required)</label>
                            <select id="type" class="form-control">
                                <option value="">Select</option>
                                <option value="Absent">Absent</option>
                                <option value="Half Day">Half Day</option>
                            </select>
                        </div>   
                        <!-- Date -->
                        <div class="form-group">
                            <label for="date_of_absence">Date Of Absence (Required)</label>
                            <input type="date" id="date_of_absence" class="form-control mb-1">
                        </div>                                                
                        <!-- Reason -->
                        <div class="form-group">
                            <label for="reason">Reason Of Absence (Required)</label>
                            <textarea id="reason" cols="30" rows="3" class="form-control" placeholder="Specify Reason"></textarea>
                        </div>                                                                                                                                                                                                                              
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="markAbsentbtn-spec">Save</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Mark Absent Modal -->
    <div class="modal fade" id="editMarkAbsentModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">EDIT ABSENT DETAILS</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editMarkAbsentForm">
                        <!-- Type Of Absence -->
                        <div class="form-group">
                            <label for="edit_type">Type Of Absence (Required)</label>
                            <select id="edit_type" class="form-control">
                                <option value="">Select</option>
                                <option value="Absent">Absent</option>
                                <option value="Half Day">Half Day</option>
                            </select>
                        </div>                          
                        <!-- Date -->
                        <div class="form-group">
                            <label for="edit_date_of_absence">Date Of Absence (Required)</label>
                            <input type="date" id="edit_date_of_absence" class="form-control mb-1">
                        </div>                         
                        <!-- Reason -->
                        <div class="form-group">
                            <label for="edit_reason">Reason Of Absence (Required)</label>
                            <textarea id="edit_reason" cols="30" rows="3" class="form-control" placeholder="Specify Reason"></textarea>
                        </div>                                                                                                                                                                                                       
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="attendance_hidden_id">
                    <button type="button" class="btn-spec primary" id="updateMarkAbsentbtn-spec">Update</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Date Range Modal -->
    <div class="modal fade" id="attendanceDateRangeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">SELECT DATA RANGE</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="attendanceDateRangeForm">                       
                        <!-- From -->
                        <div class="form-group">
                            <label for="from">From</label>
                            <input type="date" id="from" class="form-control mb-1">
                        </div>  
                        <!-- To -->
                        <div class="form-group">
                            <label for="reason">To</label>
                            <input type="date" id="to" class="form-control mb-1">
                        </div>                                                                                                                                                                                                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="attendanceDateRangebtn-spec">Search</button>
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

var employee_id = $("#employee_id").val(); 
var employee_name = $("#employee_name").val();
var from = "";
var to = "";

// Date Filter
$("#attendanceDateRangebtn-spec").click(function(){
    from = $("#from").val();
    to = $("#to").val();
    if(from==="" || to==="")
    {
        toaster("Please select date range in a proper manner",5);
    }
    else
    {
        $("#attendanceDateRangeModal").modal("hide");
        $("#attendanceTable").DataTable().destroy();
        fetchAttendance();

        // For Table Heading
        $.ajax({
            type: "POST",
            url: "ajax/headings/attendance.php",
            data: {from:from, to:to, employee_name:employee_name},
            success: function (response) 
            {
                $(".heading").text(response);
            }
        });
    }
});

// Export As Excel
$("#exportAttendancebtn-spec").click(function(){
    window.location.href=`Export/attendance.php?employee_id=${employee_id}&employee_name=${employee_name}
    &from=${from}&to=${to}`;
});

// Fetch Attendance
function fetchAttendance()
{
    $("#attendanceTable").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
        "type": "POST",
        "url": "ajax/fetch/attendance.php", 
        "data": {employee_id:employee_id, from:from, to:to},
        },
        "columns": [
            { data: 'id', className:'d-none'},
            { data: 'sr_no'},
            { data: 'date_created'},
            { data: 'type' },
            { data: 'reason' },
            { data: 'marked_by' },
            { 
                "render": function (data, type, full, meta) {

                    var buttons = '';
                    <?php if($_SESSION['login_type']==1): ?>
                    buttons += `<button class="btn-spec primary me-1" title="Edit Record" 
                    onclick="editAttendance(${full.id})"><i class="fas fa-edit"></i> Edit</button>`;

                    buttons += `<button class="btn-spec btn-spec-danger-spec me-1" title="Delete Record" 
                    onclick="deleteAttendance(${full.id})"><i class="fas fa-trash-alt"></i> Delete</button>`;
                    <?php endif; ?>

                    return buttons;
                }, <?php if($_SESSION['login_type']==2): ?> 
                    className:'operations-column d-none'
                    <?php endif; ?> 
            }
        ],
        "pageLength": 10,
        "searching": true,
    });
}   

// Load Attendance Table On Page Initialization
$(document).ready(function () {
  fetchAttendance();
});

// Mark Absent
$("#markAbsentbtn-spec").click(function(){
  let reason = $("#reason").val();
  let type = $("#type").val();
  let date_of_absence = $("#date_of_absence").val();
  if(type==="")
  {
    toaster("Please specify the type of absence",5);
  }
  else
  {
    if(date_of_absence==="")
    {
        toaster("Please select date",5);
    }
    else
    {
        if(reason==="")
        {
            toaster("Please specify reason",5);
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "ajax/add/attendance.php",
                data: {employee_id:employee_id, employee_name:employee_name, 
                type:type, reason:reason, date_of_absence:date_of_absence},
                success: function (response) 
                {
                    if(response==="Absent has been marked")
                    {
                        let currentPage = $("#attendanceTable").DataTable().page();
                        $("#attendanceTable").DataTable().destroy();
                        fetchAttendance();
                        toaster(response,5);
                        $("#reason,#date_of_absence,#type").val("");
                        $("#markAbsentModal").modal("hide");

                        // Set the current page after reinitializing DataTable
                        $("#attendanceTable").DataTable().one('draw', function () {
                            $("#attendanceTable").DataTable().page(currentPage).draw('page');
                        }); 
                    }
                    else if(response==="Absent has not been marked")
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

// Edit Attendance
function editAttendance(id)
{
  $("#attendance_hidden_id").val(id);
  $.ajax({
    type: "POST",
    url: "ajax/edit/attendance.php",
    data: {id:id},
    success: function (response) 
    {
      let attn = JSON.parse(response);
      $("#edit_type").val(attn.type);
      $("#edit_date_of_absence").val(attn.date_created);
      $("#edit_reason").val(attn.reason);
      $("#editMarkAbsentModal").modal("show");
    }
  });
}

// Update Attendance
$("#updateMarkAbsentbtn-spec").click(function(){
 let id = $("#attendance_hidden_id").val();
 let type = $("#edit_type").val();
 let reason = $("#edit_reason").val();
 let date_of_absence = $("#edit_date_of_absence").val();
 if(type==="")
 {
    toaster("Please specify the type of absence",5);
 }
 else
 {
    if(date_of_absence==="")
    {
        toaster("Please select date");
    }
    else
    {
        if(reason==="")
        {
            toaster("Please specify reason");
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "ajax/update/attendance.php",
                data: {id:id, type:type, reason:reason, date_of_absence:date_of_absence},
                success: function (response) 
                {
                    if(response==="Record has been updated")
                    {
                        let currentPage = $("#attendanceTable").DataTable().page();
                        $("#attendanceTable").DataTable().destroy();
                        fetchAttendance();
                        toaster(response,5);
                        $("#editMarkAbsentModal").modal("hide");

                        // Set the current page after reinitializing DataTable
                        $("#attendanceTable").DataTable().one('draw', function () {
                            $("#attendanceTable").DataTable().page(currentPage).draw('page');
                        }); 
                    }
                    else if(response==="Record has not been updated")
                    {
                        toaster(response,5);
                    }
                    else
                    {
                        toaster("An unknown error occured",5);
                    }
                }
            });
        }
    }
 }
});

// Delete Attendance
function deleteAttendance(id)
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
          url: "ajax/delete/attendance.php",
          data: {id:id},
          success:function(response) 
          {
            if(response==="Absent has been removed")
            {
                let currentPage = $("#attendanceTable").DataTable().page();
                $("#attendanceTable").DataTable().destroy();
                fetchAttendance();
                toaster(response,5);

                // Set the current page after reinitializing DataTable
                $("#attendanceTable").DataTable().one('draw', function () {
                    $("#attendanceTable").DataTable().page(currentPage).draw('page');
                }); 
            }
            else if(response==="Absent has not been removed")
            {
              toaster(response,5);
            }
            else
            {
              toaster("An unknown error occured",5);
            }
          }
        });
      }
  }));
}      
    </script>    
</body>
</html>