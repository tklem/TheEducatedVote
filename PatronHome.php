<?php
include 'DatabaseConnection.php';
include 'TheEducatedVoteConstants.php';

getPatronInfo();

function getPatronInfo()
{
    $consts = Constants::getInstance();
    $patronInfoKeys = $consts::getPatronBasicInfoKeys(); //all the keys for the basic info
    $login = "ktgibbo2"; //@@@@@placeholder for patron's login info@@@@@

    $patronInfoArray = getPatronBasicInfo($login); //get the info from database
    $patronId = $patronInfoArray[$patronInfoKeys['id']]; //placeholder for patronId in table @@@@@replace with something dynamic@@@@@

    makePatronIssuesCookie($patronId); //move on to handling the information on the patron's issues
}

function getPatronBasicInfo($login)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $patronInfoArray = array();
    $patronInfoPrep = "SELECT * FROM theeducatedvote.patrons_basic_info WHERE login = ? LIMIT 1;";
    if($patronInfoPrep = $conn->prepare($patronInfoPrep))
    {
        $patronInfoPrep->bind_param('s', $login);
        $patronInfoPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $patronInfoPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$patronInfoArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($patronInfoPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables

        $patronInfoPrep->fetch(); //this line gets a SINGLE array with column names as keys
        //var_dump_pre($basicInfoArray);

        $patronInfoPrep->close(); //close the prepare/bind/execute
        return $patronInfoArray;
    }
    return "error in patron basic info fetch";
}

//@@@@@ do this without a cookie
function makePatronIssuesCookie($patronId) //takes the patron info and puts it into a session cookie for easy access
{
    $importanceArray = arsort(getPatronIssuesImportances($patronId)); //sort the importance array from most to least important

    $cookieName = Constants::getPatronIssuesCookieName();

    $toStoreInCookieJsonEncoded = json_encode($importanceArray); //convert to JSON
    //$savedArray = json_decode($cookie, true); //to get cookie back in PHP, use this
    setcookie($cookieName, $toStoreInCookieJsonEncoded, time() + (86400 * 30), "/"); //stores the cookie for 30 days available throughout entire domain @@@@@smarter time allocation for cookie, tie to login@@@@@
}

