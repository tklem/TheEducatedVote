<?php
include 'TheEducatedVoteConstants.php';
include 'DatabaseConnection.php';

getPostInfo();

//gets the info from $_POST that we need to put in the database
function getPostInfo()
{
    $i = 1; //identifier in case the patron submitted more than one set of info
    $topicKey = "topic" . $i; $contentKey = "contentTextarea" . $i; $sourceKey = "sourceTextarea" . $i; //full identifiers for HTML elements
    $topicArray = array();
    $contentArray = array(); //these three are parallel arrays
    $sourceArray = array();

    //@@@@@code in character limits here to make sure we don't exceed what the database can handle
    while(isset($_POST[$topicKey]))
    {
        //debug_to_console("In the while round " . $i);
        if (empty($_POST[$topicKey]))
            $topicArray[$topicKey] = "Error: Empty value slipped through"; //this shouldn't happen since we 'required' the fields in HTML
        else
            $topicArray[$topicKey] = $_POST[$topicKey];

        if (empty($_POST[$contentKey]))
            $contentArray[$contentKey] = "Error: Empty value slipped through";
        else
            $contentArray[$contentKey] = $_POST[$contentKey];
        //debug_to_console("content array " . $contentArray[$contentKey]);
        //debug_to_console("content post " . $_POST[$contentKey]);
        if (empty($_POST[$sourceKey]))
            $sourceArray[$sourceKey] = "Error: Empty value slipped through";
        else
            $sourceArray[$sourceKey] = $_POST[$sourceKey];
        //debug_to_console("sources array " . $sourceArray[$sourceKey]);
        //debug_to_console("sources post " . $_POST[$sourceKey]);
        $i++;
        $topicKey = "topic" . $i; $contentKey = "contentTextarea" . $i; $sourceKey = "sourceTextarea" . $i;
    }

    if (isset($_POST['submitInfo'])) //should always be true, since that's how we get here
    {
        //debug_to_console("going to the database part");
        moveToDatabase(array_values($topicArray), array_values($contentArray), array_values($sourceArray));
    }
}

//dumps the submitted info into a database where the Info Admins can check it out later
function moveToDatabase($topicArray, $contentArray, $sourceArray)
{
    date_default_timezone_set('UTC'); //set timestamp to UTC @@@@@ should probably use american time stamp
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $submittedBy = "Kevin"; //@@@@@ must be easier way to get this (session cookie? localstorage?)
    $submittedWhen = date("Y-m-d H:i:s"); //timestamp in datetime format
    $forPage = "Illini Drumline"; //@@@@@ probably will set cookie whenever a user clicks 'submit info' so we know where they're coming from
    $topic = ''; $content = ''; $sources = '';


    $submittedPrep = "INSERT INTO theeducatedvote.submitted_info (submitted_by, submitted_when, topic, content, sources, for_page) VALUES (?, ?, ?, ?, ?, ?)";
    if($submittedPrep = $conn->prepare($submittedPrep))
    {
        $submittedPrep->bind_param('ssssss', $submittedBy, $submittedWhen, $topic, $content, $sources, $forPage);
        for($i = 0; $i < count($topicArray); $i++) //loop through the parallel arrays
        {
            $topic = $topicArray[$i];
            $content = $contentArray[$i];
            $sources = $sourceArray[$i];
            $submittedPrep->execute();
        }
        $submittedPrep->close(); //close the prepare/bind/execute
    }
    redirectToPage($forPage);
}

function redirectToPage($page_link)
{
    header($page_link);
    echo 'Thanks for the info! You should be sent back to your page in a couple seconds. If you\'re still reading this, <a href="'. $page_link .'">click here</a> to be redirect immediately.';
}


function simpleSterilize($data) //checks to make sure input isn't trying to hack
{
    $data = trim($data); //takes out extraneous spaces
    $data = stripslashes($data); //takes out backslashes and replaces them with a similar-to-ASCII equivalent; backslashes usually signify someone is trying to access a file and is normally a hack attempt
    $data = htmlspecialchars($data); //ASCII-esques out weird characters
    return $data;
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
    <title>Thank you for the info! - The Educated Vote</title>
    <link rel="stylesheet" type="text/css" href="Styles/TheEducatedVoteUniversalStyle.css">
    <link rel = "stylesheet" type = "text/css" href = "Styles/TheEducatedVoteIndexStyle.css">
    <!--<script src="jquery-1.11.2.js"></script>
    <script src = "js.cookie.js"></script>
    <script src = "illinidrumlinescript.js"></script>
    <script src = "illinidrumlineUniversalScript.js"></script>
    <link rel="shortcut icon" href="https://dl.dropboxusercontent.com/s/l6lhizy5fgt5ydm/BlockITransparentIconSize.ico?dl=0"> <!-- the little image shown on the tab of the webpage -->

    <link rel="shortcut icon" href="Photos/DinosaurPoliticalParty.jpg">

    <meta charset="UTF-8">
    <meta name = "description" content = "The Issues Explained, The Educated Vote"> <!-- description of page -->
    <meta name = "author" content = "Kevin Gibbons"> <!-- who wrote it -->
</head>
<body>
Thank you for your submission! Click <a href="SubmitInfo.php">here</a> to submit more info or <a href="TheEducatedVoteIndex.php">here</a> to go back to the home page.
</body>
</html>
