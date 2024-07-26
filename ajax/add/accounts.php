<?php
include("../../dbConnect.php");
if(isset($_POST['accountName']) && isset($_POST['gmail']) && isset($_POST['gmailPassword']))
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $accountName = mysqli_real_escape_string($conn, $_POST['accountName']);
    $gmail = mysqli_real_escape_string($conn, $_POST['gmail']);
    $gmailPassword = mysqli_real_escape_string($conn, $_POST['gmailPassword']);
    $twitter = mysqli_real_escape_string($conn, $_POST['twitter']);
    $twitterPassword = mysqli_real_escape_string($conn, $_POST['twitterPassword']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $instagramPassword = mysqli_real_escape_string($conn, $_POST['instagramPassword']);
    $discord = mysqli_real_escape_string($conn, $_POST['discord']);
    $discordPassword = mysqli_real_escape_string($conn, $_POST['discordPassword']);
    $twitch = mysqli_real_escape_string($conn, $_POST['twitch']);
    $twitchPassword = mysqli_real_escape_string($conn, $_POST['twitchPassword']);

    $query = "INSERT INTO account_credentials (name,gmail,gmail_password,twitter,twitter_password,instagram,instagram_password,
    discord,discord_password,twitch,twitch_password) VALUES
    ('$accountName','$gmail','$gmailPassword','$twitter','$twitterPassword','$instagram','$instagramPassword',
    '$discord','$discordPassword','$twitch','$twitchPassword')";
    $exec = mysqli_query($conn,$query);
    if($exec===true)
    {
        echo "Account has been added successfully";
    }
    else
    {
        echo "Account has not been added";
    }
}
?>