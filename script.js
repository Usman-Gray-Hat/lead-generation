// Toaster function need to define seconds to be live and the text to be shown. 
function toaster(text, duration)
{
    Toastify({
      text: text,
      duration: duration*1000,
      newWindow: true,
      close: true,
      gravity: "top",
      position: "center",
      pauseOnHover: true,
      style: {
        "background": "rgb(68 67 67 / 84%)",
        "backdrop-filter": "saturate(180%) blur(20px)",
        "-webkit-backdrop-filter": "saturate(180%) blur(20px)",
        "border-radius": "20px",
      }, 
      onClick: function(){} 
    }).showToast();
}

// Show Login Password
$("#eyeIcon").click(function(){
  let password = $("#password").attr("type");
  if(password==="password")
  {
    $("#eyeIcon").removeClass("fa-solid fa-eye-slash");
    $("#eyeIcon").addClass("fa fa-eye");
    $("#password").attr("type","text");
  }
  else
  {
    $("#eyeIcon").removeClass("fa fa-eye");
    $("#eyeIcon").addClass("fa-solid fa-eye-slash");
    $("#password").attr("type","password");
  }
});

// Show Current Password
$("#eyeIcon1").click(function(){
  let current_password = $("#current_password").attr("type");
  if(current_password==="password")
  {
    $("#eyeIcon1").removeClass("fa-solid fa-eye-slash");
    $("#eyeIcon1").addClass("fa fa-eye");
    $("#current_password").attr("type","text");
  }
  else
  {
    $("#eyeIcon1").removeClass("fa fa-eye");
    $("#eyeIcon1").addClass("fa-solid fa-eye-slash");
    $("#current_password").attr("type","password");
  }
});

// Show New Password
$("#eyeIcon2").click(function(){
  let new_password = $("#new_password").attr("type");
  if(new_password==="password")
  {
    $("#eyeIcon2").removeClass("fa-solid fa-eye-slash");
    $("#eyeIcon2").addClass("fa fa-eye");
    $("#new_password").attr("type","text");
  }
  else
  {
    $("#eyeIcon2").removeClass("fa fa-eye");
    $("#eyeIcon2").addClass("fa-solid fa-eye-slash");
    $("#new_password").attr("type","password");
  }
});

// Show Confirm Password
$("#eyeIcon3").click(function(){
  let confirm_password = $("#confirm_password").attr("type");
  if(confirm_password==="password")
  {
    $("#eyeIcon3").removeClass("fa-solid fa-eye-slash");
    $("#eyeIcon3").addClass("fa fa-eye");
    $("#confirm_password").attr("type","text");
  }
  else
  {
    $("#eyeIcon3").removeClass("fa fa-eye");
    $("#eyeIcon3").addClass("fa-solid fa-eye-slash");
    $("#confirm_password").attr("type","password");
  }
});

