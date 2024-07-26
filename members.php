<?php
include("dbConnect.php");
include("auth.php");
include("admin_auth.php");
$query = "SELECT name FROM account_credentials ORDER BY name";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
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
      <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">TEAM MEMBERS</h1>
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
                    <div class="card-header d-flex justify-content-end">
                        <!-- Add Employee Button -->
                      <button type="button" class="btn-spec primary me-1" title="Add new member"
                      data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fas fa-plus"></i> Add New</button>
                      <!-- Export As Excel Button -->
                      <a href="Export/employees.php" title="Export as excel">
                       <button class="btn-spec gren me-1"><i class="fas fa-file-excel"></i> Export</button></a>
                    </div>
                    <div class="card-body">
                        <table class='table text-center' style='width:120%;' id='employeesTable'>
                            <thead>
                                <tr>
                                    <th>HIDDEN ID</th>
                                    <th>SR.NO</th>
                                    <th>FULL NAME</th>
                                    <th>FATHER NAME</th>
                                    <th>CELL NO</th>
                                    <th>EMAIL</th>
                                    <th>CNIC NO</th>
                                    <th>ADDRESS</th>
                                    <th>SHIFT</th>
                                    <th>JOINING</th>
                                    <th>FIRST SALE</th>
                                    <th>OPERATIONS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">ADD NEW EMPLOYEE</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addEmployeeForm">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="full_name">Full Name (Required)</label>
                            <input type="text" id="full_name" class="form-control mb-1" placeholder="Enter Full Name">
                        </div>  
                        <!-- Father Name -->
                        <div class="form-group">
                            <label for="father_name">Father Name (Required)</label>
                            <input type="text" id="father_name" class="form-control mb-1" placeholder="Enter Father Name">
                        </div>   
                        <!-- Cell No -->
                        <div class="form-group">
                            <label for="cell_no">Cell No (Optional)</label>
                            <input type="number" id="cell_no" class="form-control mb-1" placeholder="Enter Cell No">
                        </div> 
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email (Optional)</label>
                            <input type="text" id="email" class="form-control mb-1" placeholder="Enter Email">
                        </div> 
                        <!-- CNIC NO -->
                        <div class="form-group">
                            <label for="cnic_no">CNIC No (Optional)</label>
                            <input type="text" id="cnic_no" class="form-control mb-1" placeholder="Enter CNIC No">
                        </div>  
                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address (Required)</label>
                            <textarea id="address" cols="30" rows="2" class="form-control mb-1" placeholder="Enter Address"></textarea>
                        </div>   
                        <!-- Shift -->
                        <div class="form-group">
                            <label for="shift">Shift (Required)</label>
                            <select id="shift" class="form-control mb-1">
                              <option value="">Select</option>
                              <option value="Morning">Morning</option>
                              <option value="Night">Night</option>
                            </select>
                        </div>                        
                        <!-- Date Of Joining -->
                        <div class="form-group">
                            <label for="doj">Date Of Joining (Required)</label>
                            <input type="date" id="doj" class="form-control mb-1">
                        </div>                                                                                                                                                                                 
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="addEmployeebtn-spec">Save</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">EDIT DETAILS</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editEmployeeForm">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="edit_full_name">Full Name (Required)</label>
                            <input type="text" id="edit_full_name" class="form-control mb-1" placeholder="Enter Full Name">
                        </div>  
                        <!-- Father Name -->
                        <div class="form-group">
                            <label for="edit_father_name">Father Name (Required)</label>
                            <input type="text" id="edit_father_name" class="form-control mb-1" placeholder="Enter Father Name">
                        </div>   
                        <!-- Cell No -->
                        <div class="form-group">
                            <label for="edit_cell_no">Cell No (Optional)</label>
                            <input type="number" id="edit_cell_no" class="form-control mb-1" placeholder="Enter Cell No">
                        </div> 
                        <!-- Email -->
                        <div class="form-group">
                            <label for="edit_email">Email (Optional)</label>
                            <input type="text" id="edit_email" class="form-control mb-1" placeholder="Enter Email">
                        </div> 
                        <!-- CNIC NO -->
                        <div class="form-group">
                            <label for="edit_cnic_no">CNIC No (Optional)</label>
                            <input type="text" id="edit_cnic_no" class="form-control mb-1" placeholder="Enter CNIC No">
                        </div>  
                        <!-- Address -->
                        <div class="form-group">
                            <label for="edit_address">Address (Required)</label>
                            <textarea id="edit_address" cols="30" rows="2" class="form-control mb-1" placeholder="Enter Address"></textarea>
                        </div>   
                        <!-- Shift -->
                        <div class="form-group">
                            <label for="edit_shift">Shift (Required)</label>
                            <select id="edit_shift" class="form-control mb-1">
                              <option value="">Select</option>
                              <option value="Morning">Morning</option>
                              <option value="Night">Night</option>
                            </select>
                        </div>                         
                        <!-- Date Of Joining -->
                        <div class="form-group">
                            <label for="edit_doj">Date Of Joining (Required)</label>
                            <input type="date" id="edit_doj" class="form-control mb-1">
                        </div>
                        <!-- Date Of First Sale -->
                        <div class="form-group">
                            <label for="first_sale">Date Of 1st Sale (Optional)</label>
                            <input type="date" id="first_sale" class="form-control mb-1">
                        </div>                        
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status (Required)</label>
                            <select id="status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Left">Left</option>
                            </select>
                        </div>                                                                                                                                                                                  
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="employee_hidden_id">
                    <button type="button" class="btn-spec primary" id="updateEmployeebtn-spec">Update</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Make Admin Modal -->
    <div class="modal fade" id="makeAdminModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">GIVE ACCESS</h2>
              <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="POST" id="makeAdminForm" autocomplete="off">
                <!-- Name -->
                <div class="form-group mb-1">
                  <label for="name2">Name (Required)</label>
                  <input type="text" id="name2" class="form-control" readonly>
                </div>
                <!-- Username -->
                <div class="form-group mb-1">
                  <label for="username">Username (Required)</label>
                  <input type="text" id="username" class="form-control" placeholder="Enter Username" autocomplete="off">
                </div> 
                <!-- Email -->
                <div class="form-group mb-1">
                  <label for="email2">Email (Optional)</label>
                  <input type="text" id="email2" class="form-control" placeholder="Enter Email" autocomplete="off">
                </div>                 
                <!-- Password -->
                <div class="form-group mb-1">
                  <label for="password">Password (Required)</label>
                  <input type="password" id="password" class="form-control" placeholder="Enter Password" autocomplete="off">
                </div> 
                <!-- Role -->
                <div class="form-group mb-1">
                  <label for="role">Role (Required)</label>
                  <select id="role" class="form-control">
                    <option value="">Assign Role</option>
                    <option value="Manager">Manager</option>
                    <option value="Closer">Closer</option>
                    <option value="Lead Generation">Lead Generation</option>
                  </select>
                </div>                                                
              </form>
            </div>
            <div class="modal-footer">
              <input type="hidden" id="employee_hidden_id2">
              <button type="button" class="btn-spec primary" id="giveAccessbtn-spec">Save</button>
              <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <!-- Assign Account Modal -->
    <div class="modal fade" id="assignAccountModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">ASSIGN ACCOUNTS</h2>
              <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="POST" id="assignAccountForm" autocomplete="off">                               
                <!-- Account 1 -->
                <div class="form-group mb-1">
                  <label for="account1">Account:01 (Optional)</label>
                  <select id="account1" class="form-control">
                    <option value="Not Assigned" class='text-danger' style="font-weight:bolder;">Not Assigned</option>
                    <?php
                    $result = mysqli_query($conn,$query);
                    while($data=mysqli_fetch_assoc($result))
                    {
                      echo "<option value='$data[name]'>".$data['name']."</option>";
                    }
                    ?>
                  </select>
                </div>
                <!-- Account 2 -->
                <div class="form-group mb-1">
                  <label for="account2">Account:02 (Optional)</label>
                  <select id="account2" class="form-control">
                    <option value="Not Assigned" class='text-danger' style="font-weight:bolder;">Not Assigned</option>
                    <?php
                    $result = mysqli_query($conn,$query);
                    while($data=mysqli_fetch_assoc($result))
                    {
                      echo "<option value='$data[name]'>".$data['name']."</option>";
                    }
                    ?>
                  </select>
                </div>                                                                                 
              </form>
            </div>
            <div class="modal-footer">
              <input type="hidden" id="employee_hidden_id3">
              <button type="button" class="btn-spec primary" id="assignAccountbtn-spec">Save</button>
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

