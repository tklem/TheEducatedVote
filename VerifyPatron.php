<?php

include 'DatabaseConnection.php';

getInfo();
function getInfo()
{
    if (empty($_POST['loginEntry']))
        $login = "Error: Empty value slipped through";
    else
        $login = $_POST['loginEntry'];

    if (empty($_POST['passwordEntry']))
        $pass = "Error: Empty value slipped through";
    else
        $pass = $_POST['passwordEntry'];

    if (empty($_POST['keepLoggedIn']))
        $keepLogged = "Error: Empty value slipped through";
    else
        $keepLogged = $_POST['keepLoggedIn'];
    
    verify($login, $pass, $keepLogged);
}

//@@@@@needs a hashed lookup function 
function verify($login, $pass, $keepLogged)
{
    $passCheck = '';
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $emailPrep = "SELECT password FROM theeducatedvote.patrons_basic_info WHERE email = ?";
    $usernamePrep = "SELECT password FROM theeducatedvote.patrons_basic_info WHERE login = ?";
    if($emailPrep = $conn->prepare($emailPrep))
    {
        $emailPrep->bind_param('s', $login);
        $emailPrep->execute();
        $emailPrep->bind_result($passCheck);
        $emailPrep->fetch();
        $emailPrep->close();
        if($passCheck == '')
        {
            if($usernamePrep = $conn->prepare($usernamePrep))
            {
                $usernamePrep->bind_param('s', $login);
                $usernamePrep->execute();
                $usernamePrep->bind_result($passCheck);
                $usernamePrep->fetch();
                $usernamePrep->close();
                debug_to_console("ending username");
            }
        }
        debug_to_console("ending email");
    }

    if($passCheck == $pass)
    {
        echo $passCheck ." = ". $pass .":   Patron can login";
        updateKeepLogged($keepLogged);
    }
    else
    {
        echo $passCheck ." = ". $pass .":   Wrong username/password";
    }
}

function updateKeepLogged($keepLogged)
{
}

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

function var_dump_pre($mixed = null)
{
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>';
    return null;
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
welcome back
</body>
</html>