// Login
$("#loginbtn-spec").click(function(){
  let username = $("#username").val();
  let password = $("#password").val();
  if(username==='' || password==="")
  {
    toaster("All fields are required",5);
  }
  else
  {
    $.ajax({
      type: "POST",
      url: "ajax/login.php",
      data: {username:username, password:password},
      success: function (response) 
      {
        if(response==="Login Successful")
        {
            window.location.href='home.php';
        }
        else if(response==="Invalid username or password")
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
});

// Logout
$(".logout").click(function(){
  swal.fire({
    title: "CONFIRM?",
    text: "Are you sure you want to logout?",
    icon: 'question',
    showCancelButton: true,
    cancelButtonText: 'No',
    cancelButtonColor: 'red',
    confirmButtonText: 'Yes',
    confirmButtonColor: 'blue',
    showCloseButton: true,
    allowOutsideClick: false
    }).then((result => {
      if(result.isConfirmed)
      {
        window.location.href="logout.php";
      }
  }));  
});

// Fetch Accounts
function fetchAccounts()
{
  let rd = "rd";
  $.ajax({
    type: "POST",
    url: "ajax/fetch/accounts.php",
    data: {rd:rd},
    success: function (response) 
    {
      $("#accountsTable").html(response);
    }
  });
}

// Load Accounts Table On Page Initialization
$(document).ready(function () {
  fetchAccounts();
});

// Add New Account
$("#addAccountbtn-spec").click(function(){
  let accountName = $("#accountName").val();
  let gmail = $("#gmail").val();
  let gmailPassword = $("#gmailPassword").val();
  let twitter = $("#twitter").val();
  let twitterPassword = $("#twitterPassword").val();
  let instagram = $("#instagram").val();
  let instagramPassword = $("#instagramPassword").val();
  let discord = $("#discord").val();
  let discordPassword = $("#discordPassword").val();
  let twitch = $("#twitch").val();
  let twitchPassword = $("#twitchPassword").val();

  if(accountName==="")
  {
    toaster("Account name is required",5);
  }
  else
  {
    if(gmail==="")
    {
      toaster("Gmail is required",5);
    }
    else
    {
      if(gmailPassword==="")
      {
        toaster("Gmail password is required",5);
      }
      else
      {
        $.ajax({
          type: "POST",
          url: "ajax/add/accounts.php",
          data: {accountName:accountName, gmail:gmail, gmailPassword:gmailPassword, twitter:twitter, twitterPassword:twitterPassword,
          instagram:instagram, instagramPassword:instagramPassword, discord:discord, discordPassword:discordPassword,
          twitch:twitch, twitchPassword:twitchPassword},
          success: function (response) 
          {
            if(response==="Account has been added successfully")
            {
              fetchAccounts();
              toaster(response,5);
              $("#addAccountForm input").val("");
              $("#addAccountModal").modal("hide");
            }
            else if(response==="Account has not been added")
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
});

// Edit Account
function editAccount(id)
{
  $("#hidden_account_id").val(id);
  $.ajax({
    type: "POST",
    url: "ajax/edit/account.php",
    data: {id:id},
    success: function (response) 
    {
      var accounts = JSON.parse(response);
      // Account Name
      $("#edit_accountName").val(accounts.name);
      // Gmail
      $("#edit_gmail").val(accounts.gmail);
      $("#edit_gmailPassword").val(accounts.gmail_password);
      // Twitter
      $("#edit_twitter").val(accounts.twitter);
      $("#edit_twitterPassword").val(accounts.twitter_password);
      // Instagram
      $("#edit_instagram").val(accounts.instagram);
      $("#edit_instagramPassword").val(accounts.instagram_password);
      // Discord
      $("#edit_discord").val(accounts.discord);
      $("#edit_discordPassword").val(accounts.discord_password);
      // Twitch
      $("#edit_twitch").val(accounts.twitch);
      $("#edit_twitchPassword").val(accounts.twitch_password);
    }
  });
  $("#editAccountModal").modal("show");
}

// Update Account
$("#updateAccountbtn-spec").click(function(){

  let id = $("#hidden_account_id").val();
  let accountName = $("#edit_accountName").val();

  let gmail = $("#edit_gmail").val();
  let gmailPassword = $("#edit_gmailPassword").val();

  let twitter = $("#edit_twitter").val();
  let twitterPassword = $("#edit_twitterPassword").val();

  let instagram = $("#edit_instagram").val();
  let instagramPassword = $("#edit_instagramPassword").val();

  let discord = $("#edit_discord").val();
  let discordPassword = $("#edit_discordPassword").val();

  let twitch = $("#edit_twitch").val();
  let twitchPassword = $("#edit_twitchPassword").val();

  if(accountName==="")
  {
    toaster("Account name is required",5);
  }
  else
  {
    if(gmail==="")
    {
      toaster("Gmail is required",5);
    }
    else
    {
      if(gmailPassword==="")
      {
        toaster("Gmail password is required",5);
      }
      else
      {
        $.ajax({
          type: "POST",
          url: "ajax/update/account.php",
          data: {id:id, accountName:accountName, gmail:gmail, gmailPassword:gmailPassword, twitter:twitter,
          twitterPassword:twitterPassword, instagram:instagram, instagramPassword:instagramPassword,
          discord:discord, discordPassword:discordPassword, twitch:twitch, twitchPassword:twitchPassword},
          success: function (response) 
          {
            if(response==="Account has been updated")
            {
              fetchAccounts();
              toaster(response,5);
              $("#editAccountModal").modal("hide");
            }
            else if(response==="Account has not been updated")
            {
              toaster(response,5);
            }
            else
            {
              toaster(response,5);
              // toaster("An unknown error has occured",5);
            }
          }
        });
      }
    }
  }
});

// Delete Account
function deleteAccount(id)
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
                url: "ajax/delete/account.php",
                data: {id:id},
                success: function (response) 
                {
                    if(response==="Account has been deleted")
                    {
                        fetchAccounts();
                        toaster(response,5);
                    }
                    else if(response==="Account has not been deleted")
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

// Copied To Clipboard
$(document).on('click', '.copied', function(){
  var txt = $(this).text();
  txt.trim();
  var tempInput = $("<input>");
  $("body").append(tempInput);
  tempInput.val(txt).select();
  document.execCommand("copy");
  tempInput.remove();
  $(".copied-popup").fadeIn().delay(1500).fadeOut();
});

$(document).on('mouseenter','.copied',function(){
  $(this).attr("title","Click to copy");
});

// Gif Loader
setTimeout(function() {
    $("#loader-container").css("display","none");
}, 200); 

$(document).ready(function () {
  // Show the loader while waiting for the GIF loader to load
document.getElementById('loader-container').style.display = 'flex';
});