// Fetch Employees
function fetchEmployees()
{
    $("#employeesTable").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
    "type": "POST",
    "url": "ajax/fetch/employees.php", 
    },
    "columns": [
      { data: 'id', className:'d-none'}, // Employee Hidden ID
      { data: 'sr_no'},
      { data: 'full_name' },
      { data: 'father_name' },
      { data: 'cell_no' },
      { data: 'email' },
      { data: 'cnic_no' },
      { data: 'address' },
      { data: 'shift' },
      { data: 'date_of_joining' },
      { data: 'first_sale' },
      { 
        "render": function (data, type, full, meta) 
        {
          var buttons = '';

          buttons += `<button type="button" class="btn-spec gren me-1" title="Delete Record" 
          onclick="makeAdmin(${full.id})"><i class="fas fa-unlock"></i> Give Access</button>`;

          buttons += `<button type="button" class="btn-spec primary me-1" title="Assign account"
          onclick="assignAccount(${full.id})"><i class="fas fa-id-card"></i> Assign Account</button>`;

          buttons += `<button type="button" class="btn-spec primary me-1" title="Edit Record" 
          onclick="editEmployee(${full.id})"><i class="fas fa-edit"></i> Edit</button>`;

          buttons += `<button type="button" class="btn-spec btn-spec-danger-spec me-1" title="Delete Record" 
          onclick="deleteEmployee(${full.id})"><i class="fas fa-trash-alt"></i> Delete</button>`;
              
          return buttons;
        }, className:'operations-column'
      }
    ],
    "pageLength": 10,
    "searching": true,
  });
}


