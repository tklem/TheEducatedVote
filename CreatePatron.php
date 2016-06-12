<?php
include 'DatabaseConnection.php';



getInfo();
function getInfo()
{
    if (empty($_POST['name']))
        $name = "Error: Empty value slipped through";
    else
        $name = $_POST['name'];

    if (empty($_POST['email']))
        $email = "Error: Empty value slipped through";
    else
        $email = $_POST['email'];

    if (empty($_POST['username']))
        $username = "Error: Empty value slipped through";
    else
        $username = $_POST['username'];

    if (empty($_POST['password']))
        $password = "Error: Empty value slipped through";
    else
        $password = $_POST['password'];

    if (empty($_POST['passwordConfirm'])) //@@@@@double-check password here
        $passwordConfirm = "Error: Empty value slipped through";
    else
        $passwordConfirm = $_POST['passwordConfirm'];

    if (empty($_POST['addressLineOne']))
        $addressLineOne = "Error: Empty value slipped through";
    else
        $addressLineOne = $_POST['addressLineOne'];

    if (empty($_POST['addressLineTwo']))
        $addressLineTwo = "";
    else
        $addressLineTwo = $_POST['addressLineTwo'];

    if (empty($_POST['city']))
        $city = "Error: Empty value slipped through";
    else
        $city = $_POST['city'];

    if (empty($_POST['state']))
        $state = "Error: Empty value slipped through";
    else
        $state = $_POST['state'];

    if (empty($_POST['zipcode']))
        $zipcode = "Error: Empty value slipped through";
    else
        $zipcode = $_POST['zipcode'];


    if(isset($_POST['submitNewPatron'])) //should always be true, since that's how we get here
    {
        //debug_to_console("going to the database part");
        moveInfoToDatabase($name, $username, $password, $addressLineOne, $addressLineTwo, $city, $state, $zipcode, $email);
    }
}

function moveInfoToDatabase($name, $username, $password, $addressLineOne, $addressLineTwo, $city, $state, $zipcode, $email)
{
    date_default_timezone_set('UTC'); //set timestamp to UTC @@@@@ should probably use american time stamp
    $dateCreated = date("Y-m-d H:i:s"); //timestamp in datetime format
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $newPatronPrep = "INSERT INTO theeducatedvote.patrons_basic_info (name, login, password, address_line_one, address_line_two, city, state, zipcode, email, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if($newPatronPrep = $conn->prepare($newPatronPrep))
    {
        $newPatronPrep->bind_param('ssssssssss', $name, $username, $password, $addressLineOne, $addressLineTwo, $city, $state, $zipcode, $email, $dateCreated);
        $newPatronPrep->execute();
        $newPatronPrep->close(); //close the prepare/bind/execute
    }
}
?>

<!doctype html>
<html>
<head>
    <title>Welcome to The Educated Vote!</title>
    <link rel="stylesheet" type="text/css" href="Styles/TheEducatedVoteUniversalStyle.css">
    <!--<link rel="stylesheet" type="text/css" href="Styles/submitInfoStyle.sass"> <!--@@@@@ supposedly this will not work, and I'll need to convert my .sass docs to .css ones. lame.
    <script src="Scripts/jquery-1.11.2.js"></script>
    <script src="Scripts/js.cookie.js"></script>
    <script src="Scripts/SubmitNewsScript.js"></script>
    <script src="Scripts/TheEducatedVoteUniversalScript.js"></script> -->
    <link rel="shortcut icon" href="Photos/DinosaurPoliticalParty.jpg">

    <meta charset="UTF-8">
    <meta name="description" content="New User Page, The Educated Vote"> <!-- description of page -->
    <meta name="author" content="Kevin Gibbons"> <!-- who wrote it -->
</head>
<body>
hello world
</body>
</html>
