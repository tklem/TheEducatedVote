<?php
include 'TheEducatedVoteConstants.php';
include 'DatabaseConnection.php';

$issueCategories = array();
$issueDefinitions = array();
$issueReferences = array();
$issueStances = array();

updateCache($issueCategories, $issueDefinitions, $issueReferences, $issueStances);

//updates the caches for this page and also assigns their values back to the passed variables by reference
//this way we can get get them (since they are in the full scope of this PHP script) via jquery via json_encode later
function updateCache(&$issueCategories, &$issueDefinitions, &$issueReferences, &$issueStances)
{
    //debug_to_console('hit update cache');
    $consts = Constants::getInstance();
    $docRoot = $consts->getDocumentRoot();

    $cache_dir = $docRoot .'Caches/Issue Info'; //directory location of cache
    $categories_cache_file = $cache_dir .'/categories.txt'; //cache for categories array
    $definitions_cache_file = $cache_dir .'/definitions.txt'; //cache for categories array
    $references_cache_file = $cache_dir .'/references.txt'; //cache for categories array
    $stances_cache_file = $cache_dir .'/stances.txt'; //cache for categories array
    $expansions_cache_file = $cache_dir .'/expansions.txt'; //cache for the expansions popups for all the issues
    checkAndMakeDirectories($cache_dir); //ensure theese files' home directory exists and create it if it doesn't

    if (!file_exists($categories_cache_file) || filemtime($categories_cache_file) < (time() - 86400)) {updateCategoriesCache($categories_cache_file);} //if file doesn't exist or it hasn't been updated in 24 hours
    if (!file_exists($definitions_cache_file) || filemtime($definitions_cache_file) < (time() - 86400) || !file_exists($references_cache_file) || filemtime($references_cache_file) < (time() - 86400)) {updateDefinitionsAndReferencesCaches($definitions_cache_file, $references_cache_file);}//if file doesn't exist or it hasn't been updated in 24 hours
    if (!file_exists($stances_cache_file) || filemtime($stances_cache_file) < (time() - 86400)) {updateStancesCache($stances_cache_file);}//if file doesn't exist or it hasn't been updated in 24 hours

    $issueCategories = json_decode(file_get_contents($categories_cache_file), true);
    //var_dump_pre($issueCategories);
    $issueDefinitions = json_decode(file_get_contents($definitions_cache_file), true);
    //var_dump_pre($issueDefinitions);
    $issueReferences = json_decode(file_get_contents($references_cache_file), true);
    //var_dump_pre($issueReferences);
    $issueStances = json_decode(file_get_contents($stances_cache_file), true);
    //var_dump_pre($issueStances);

    if (!file_exists($expansions_cache_file) || filemtime($expansions_cache_file) < (time() - 86400)) {updateExpansionsCache($expansions_cache_file,$issueDefinitions, $issueReferences, $issueStances);}//if file doesn't exist or it hasn't been updated in 24 hours
    $issueExpansions = json_decode(file_get_contents($expansions_cache_file), true);
    var_dump_pre($issueExpansions);
}

//checks to make sure the path to the cache exists and makes it if it doesn't
function checkAndMakeDirectories($file_path)
{
    if(!is_dir($file_path)) mkdir($file_path, 0777, true);
}

//updates the Categories Cache
function updateCategoriesCache($categories_cache_file)
{
    file_put_contents($categories_cache_file, json_encode(getCategoriesFromDatabase()));
}

//gets  the Categories information from the database formatted as an array
function getCategoriesFromDatabase()
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $categoriesArray = array();
    $categoriesQuery = "SELECT * FROM theeducatedvote.issue_categories;";
    if($resultBox = $conn->query($categoriesQuery))
    {
        while($row = $resultBox->fetch_assoc())
        {
            $tempCategory= $row['category'];
            unset($row['id']); unset($row['category']); //we don't care about this data in our array
            $categoriesArray[$tempCategory] = $row;
        }
        //var_dump_pre($categoriesArray);
        return $categoriesArray;
    }
    else return 'error in Categories Fetch';
}

//updates both the Definitions and References Caches (does both in one function because the
//information is stored in the same table in the database
function updateDefinitionsAndReferencesCaches($definitions_cache_file, $references_cache_file)
{
    $consts = Constants::getInstance();
    $issueDefKeys = $consts::getIssueDefinitionKeys();
    
    $fullArray = getDefinitionsAndReferencesFromDatabase();
    $definitionsArray = array();
    $referencesArray = array();

    foreach($fullArray as $row)
    {
        $definitionsArray[$row[$issueDefKeys['issue']]] = array($row[$issueDefKeys['explanation']]);
        $referencesArray[$row[$issueDefKeys['issue']]] = array($row[$issueDefKeys['reference_1']], $row[$issueDefKeys['reference_2']], $row[$issueDefKeys['reference_3']], $row[$issueDefKeys['reference_4']]);
    }

    file_put_contents($definitions_cache_file, json_encode($definitionsArray));
    file_put_contents($references_cache_file, json_encode($referencesArray));
}

