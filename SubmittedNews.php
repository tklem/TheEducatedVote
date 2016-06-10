<?php
include 'TheEducatedVoteConstants.php';
include 'DatabaseConnection.php';

getPostInfo();

//gets the info from $_POST that we need to put in the database
function getPostInfo()
{
    $titleKey = "title"; $authorKey = "author"; $outletKey = "outlet"; $linkKey = "link"; $mediaKey = "media";//identifiers for HTML elements
    $publishedKey = "published"; $primariesKey = "primaries"; $secondariesKey = "secondaries";

    //@@@@@code in character limits here to make sure we don't exceed what the database can handle
    if (empty($_POST[$titleKey]))
        $title = "Error: Empty value slipped through";
    else
        $title = $_POST[$titleKey];

    if (empty($_POST[$authorKey])) //@@@@@ this will be blank sometimes; not all news outlets publish author names
        $author = "Error: Empty value slipped through";
    else
        $author = $_POST[$authorKey];

    if (empty($_POST[$outletKey]))
        $outlet = "Error: Empty value slipped through";
    else
        $outlet = $_POST[$outletKey];

    if (empty($_POST[$publishedKey]))
        $published = "Error: Empty value slipped through";
    else
    {
        debug_to_console("something was published");
        $published = makeDate($_POST[$publishedKey]);
        debug_to_console($published);
    }

    if (empty($_POST[$primariesKey]))
        $primaries = "Error: Empty value slipped through";
    else
    {
        $primaries = makeArray($_POST[$primariesKey]);
    }
    if (empty($_POST[$secondariesKey]))
        $secondaries = "Error: Empty value slipped through";
    else
    {
        $secondaries = makeArray($_POST[$secondariesKey]);
    }
    if (empty($_POST[$linkKey]))
        $link = "Error: Empty value slipped through";
    else
        $link = $_POST[$linkKey];

    if (empty($_POST[$mediaKey]))
        $media = "Error: Empty value slipped through";
    else
        $media = $_POST[$mediaKey];

    if (isset($_POST['submitInfo'])) //should always be true, since that's how we get here
    {
        moveToDatabase($title, $author, $outlet, $published, $primaries, $secondaries, $link, $media);
    }
}

//dumps the submitted info into a database where the Info Admins can check it out later
function moveToDatabase($title, $author, $outlet, $published, $primaries, $secondaries, $link, $media)
{
    date_default_timezone_set('UTC'); //set timestamp to UTC
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $submittedBy = "Kevin Gibbons"; //@@@@@ must be easier way to get this (session cookie? localstorage?)
    $submittedWhen = date("Y-m-d H:i:s"); //timestamp in datetime format

    $submittedPrep = "INSERT INTO theeducatedvote.submitted_news (submitted_by, submitted_when, article_title, author, news_outlet, date_published, primary_people, secondary_people, link, media_types) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if($submittedPrep = $conn->prepare($submittedPrep))
    {
        $submittedPrep->bind_param('ssssssssss', $submittedBy, $submittedWhen, $title, $author, $outlet, $published, $primaries, $secondaries, $link, $media);
            $primaries = json_encode($primaries); //@@@@@ needs to be JSON string of an array
            $secondaries = json_encode($secondaries); //@@@@@ needs to be JSON string of an array
            $media = json_encode($media); //@@@@@ needs to be JSON string of an array
            $submittedPrep->execute();
            //debug_to_console($title, $author, $outlet, $published, $primaries, $secondaries, $link, $media); //@@@@@ silences "unused variable" warnings
        $submittedPrep->close(); //close the prepare/bind/execute
    }
    //redirectToPage($forPage); //@@@@@shoot them back to the last page they came from
}

//requires comma separated values @@@@@ do some problem case testing here
function makeArray($stringy)
{
    return explode(',', $stringy, 50);
}

function makeDate($stringy)
{
    //$day = substr($stringy, 0, 2); $month = substr($stringy, 3, 2); $year = substr($stringy, 6, 4);
    //$dateString = $year.'/'.$month.'/'.$day;
    debug_to_console($stringy);
    return date('Y-m-d', strtotime($stringy));
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