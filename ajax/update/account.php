<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['accountName']) && isset($_POST['gmail']))
{
    $id = $_POST['id'];
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

    $query = "UPDATE account_credentials SET name='$accountName', gmail='$gmail', gmail_password='$gmailPassword',
    twitter='$twitter', twitter_password='$twitterPassword',
    instagram='$instagram', instagram_password='$instagramPassword',
    discord='$discord', discord_password='$discordPassword',
    twitch='$twitch', twitch_password='$twitchPassword' WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Account has been updated";
    }
    else
    {
        echo "Account has not been updated";
    }
}
?>