// Load Employees Table On Page Initialization
$(document).ready(function () {
  fetchEmployees();
});

// Add New Employee
$("#addEmployeebtn-spec").click(function(){
  let full_name = $("#full_name").val();
  let father_name = $("#father_name").val();
  let cell_no = $("#cell_no").val();
  let email = $("#email").val();
  let cnic_no = $("#cnic_no").val();
  let address = $("#address").val();
  let shift = $("#shift").val();
  let doj = $("#doj").val();
  if(full_name==="")
  {
    toaster("Name of employee is required",5);
  }
  else
  {
    if(father_name==="")
    {
      toaster("Please enter father name",5);
    }
    else
    {
      if(address==="")
      {
        toaster("Please enter address",5);
      }
      else
      {
        if(doj==="")
        {
          toaster("Please select date of joining",5);
        }
        else
        {
          if(shift==="")
          {
            toaster("Please select shift",5);
          }
          else
          {
            $.ajax({
              type: "POST",
              url: "ajax/add/employees.php",
              data: {full_name:full_name, father_name:father_name, cell_no:cell_no, 
              email:email, cnic_no:cnic_no, address:address, shift:shift, doj:doj},
              success: function (response) 
              {
                if(response==="Employee has been added")
                {
                  let currentPage = $("#employeesTable").DataTable().page();
                  $("#employeesTable").DataTable().destroy();
                  fetchEmployees();
                  toaster(response,5);
                  $("#addEmployeeForm input,textarea").val("");
                  $("#addEmployeeModal").modal("hide");

                  // Set the current page after reinitializing DataTable
                  $("#employeesTable").DataTable().one('draw', function () {
                    $("#employeesTable").DataTable().page(currentPage).draw('page');
                  }); 
                }
                else if(response==="Employee has not been added")
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
    }
  }
});

// Edit Employee
function editEmployee(employee_edit_id)
{
  $("#employee_hidden_id").val(employee_edit_id);
  $.ajax({
    type: "POST",
    url: "ajax/edit/employees.php",
    data: {employee_edit_id:employee_edit_id},
    success: function (response) 
    {
      var emp = JSON.parse(response);
      $("#edit_full_name").val(emp.full_name);
      $("#edit_father_name").val(emp.father_name);
      $("#edit_cell_no").val(emp.cell_no);
      $("#edit_email").val(emp.email);
      $("#edit_cnic_no").val(emp.cnic_no);
      $("#edit_address").val(emp.address);
      $("#edit_shift").val(emp.shift);
      $("#status").val(emp.status);
      $("#edit_doj").val(emp.date_of_joining);
      $("#first_sale").val(emp.first_sale);
      $("#editEmployeeModal").modal("show");
    }
  });
}

// Update Employee
$("#updateEmployeebtn-spec").click(function(){
  let full_name = $("#edit_full_name").val();
  let father_name = $("#edit_father_name").val();
  let cell_no = $("#edit_cell_no").val();
  let email = $("#edit_email").val();
  let cnic_no = $("#edit_cnic_no").val();
  let address = $("#edit_address").val();
  let shift = $("#edit_shift").val();
  let doj = $("#edit_doj").val();
  let first_sale = $("#first_sale").val();
  let status = $("#status").val();
  let employee_edit_id = $("#employee_hidden_id").val();
  if(full_name==="")
  {
    toaster("Name is required",5);
  }
  else
  {
    if(father_name==="")
    {
      toaster("Please enter father name",5);
    }
    else
    {
      if(address==="")
      {
        toaster("Please enter address",5);
      }
      else
      {
        if(shift==="")
        {
          toaster("Please select shift",5);
        }
        else
        {
          if(doj==="")
          {
            toaster("Please select date of joining",5);
          }
          else
          {
            $.ajax({
              type: "POST",
              url: "ajax/update/employees.php",
              data: { employee_edit_id:employee_edit_id, full_name:full_name, father_name:father_name,
              cell_no:cell_no, email:email, cnic_no:cnic_no, address:address, shift:shift, doj:doj,
              first_sale:first_sale, status:status },
              success: function (response) 
              {
                if(response==="Employee record has been updated")
                {
                  let currentPage = $("#employeesTable").DataTable().page();
                  $("#employeesTable").DataTable().destroy();
                  fetchEmployees();
                  toaster(response,5);
                  $("#editEmployeeModal").modal("hide");

                  // Set the current page after reinitializing DataTable
                  $("#employeesTable").DataTable().one('draw', function () {
                    $("#employeesTable").DataTable().page(currentPage).draw('page');
                  }); 
                }
                else if(response==="Employee record has not been updated")
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
    }
  }
});

// Delete Employee
function deleteEmployee(employee_delete_id)
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
          url: "ajax/delete/employees.php",
          data: {employee_delete_id:employee_delete_id},
          success:function(response) 
          {
            if(response==="Employee record has been deleted")
            {
              let currentPage = $("#employeesTable").DataTable().page();
              $("#employeesTable").DataTable().destroy();
              fetchEmployees();
              toaster(response,5);

              // Set the current page after reinitializing DataTable
              $("#employeesTable").DataTable().one('draw', function () {
                $("#employeesTable").DataTable().page(currentPage).draw('page');
              }); 
            }
            else if(response==="Employee record has not been deleted")
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

// Make Admin
function makeAdmin(id)
{
  $("#employee_hidden_id2").val(id);
  $.ajax({
    type: "POST",
    url: "ajax/fetch/makeAdmin.php",
    data: {id:id},
    success: function (response) 
    {
      let admin = JSON.parse(response);
      $("#name2").val(admin.full_name);
      $("#email2").val(admin.email);
    }
  });
  $("#makeAdminModal").modal("show");
}

// Give Access
$("#giveAccessbtn-spec").click(function(){
  let id = $("#employee_hidden_id2").val();
  let name = $("#name2").val();
  let username = $("#username").val();
  let email = $("#email2").val();
  let password = $("#password").val();
  let role = $("#role").val();
  if(username==="")
  {
    toaster("Please Enter Username");
  }
  else
  {
    if(password==="")
    {
      toaster("Please Enter Password");
    }
    else
    {
      if(role==="")
      {
        toaster("Please assign role");
      }
      else
      {
        if(password.length<7)
        {
          toaster("Too short! The password must be at least 7 characters long",5);
        }
        else
        {
          $.ajax({
            type: "POST",
            url: "ajax/add/giveAccess.php",
            data: {id:id, name:name, username:username, email:email, password:password, role:role},
            success: function (response) 
            {
              if(response==="Access has already been given to this user")
              {
                swal.fire({
                  title:"CAN'T GIVE ACCESS",
                  text:"Access has already been given to this user",
                  icon:"info",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: 'blue',
                  showCloseButton: true,
                  allowOutsideClick: false
                });
              }
              else if(response==="This username has already taken by another user. Please try different username.")
              {
                swal.fire({
                  title:"USERNAME DUPLICATION",
                  text:"This username has already taken by another user. Please try different username.",
                  icon:"error",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: 'blue',
                  showCloseButton: true,
                  allowOutsideClick: false
                });
              }
              else if(response==="This email has already taken by another user. Please try different email.")
              {
                swal.fire({
                  title:"EMAIL DUPLICATION",
                  text:"This email has already taken by another user. Please try different email.",
                  icon:"error",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: 'blue',
                  showCloseButton: true,
                  allowOutsideClick: false
                });
              }
              else if(response==="Access has been given")
              {
                let currentPage = $("#employeesTable").DataTable().page();
                $("#employeesTable").DataTable().destroy();
                fetchEmployees();
                swal.fire({
                  title:"ACCESS GRANTED",
                  text:"Access has been given!",
                  icon:"success",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: 'blue',
                  showCloseButton: true,
                  allowOutsideClick: false,
                  timer:2000
                });
                $("#username, password").val("");
                $("#makeAdminModal").modal("hide");

                // Set the current page after reinitializing DataTable
                $("#employeesTable").DataTable().one('draw', function () {
                  $("#employeesTable").DataTable().page(currentPage).draw('page');
                }); 
              }
              else if(response==="Access has not been given")
              {
                swal.fire({
                  title:"CAN'T GIVE ACCESS",
                  text:"Access has not been given to this user",
                  icon:"error",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: 'blue',
                  showCloseButton: true,
                  allowOutsideClick: false
                });
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

// Assign Account
function assignAccount(id)
{
  $("#employee_hidden_id3").val(id);
  $.ajax({
    type: "POST",
    url: "ajax/edit/assignAccount.php",
    data: {id:id},
    success: function (response) 
    {
      let emp = JSON.parse(response);
      $("#account1").val(emp.account1);
      $("#account2").val(emp.account2);
    }
  });
  $("#assignAccountModal").modal("show");
}

$("#assignAccountbtn-spec").click(function(){
  let id = $("#employee_hidden_id3").val();
  let account1 = $("#account1").val();
  let account2 = $("#account2").val();

  $.ajax({
    type: "POST",
    url: "ajax/update/assignAccount.php",
    data: {id:id, account1:account1, account2:account2},
    success: function (response) 
    {
      if(response==="Accounts has been unassigned")
      {
        let currentPage = $("#employeesTable").DataTable().page();
        $("#employeesTable").DataTable().destroy();
        fetchEmployees();
        toaster(response,5);
        $("#assignAccountModal").modal("hide");

        // Set the current page after reinitializing DataTable
        $("#employeesTable").DataTable().one('draw', function () {
          $("#employeesTable").DataTable().page(currentPage).draw('page');
        }); 
      }
      else if(response==="Account has been assigned")
      {
        let currentPage = $("#employeesTable").DataTable().page();
        $("#employeesTable").DataTable().destroy();
        fetchEmployees();
        toaster(response,5);
        $("#assignAccountModal").modal("hide");

        // Set the current page after reinitializing DataTable
        $("#employeesTable").DataTable().one('draw', function () {
          $("#employeesTable").DataTable().page(currentPage).draw('page');
        }); 
      }
      else if(response==="Account has not been assigned")
      {
        toaster(response,5);
      }
      else
      {
        toaster("An unknown error has occured",5);
      }
    }
  });
});
    </script> 
</body>
</html>