//gets  the Definitions and References information from the database formatted as an array
function getDefinitionsAndReferencesFromDatabase()
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $defsAndRefsArray = array();
    $defsAndRefsQuery = "SELECT * FROM theeducatedvote.issue_definitions;";
    if($resultBox = $conn->query($defsAndRefsQuery))
    {
        while($row = $resultBox->fetch_assoc())
        {
            $defsAndRefsArray[$row['issue']] = $row;
        }
        //var_dump_pre($defsAndRefsArray);
        return $defsAndRefsArray;
    }
    else return 'error in Defitions and References Fetch';
}

//updates the Stances cache
function updateStancesCache($stances_cache_file)
{
    file_put_contents($stances_cache_file, json_encode(getStancesFromDatabase()));
}

//gets  the Stances information from the database formatted as an array
function getStancesFromDatabase()
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stancesArray = array();
    $stancesQuery = "SELECT * FROM theeducatedvote.issue_stances;";
    if($resultBox = $conn->query($stancesQuery))
    {
        while($row = $resultBox->fetch_assoc())
        {
            $tempName = $row['issue_name'];
            unset($row['id']); unset($row['issue_name']); //we don't care about this data in our array
            $stancesArray[$tempName] = $row;
        }
        //var_dump_pre($stancesArray);
        return $stancesArray;
    }
    else return 'error in Stances Fetch';
}

//makes the bar that will have all the categories on it that, when clicked, will change the display
//to show the issues in that category
function makeNavBar($issueCategories)
{
    $liElements = '<li class="category" data-category="all">All</li>'; //initialize with an "all" button
    foreach($issueCategories as $catName => $catArray) //for each category, we put a navigation item on the bar
    {
        $liElements .= '<li class="category" data-category="'. $catName .'">'. $catName .'</li>';
    }

    $outputHtml = '
    <div class="categoryNavBarContainer">
        <ul class="categoryNavBar">
            '. $liElements .'
        </ul>
    </div>
    ';
    return $outputHtml;
}

//makes the tiles that hold the issues and, when clicked, will display the information for the issue
function makeTileArray($issueDefinitions)
{
    $consts = Constants::getInstance();
    $tileArray = array();
    foreach($issueDefinitions as $issue => $definition) //for each issue that we have a definition for
    {
        $pictureSrc = $consts::getDocumentRoot() ."Photos/". $issue ."_picture.png";
        $title = ucwords(str_replace('_', ' ', $issue)); //title formatting
        $outputHtml = '
        <div class="issueTileContainer">
            <div class="issueTilePictureContainer">
                <img src="'. $pictureSrc .'" alt="picture for '. $issue .'">
            </div>
            <div class="issueTileTitleContainer">
                <h3 class="'. $title .'"></h3>
            </div>
        </div>
        ';
        $tileArray[$issue] = $outputHtml;
    }
}

function updateExpansionsCache($expansions_cache_file, $issueDefinitions, $issueReferences, $issueStances)
{
    file_put_contents($expansions_cache_file, json_encode(makeExpansionsPopups($issueDefinitions, $issueReferences, $issueStances)));
}

//makes the popups that display the information about issues
function makeExpansionsPopups($issueDefinitions, $issueReferences, $issueStances)
{
    //var_dump_pre($issueDefinitions);
    $popupsArray = array();
    foreach($issueDefinitions as $issue => $definition) //for each issue that we have a definition for
    {
        $definitionString = $definition[0];
        $referencesHtml = ''; $stancesHtml = ''; //need to reset these to empty at the beginning of every loop
        $title = ucwords(str_replace('_', ' ', $issue)); //title formatting
        foreach($issueReferences[$issue] as $ref) //get html for the references
        {
            if($ref) //if it's not null, put it in
            {
                if(strlen($ref) > 40) //if it's longer than 40 characters, we'll truncate the display of the link
                {
                    $referencesHtml .= '<p class="issuePopupLinks"><a href ="'. $ref .'">'. substr($ref, 0, 40) .'...</a></p>';
                }
                else //otherwise display it normally
                {
                    $referencesHtml .= '<p class="issuePopupLinks"><a href ="'. $ref .'">'. $ref .'</a></p>';
                }
            }
        }
        foreach($issueStances[$issue] as $stance) //get html for the stances
        {
            if($stance) //if it's not null or the issue(foreign key) put it in
            {
                $stancesHtml .= '<p class="issuePopupStance">'. $stance .'</p>';
            }
        }
        $outputHtml = '
        <div class="issuePopupContainer">
            <div class="issuePopupTitleContainer">
                <h1 class="issuePopupTitle">'. $title .'</h1>
            </div>
            <div class="issuePopupTextContainer">
                <p class="issuePopupText">'. $definitionString .'</p>
            </div>
            <div class="issuePopupBottomContainer">
                <div class="issuePopupLinksContainer">
                    <h2 class="issuePopupSubtitle">More Information:</h2>
                    '. $referencesHtml .'
                </div>
                <div class="issuePopupStancesContainer">
                    <h2 class="issuePopupSubtitle">Stances:</h2>
                    '. $stancesHtml .'
                </div>
            </div>
        </div>
        ';
        $popupsArray[$issue] = $outputHtml; //usual deal here, stack the html on an array with the issues as keys
    }
    return $popupsArray;
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
    <title>The Issues Explained - The Educated Vote</title>
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

Well we got here 

</body>
</html>