//returns associative array with issues as keys, importances (tinyints) as values
function getPatronIssuesImportances($patronId)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $patronIssuesImportances = array();
    $patronImportancesPrep = "SELECT * FROM theeducatedvote.patrons_issues_importances WHERE id = ? LIMIT 1;";
    if($patronImportancesPrep = $conn->prepare($patronImportancesPrep))
    {
        $patronImportancesPrep->bind_param('i', $patronId);
        $patronImportancesPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $patronImportancesPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$patronIssuesImportances[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($patronImportancesPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables

        $patronImportancesPrep->fetch(); //this line gets a SINGLE array with column names as keys
        //var_dump_pre($basicInfoArray);

        $patronImportancesPrep->close(); //close the prepare/bind/execute
        return $patronIssuesImportances;
    }
    return "error in patron issues importances fetch";
}

//returns associative array with issues as keys, yn (bit(1)) as values
function getPatronIssuesYN($patronId)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $patronIssuesYN = array();
    $patronYNPrep = "SELECT * FROM theeducatedvote.patrons_issues_yn WHERE id = ? LIMIT 1;";
    if($patronYNPrep = $conn->prepare($patronYNPrep))
    {
        $patronYNPrep->bind_param('i', $patronId);
        $patronYNPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $patronYNPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$patronIssuesYN[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($patronYNPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables

        $patronYNPrep->fetch(); //this line gets a SINGLE array with column names as keys
        //var_dump_pre($patronIssuesYN);

        $patronYNPrep->close(); //close the prepare/bind/execute
        return $patronIssuesYN;
    }
    return "error in patron issues YN fetch";
}

//returns associative array with issues as keys, binary representation of stances (bit(40)) as values
function getPatronIssuesStances($patronId)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $patronIssuesStances = array();
    $patronStancesPrep = "SELECT * FROM theeducatedvote.patrons_issues_stances WHERE id = ? LIMIT 1;";
    if($patronStancesPrep = $conn->prepare($patronStancesPrep))
    {
        $patronStancesPrep->bind_param('i', $patronId);
        $patronStancesPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $patronStancesPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$patronIssuesStances[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($patronStancesPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables

        $patronStancesPrep->fetch(); //this line gets a SINGLE array with column names as keys

        $patronStancesPrep->close(); //close the prepare/bind/execute
        return (stancesJsonInterpret($patronIssuesStances));
    }
    return "error in patron issues YN fetch";
}

//returns associative array with issues as keys, arrays of binary values of stances as values
function stancesJsonInterpret($stances)
{
    $consts = Constants::getInstance();
    $issueKeys = $consts::getIssueKeys();
    $stancesDecoded = array();

    foreach($issueKeys as $key)
    {
        $stancesDecoded[$key] = json_decode($stances[$key], true);
    }
    return $stancesDecoded;
}

/* this should be obselete now, keeping it around for a little while just in case 
function setupPatronIssuesBox($patronId)
{
    $issuesCookie = $_COOKIE[Constants::getPatronIssuesCookieName()]; //get cookie

    $outputContainersArray = []; //we'll put all the containers in here once they're made

    $ynArray = getPatronIssuesYN($patronId);
    $importancesArray = json_decode($issuesCookie, true); //convert to array @@@@@needs to not be a cookie
    $stancesArray = json_decode(file_get_contents('Caches/Issue Info/stances.txt'), true);

    foreach($importancesArray as $issueKey => $import) //go through the issues
    {
        $issueName = ucwords(preg_replace('_', ' ', $issueKey)); //Name of the issue made to look pretty
        $issueChecked = ''; //holds 'checked' if the user has marked this issue as important

        $outputHtml = '
            <div class="issueCheckboxesContainer" id="'. $issueKey .'CheckboxesContainer">
                <ul class="issueCheckboxesContainerOuterUl" name="'. $issueKey .'CheckboxesContainerOuterUl" id="'. $issueKey .'CheckboxesContainerOuterUl">
                    <li><input type="checkbox" class="issueContainerOuterCheckbox" name="'. $issueKey .'Checkbox" value="1" placeholder="" id="'. $issueKey .'Checkbox" '. $issueChecked .'><label for="'. $issueKey .'Checkbox">'. $issueName .'</label>
                        <ul class="issueCheckboxContainerInnerUl" name="'. $issueKey .'CheckboxContainerInnerUl" id="'. $issueKey .'CheckboxContainerInnerUl">
                            <li><input type="checkbox" class="issueContainerInnerCheckbox" name="'. $issueKey .'ForAgainstCheckbox" value="1" id="'. $issueKey .'ForCheckbox"><label for="'. $issueKey .'ForCheckbox" '. $forChecked .'>'. $forStatement .'</label></li>
                            <li><input type="checkbox" class="issueContainerInnerCheckbox" name="'. $issueKey .'ForAgainstCheckbox" value="2" id="'. $issueKey .'AgainstCheckbox"><label for="'. $issueKey .'AgainstCheckbox" '. $againstChecked .'>'. $againstStatement .'</label></li>
                        </ul>
                    </li>
                </ul>
            </div>
        ';
        $outputContainersArray[] = $outputHtml; //add it to the array, in order of importance
    }
}*/

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






function dumpStuffIntoPatronStances()
{
    $array['abortion'] = array('stance1' => 1, 'stance2' => 1, 'stance3' => 0, 'stance4' => 0, 'stance5' => 1, 'stance6' => 1, 'stance7' => 0, 'stance8' => 0);
    $array['affirm'] = array('stance1' => 0, 'stance2' => 0, 'stance3' => 0, 'stance4' => 0, 'stance5' => 0, 'stance6' => 0, 'stance7' => 0, 'stance8' => 0);
    $array['birth'] = array('stance1' => 1, 'stance2' => 1, 'stance3' => 1, 'stance4' => 1, 'stance5' => 1, 'stance6' => 1, 'stance7' => 1, 'stance8' => 1);
    databaseDump($array);
}

function databaseDump($array)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();
    $id = 1; $fk = 1;
    $abortion = json_encode($array['abortion']);
    $affirm = json_encode($array['affirm']);
    $birth = json_encode($array['birth']);

    $submittedPrep = "INSERT INTO theeducatedvote.patrons_issues_stances(id, basic_info_id_fk, abortion, affirmative_action, birth_control) VALUES (?, ?, ?, ?, ?)";
    if($submittedPrep = $conn->prepare($submittedPrep))
    {
        $submittedPrep->bind_param('iisss', $id, $fk, $abortion, $affirm, $birth);
        $submittedPrep->execute();
        //debug_to_console($title, $author, $outlet, $published, $primaries, $secondaries, $link, $media); //@@@@@ silences "unused variable" warnings
        $submittedPrep->close(); //close the prepare/bind/execute
    }
}
?>

<!doctype html>
<html>
<head>
    <title>The Educated Vote</title>
    <link rel="stylesheet" type="text/css" href="Styles/TheEducatedVoteUniversalStyle.css">
    <link rel = "stylesheet" type = "text/css" href = "Styles/TheEducatedVoteIndexStyle.css">
    <!--<script src="jquery-1.11.2.js"></script>
    <script src = "js.cookie.js"></script>
    <script src = "illinidrumlinescript.js"></script>
    <script src = "illinidrumlineUniversalScript.js"></script>
    <link rel="shortcut icon" href="https://dl.dropboxusercontent.com/s/l6lhizy5fgt5ydm/BlockITransparentIconSize.ico?dl=0"> <!-- the little image shown on the tab of the webpage -->

    <link rel="shortcut icon" href="Photos/DinosaurPoliticalParty.jpg">

    <meta charset="UTF-8">
    <meta name = "keywords" content = "vote, voter, election, democrat, republican, polls, senate, representative, congress, president"> <!-- search engines look for these -->
    <meta name = "description" content = "Main page, The Educated Vote"> <!-- description of page -->
    <meta name = "author" content = "Kevin Gibbons"> <!-- who wrote it -->
</head>

<body>
    <?php include 'TheEducatedVoteHeader.php'?>
    <?php include 'TheEducatedVoteSidebar.php'?>
    <?php include 'TheEducatedVoteFooter.php'?>
</body>


















