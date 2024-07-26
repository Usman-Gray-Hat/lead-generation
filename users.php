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
    <title>Users</title>
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
        <h1 class="text-center t-bold py-3 awfawf mt-2 mb-2">ADMINS AND USERS</h1>
    </section>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-3">
                    <div class="card-header d-flex justify-content-end">
                                        <!-- Add User Modal -->
                                <button type="button" class="btn-spec primary" 
                                data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus"></i> Add New</button>
                    </div>
                    <div class="card-body">
                        <table class='table text-center' id='usersTable'>
                            <thead>
                                <tr>
                                    <th>USER HIDDEN ID</th>
                                    <th>SR.NO</th>
                                    <th>FULL NAME</th>
                                    <th>USERNAME</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>TYPE</th>
                                    <th>OPERATIONS</th>
                                </tr> 
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">ADD USER</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addUserForm" autocomplete="off">
                        <div class="form-group">
                            <label for="name">Full Name (Required)</label>
                            <input type="text" id="name" placeholder="Enter Full Name" class="form-control mb-1">
                        </div>
                        <div class="form-group">
                            <label for="username">Username (Required)</label>
                            <input type="text" id="username" placeholder="Enter Username" class="form-control">
                        </div>   
                        <div class="form-group">
                            <label for="email">Email (Optional)</label>
                            <input type="text" id="email" placeholder="Enter Email" class="form-control">
                        </div>                         
                        <div class="form-group">
                            <label for="password">Password (Required)</label>
                            <input type="password" id="password" placeholder="Enter Password" class="form-control">
                        </div>  
                        <div class="form-group">
                            <label for="role">Role (Required)</label>
                            <select id="role" class="form-control">
                                <option value="">Assign Role</option>
                                <option value="Owner">Owner</option>
                                <option value="Manager">Manager</option>
                                <option value="Closer">Closer</option>
                                <option value="Lead Generation">Lead Generation</option>
                            </select>
                        </div>                         
                        <div class="form-group">
                            <label for="type">Type (Required)</label>
                            <select id="type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                        </div>                                              
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-spec primary" id="addUserbtn-spec">Add</button>
                    <button type="button" class="btn-spec btn-spec-danger-spec" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">EDIT USER DETAILS</h2>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editUserForm">
                        <div class="form-group">
                            <label for="edit_name">Full Name (Required)</label>
                            <input type="text" id="edit_name" placeholder="Enter Full Name" class="form-control mb-1">
                        </div>
                        <div class="form-group">
                            <label for="edit_username">Username (Required)</label>
                            <input type="text" id="edit_username" placeholder="Enter Username" class="form-control">
                        </div>   
                        <div class="form-group">
                            <label for="edit_email">Email (Optional)</label>
                            <input type="text" id="edit_email" placeholder="Enter Email" class="form-control">
                        </div>   
                        <div class="form-group">
                            <label for="edit_role">Role (Required)</label>
                            <select id="edit_role" class="form-control">
                                <option value="">Assign Role</option>
                                <option value="Owner">Owner</option>
                                <option value="Manager">Manager</option>
                                <option value="Closer">Closer</option>
                                <option value="Lead Generation">Lead Generation</option>
                            </select>
                        </div>                                                
                        <div class="form-group">
                            <label for="edit_type">Type (Required)</label>
                            <select id="edit_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                        </div>                            
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="user_hidden_id"> <!-- User Hidden ID -->
                    <button type="button" class="btn-spec primary" id="updateUserbtn-spec">Update</button>
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

        // Fetch Users
        function fetchUsers()
        {
            $("#usersTable").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                "type": "POST",
                "url": "ajax/fetch/user.php",
                },
                "columns": [
                { data: 'id', className:'d-none'}, // User Hidden ID
                { data: 'sr_no'},
                { data: 'name'},
                { data: 'username'},
                { data: 'email'},
                { data: 'role'},
                { data: 'type'},
                {
                    "render":function(data,type,full,meta)
                    {
                        var buttons = '';
                        // Edit
                        buttons += `<button type='button' class='btn-spec primary me-1' title='Edit record'
                        onclick='editUser(${full.id})'><i class="fas fa-edit"></i> Edit</button>`;
                        // Delete
                        buttons += `<button type='button' class='btn-spec btn-spec-danger-spec me-1' title='Delete record'
                        onclick='deleteUser(${full.id})'><i class="fas fa-trash-alt"></i> Delete</button>`;
                        return buttons;
                    }, className:'operations-column'
                }
                ],
            });
        }

        // Load Users Table On Page Initialization
        $(document).ready(function () {
            fetchUsers();
        });      
        
        // Add User
        $("#addUserbtn-spec").click(function(){
            let name = $("#name").val();
            let username = $("#username").val();
            let email = $("#email").val();
            let password = $("#password").val();
            let type = $("#type").val();
            let role = $("#role").val();
            if(name==="")
            {
                toaster("Please enter full name",5);
            }
            else
            {
                if(username==="")
                {
                    toaster("Please enter username",5);
                }
                else
                {
                    if(password==="")
                    {
                        toaster("Please enter password",5);
                    }
                    else
                    {
                        if(role==="")
                        {
                            toaster("Please assign role",5);
                        }
                        else
                        {
                            if(type==="")
                            {
                                toaster("Please select type",5);
                            }
                            else
                            {
                                if(password.length<7)
                                {
                                toaster("Too short! The password must be at least 7 characters long",5);
                                }
                                else
                                {
                                    if(role==="Owner" && type==2)
                                    {
                                        toaster("Owner type cannot be assigned as User. Please select Admin type for Owner",8);
                                    }
                                    else
                                    {
                                        $.ajax({
                                            type: "POST",
                                            url: "ajax/add/user.php",
                                            data: {name:name, username:username, email:email, 
                                            password:password, type:type, role:role},
                                            success: function (response) 
                                            {
                                                if(response==="This username has already taken. Please try different username")
                                                {
                                                    toaster(response,5);
                                                }
                                                else if(response==="This email has already taken by another user. Please try different email")
                                                {
                                                    toaster(response,5);
                                                }
                                                else if(response==="User has been added")
                                                {
                                                    let currentPage = $("#usersTable").DataTable().page();
                                                    $("#usersTable").DataTable().destroy();
                                                    fetchUsers();
                                                    toaster(response,5);
                                                    $("#name,#username,#email,#password,#role,#type").val("");
                                                    $("#addUserModal").modal("hide");

                                                    // Set the current page after reinitializing DataTable
                                                    $("#usersTable").DataTable().one('draw', function () {
                                                        $("#usersTable").DataTable().page(currentPage).draw('page');
                                                    }); 
                                                }
                                                else if(response==="User has not been added")
                                                {
                                                    toaster(response,5);
                                                }
                                                else
                                                {
                                                    toaster("An unkown error has occured",5);
                                                }
                                            }
                                        });                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });

        // Edit User
        function editUser(id)
        {
            $("#user_hidden_id").val(id);
            $.ajax({
                type: "POST",
                url: "ajax/edit/user.php",
                data: {id:id},
                success: function (response) 
                {
                    let users = JSON.parse(response);
                    $("#edit_name").val(users.name);
                    $("#edit_username").val(users.username);
                    $("#edit_email").val(users.email);
                    $("#edit_type").val(users.type);
                    $("#edit_role").val(users.role);
                }
            });
            $("#editUserModal").modal("show");
        }

        // Update User
        $("#updateUserbtn-spec").click(function(){
            let id = $("#user_hidden_id").val();
            let name = $("#edit_name").val();
            let username = $("#edit_username").val();
            let email = $("#edit_email").val();
            let type = $("#edit_type").val();
            let role = $("#edit_role").val();
            if(name==="")
            {
                toaster("Please enter name",5);
            }
            else
            {
                if(username==="")
                {
                    toaster("Please enter username",5);
                }
                else
                {
                    if(role==="")
                    {
                        
                        toaster("Please assign role",5);
                    }
                    else
                    {
                        if(type==="")
                        {
                            toaster("Please select type",5);
                        }
                        else
                        {
                            if(role==="Owner" && type==2)
                            {
                                toaster("Owner type cannot be assigned as User. Please select Admin type for Owner",8);
                            }
                            else
                            {
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/update/user.php",
                                    data: {id:id, name:name, username:username,
                                    email:email, type:type, role:role},
                                    success: function (response) 
                                    {
                                        if(response==="This username has already taken. Please try different username")
                                        {
                                            toaster(response,5);
                                        }
                                        else if(response==="This email has already taken by another user. Please try different email")
                                        {
                                            toaster(response,5);
                                        }
                                        else if(response==="User has been updated")
                                        {
                                            let currentPage = $("#usersTable").DataTable().page();
                                            $("#usersTable").DataTable().destroy();
                                            fetchUsers();
                                            toaster(response,5);
                                            $("#editUserModal").modal("hide");

                                            // Set the current page after reinitializing DataTable
                                            $("#usersTable").DataTable().one('draw', function () {
                                                $("#usersTable").DataTable().page(currentPage).draw('page');
                                            });                                         
                                        }
                                        else if(response==="User has not been updated")
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

        // Delete User
        function deleteUser(id)
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
                        url: "ajax/delete/user.php",
                        data: {id:id},
                        success: function (response) 
                        {
                            if(response==="User has been deleted")
                            {
                                let currentPage = $("#usersTable").DataTable().page();
                                $("#usersTable").DataTable().destroy();
                                fetchUsers();
                                toaster(response,5);

                                // Set the current page after reinitializing DataTable
                                $("#usersTable").DataTable().one('draw', function () {
                                    $("#usersTable").DataTable().page(currentPage).draw('page');
                                });                                 
                            }
                            else if(response==="User has not been deleted")
                            {
                                toaster(response,5);
                            }
                            else
                            {
                                toaster("An unkown error has occured",5);
                            }
                        }
                    });
                }
            }));
        }
    </script>
</body>
</html>