<?php
include 'TheEducatedVoteConstants.php';
include 'DatabaseConnection.php';


updateCache(); //gets the ball rolling 

//updates the cache for the given page if it needs to be
function updateCache()
{
    //debug_to_console('hit update cache');
    $consts = Constants::getInstance();
    $docRoot = $consts->getDocumentRoot();
    $basic_info_id = 0;
    if ($_GET['office'] != 'President') //if we're dealing with anyone who's not running for president, we need their state
    {
        $cache_dir = $docRoot .'Caches/'. $_GET['year'] .'/'. $_GET['state'] .'/'. $_GET['office']; //directory location of cache
    }
    else //if it is a presidential candidate, we don't care about the state
    {
        $cache_dir = $docRoot .'Caches/'. $_GET['year'] .'/'. $_GET['office']; //directory location of cache
    }
    $cache_file = $cache_dir .'/'. $_GET['name'] .'.txt'; //file where we'll be putting/retrieving this cache

    if (file_exists($cache_file) && filemtime($cache_file) > (time() - 3600)) //if file exists and hasn't been updated in the last hour...
    {
        //currently, we don't care
    }
    else //If the content is an hour stale or more OR there is no file there, update/create it
    {
        checkAndMakeDirectories($cache_dir); //ensure directory exists
        $basic_info_id = getCandidateId($_GET['name'], $_GET['office']); //get the id of the candidate
        $issuesHtmlArray = getCandidateIssueInfo($basic_info_id); //get our content that we're going to cache
        file_put_contents($cache_file, json_encode($issuesHtmlArray)); //put the data in $cache_file, LOCK_EX ensures only one write at a time
    }

    checkAndRedirect($basic_info_id);
}

//checks to make sure the directory we're writing to exists and makes it if it doesn't
function checkAndMakeDirectories($file_path)
{
    if(!is_dir($file_path)) mkdir($file_path, 0777, true);
}

//returns all the candidate non-issue info as ready-to-go HTML
function getCandidateInfoHTML($basic_info_id)
{
    debug_to_console('getCandidateInfoHTML');
    $retVal = '' . getCandidateBasicInfoHTML($basic_info_id) . getCandidateHeldOfficesInfoHTML($basic_info_id) . getCandidateContactInfoHTML($basic_info_id) .'';
    return $retVal;
}

//returns the HTML for the basic info for the candidate
function getCandidateBasicInfoHTML($basic_info_id)
{
    //debug_to_console('getCandidateBasicInfoHTML');
    $consts = Constants::getInstance();
    $basicInfo = getCandidateBasicInfo($basic_info_id);
    $basicInfoKeys = $consts->getCandidateBasicInfoKeys();

    $picSrc = $consts->getDocumentRoot() . 'Photos/'. $_GET['name'] . $_GET['office'] . $_GET['year'];
    $picAlt = 'Photo of '. $_GET['name'];

    $returnHTML = '
        <div class="basicInfoContainer">
            <div class="candidateNameContainer">
                <h1 class="candidateName">'. htmlspecialchars($basicInfo[$basicInfoKeys['name']], ENT_QUOTES) .'</h1>
            </div>
            <div class="basicInfoCandidatePictureContainer">
                <img src="'. htmlspecialchars($picSrc, ENT_QUOTES) .'" alt="'. htmlspecialchars($picAlt, ENT_QUOTES) .'">
            </div>
            <div class="basicInfoBioContainer">
                <p class="bio">'. htmlspecialchars($basicInfo[$basicInfoKeys['bio']], ENT_QUOTES) .'</p>
            </div>
            <div class="basicInfoPartyContainer">
                <p class="party">'. htmlspecialchars($basicInfo[$basicInfoKeys['party']], ENT_QUOTES) .'</p>
            </div>
            <div class="basicInfoElectionDetailsContainer">
                <p class="electionDetails">'. htmlspecialchars($basicInfo[$basicInfoKeys['election']], ENT_QUOTES) .' '. htmlspecialchars($basicInfo[$basicInfoKeys['office']], ENT_QUOTES) .'</p>
                <p class="electionDetails">'. htmlspecialchars($basicInfo[$basicInfoKeys['district']], ENT_QUOTES) .' '. htmlspecialchars($basicInfo[$basicInfoKeys['state']], ENT_QUOTES) .'</p>
            </div>
        </div>
    ';

    return $returnHTML;
}

//returns HTML for the held offices and corresponding committees for the candidate
function getCandidateHeldOfficesInfoHTML($basic_info_id)
{
    //debug_to_console('hit the held offices HTML retrieval');
    $consts = Constants::getInstance();
    $heldOffices = getCandidateHeldOffices($basic_info_id);
    $committeesEtc = [];
    foreach($heldOffices as $row)
    {
        $held_office_id = $row['id'];
        $committeesEtc[] = getCandidateCommitteesEtc($held_office_id);
    }

    $heldOfficesKeys = $consts->getCandidateHeldOfficesKeys();
    $committeesEtcKeys = $consts->getCandidateCommitteesEtcKeys();

    $returnHTML = '<div class="heldOfficesOuterContainer">';
    foreach($heldOffices as $office)
    {
        $returnHTML .= '
            <div class="heldOfficesInnerContainer">
                <p class="officeDetails">'. htmlspecialchars($office[$heldOfficesKeys['title']], ENT_QUOTES) .' in '. htmlspecialchars($office[$heldOfficesKeys['state']], ENT_QUOTES) .'</p>
                <p class="officeDetails">From '. htmlspecialchars($office[$heldOfficesKeys['start_date']], ENT_QUOTES) .' to '. htmlspecialchars($office[$heldOfficesKeys['end_date']], ENT_QUOTES) .'</p>
                <div class="comitteeOuterContainer>"
        ';
        foreach($committeesEtc as $comArray) //the $committeesEtc array is 3D. This foreach grabs the list of committees for each office
        {
            foreach($comArray as $com) //this foreach grabs each committee from the list of committees
            {
                if ($com[$committeesEtcKeys['held_offices_id_fk']] == $office[$heldOfficesKeys['id']])
                {
                    $returnHTML .= '
                    <div class="committeeInnerContainer">
                        <p class="committeeDetails">' . htmlspecialchars($com[$committeesEtcKeys['title']], ENT_QUOTES) . '</p>
                        <p class="committeeDetails">' . htmlspecialchars($com[$committeesEtcKeys['rank']], ENT_QUOTES) . '</p>
                        <p class="committeeDetails">From ' . htmlspecialchars($com[$committeesEtcKeys['start_date']], ENT_QUOTES) . ' to ' . htmlspecialchars($com[$committeesEtcKeys['end_date']], ENT_QUOTES) . '</p>
                    </div>
                ';
                }
            }
        }
        $returnHTML .= '</div>';
    }
    $returnHTML .= '</div>';
    return $returnHTML;
}

//returns HTML for the contact info and office locations/phones of the candidate
function getCandidateContactInfoHTML($basic_info_id)
{
    debug_to_console('getCandidateContactInfoHTML');
    $consts = Constants::getInstance();

    $contactInfo = getCandidateContactInfo($basic_info_id);
    $officesInfo = getCandidateOfficesInfo($contactInfo['id']);
    $contactInfoKeys = $consts->getCandidateContactInfoKeys();
    $officesInfoKeys = $consts->getCandidateOfficesInfoKeys();

    $returnHTML = '
        <div class="contactInfoContainer">
            <div class="contactInfoNonSocialMedia">
                <p class="contactInfo">Email: '. htmlspecialchars($contactInfo[$contactInfoKeys['email']], ENT_QUOTES) .'</p>
                <p class="contactInfo">Website: '. htmlspecialchars($contactInfo[$contactInfoKeys['website']], ENT_QUOTES) .'</p>
            </div>
            <div class="contactInfoSocialMediaContainer">
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['facebook']], ENT_QUOTES) .'">Facebook</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['twitter']], ENT_QUOTES) .'">Twitter</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['youtube']], ENT_QUOTES) .'">YouTube</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['google_plus']], ENT_QUOTES) .'">Google Plus</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['instagram']], ENT_QUOTES) .'">Instagram</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['flickr']], ENT_QUOTES) .'">Flickr</a></p>
                <p class="socialMediaLink"><a href="'. htmlspecialchars($contactInfo[$contactInfoKeys['tumblr']], ENT_QUOTES) .'">Tumblr</a></p>
            </div>
            <div class="officeOuterContainer">
        ';

    foreach($officesInfo as $office)
    {
        $returnHTML .= '
        <div class="officeInnerContainer">
            <p class="officePhone">Phone:' . htmlspecialchars($office[$officesInfoKeys['phone']], ENT_QUOTES) . ' Fax: ' . htmlspecialchars($office[$officesInfoKeys['fax']], ENT_QUOTES) . '</p>
            <div class="officeAddressContainer">
                <p class="officeAddress">' . htmlspecialchars($office[$officesInfoKeys['address_line_one']], ENT_QUOTES) . '</p>
                <p class="officeAddress">' . htmlspecialchars($office[$officesInfoKeys['address_line_two']], ENT_QUOTES) . '</p>
                <p class="officeAddress">' . htmlspecialchars($office[$officesInfoKeys['city']], ENT_QUOTES) . ' ' . htmlspecialchars($office[$officesInfoKeys['state']], ENT_QUOTES) . ' ' . htmlspecialchars($office[$officesInfoKeys['zipcode']], ENT_QUOTES) . '</p>
            </div>
        </div>
        ';
    }
    $returnHTML .= '
        </div>
    </div>';

    return $returnHTML;
}

//returns an array with the basic_info columns as keys and their info as values
function getCandidateBasicInfo($basic_info_id)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $basicInfoArray = array(); //to hold values
    $candidateBasicInfoPrep = "SELECT * FROM theeducatedvote.candidates_basic_info WHERE id = ? LIMIT 1;";
    if($candidateBasicInfoPrep = $conn->prepare($candidateBasicInfoPrep))
    {
        $candidateBasicInfoPrep->bind_param('i', $basic_info_id);
        $candidateBasicInfoPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $candidateBasicInfoPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$basicInfoArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($candidateBasicInfoPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        
        $candidateBasicInfoPrep->fetch(); //this line gets a SINGLE array with column names as keys
        //var_dump_pre($basicInfoArray);
        
        $candidateBasicInfoPrep->close(); //close the prepare/bind/execute
        return $basicInfoArray;
    }
    else return 'error in Basic Info Fetch';
}

//returns an array(numerical) of arrays with the held_offices columns as keys and their info as values
function getCandidateHeldOffices($basic_info_id) //@@@@@haven't verified that this one works yet 
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $heldOfficesArray = array();
    $candidateHeldOfficesPrep = "SELECT * FROM theeducatedvote.candidates_held_offices WHERE basic_info_id_fk = ? ORDER BY end_date DESC LIMIT 20;";
    if($candidateHeldOfficesPrep = $conn->prepare($candidateHeldOfficesPrep))
    {
        $candidateHeldOfficesPrep->bind_param('i', $basic_info_id);
        $candidateHeldOfficesPrep->execute();

        $params = array(); //to hold all the column names
        $dataForEachRowTemp = array();
        $meta = $candidateHeldOfficesPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$dataForEachRowTemp[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($candidateHeldOfficesPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        $rowArray = array();
        while ($candidateHeldOfficesPrep->fetch()) //this puts the result of the fetch for each ROW in it's own NUMBERED index in $tempAllRowsArray
        {
            foreach($dataForEachRowTemp as $key => $val)
            {
                $rowArray[$key] = $val;
            }
            $heldOfficesArray[] = $rowArray;
        }
        //($heldOfficesArray);
        $candidateHeldOfficesPrep->close(); //close the prepare/bind/execute

        return $heldOfficesArray;
    }
    else return 'error in Held Offices Fetch';
}

//returns an array(numerical) of arrays with the committees_etc columns as keys and their info as values
function getCandidateCommitteesEtc($held_offices_id)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $committeesEtcArray = array();
    $committeesEtcPrep = "SELECT * FROM theeducatedvote.candidates_committees_and_other_responsibilities WHERE held_offices_id_fk = ? LIMIT 5;";
    if($committeesEtcPrep = $conn->prepare($committeesEtcPrep))
    {
        $committeesEtcPrep->bind_param('i', $held_offices_id);
        $committeesEtcPrep->execute();

        $params = array(); //to hold all the column names
        $dataForEachRowTemp = array();
        $meta = $committeesEtcPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$dataForEachRowTemp[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($committeesEtcPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        $rowArray = array();
        while ($committeesEtcPrep->fetch()) //this puts the result of the fetch for each ROW in it's own NUMBERED index in $tempAllRowsArray
        {
            foreach($dataForEachRowTemp as $key => $val)
            {
                $rowArray[$key] = $val;
            }
            $committeesEtcArray[] = $rowArray;
        }
        //var_dump_pre($committeesEtcArray);
        $committeesEtcPrep->close(); //close the prepare/bind/execute

        return $committeesEtcArray;
    }
    else return 'error in Committees Etc Fetch';
}

//returns an array with the contact_info columns as keys and their info as values
function getCandidateContactInfo($basic_info_id)
{
    //debug_to_console("getCandidateContactInfo");
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $contactInfoArray = array();
    $contactInfoPrep = "SELECT * FROM theeducatedvote.candidates_contact_info WHERE basic_info_id_fk = ? LIMIT 1;";
    if($contactInfoPrep = $conn->prepare($contactInfoPrep))
    {
        $contactInfoPrep->bind_param('i', $basic_info_id);
        $contactInfoPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $contactInfoPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params, referencing them to $contactInfoArray
        {
            $params[] = &$contactInfoArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($contactInfoPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        //the following line gets a SINGLE array with column names as keys. It actually dumps the info into $params (using numbered indexes), but 
        //because $contactInfoArray has the same references, it also filled $contactInfoArray, using associative keys
        $contactInfoPrep->fetch();
        //var_dump_pre($contactInfoArray);        
        
        $contactInfoPrep->close();
        return $contactInfoArray;
    }
    else return 'error in Contact Info Fetch';
}

//returns an array(numerical) of arrays with the offices_info columns as keys and their info as values
function getCandidateOfficesInfo($contact_info_id)
{
    //debug_to_console('getCandidateOfficesInfo');
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $officesInfoArray = array();
    $officesInfoPrep = "SELECT * FROM theeducatedvote.candidates_offices_info WHERE contact_info_id_fk = ? LIMIT 10;";
    if($officesInfoPrep = $conn->prepare($officesInfoPrep))
    {
        $officesInfoPrep->bind_param('i', $contact_info_id);
        $officesInfoPrep->execute();

        $params = array(); //to hold all the column names
        $dataForEachRowTemp = array();
        $meta = $officesInfoPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params
        {
            $params[] = &$dataForEachRowTemp[$field->name]; //&$whatever is where we want the results of the query to end up
        }
        call_user_func_array(array($officesInfoPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        $rowArray = array();
        while ($officesInfoPrep->fetch()) //this puts the result of the fetch for each ROW in it's own NUMBERED index in $tempAllRowsArray
        {
            foreach($dataForEachRowTemp as $key => $val)
            {
                $rowArray[$key] = $val;
            }
            $officesInfoArray[] = $rowArray;
        }
        //var_dump_pre($officesInfoArray);
        $officesInfoPrep->close();

        return $officesInfoArray;
    }
    else return 'error in Offices Info Fetch';
}

//retrieves all the candidate info from the database
function getCandidateIssueInfo($basic_info_id)
{
    //debug_to_console('hit the candidate issue info HTML retrieval');
    $consts = Constants::getInstance();

    $issuesExpansions = getCandidateIssuesExpansions($basic_info_id);
    $issuesStances = getCandidateIssuesStances($basic_info_id);
    $issuesImportances = getCandidateIssuesImportances($basic_info_id);

    $issueOutputArray = [];

    foreach ($consts->getIssueKeys() as $key) //keys will hold all the issue names
    {
        $issueNameParsed = ucwords(str_replace('_', ' ', $key)); //key will be the issue name with spaces instead of underscores, first letter of each word capitalized
        $issueCandidateImport = $issuesImportances[$key];
        $issueCandidateStance = $issuesStances[$key]; //@@@@@ at this point in time, is still just a 40-bit binary
        $issueCandidateExpansion = $issuesExpansions[$key];

        $currOuputHtml = '
            <div class="issueInfoDiv" id="'. $key .'">
                <div class="issueHeadDiv">
                    <div class="issueNameDiv">
                        <span class="issueNameSpan">'. $issueNameParsed .'</span>
                    </div>
                    <div class="issueStanceDiv" data-stance="'. htmlspecialchars($issueCandidateStance, ENT_QUOTES) .'">
                        <span class="issueStanceSpan">'. htmlspecialchars($issueCandidateStance, ENT_QUOTES) .'</span>
                    </div>
                    <div class="issueCandidateImportDiv" data-candidate-import="'. htmlspecialchars($issueCandidateImport, ENT_QUOTES) .'">
                        <!--<span class="issueCandidateImportSpan">'. htmlspecialchars($issueCandidateImport, ENT_QUOTES) .'</span>-->
                    </div>
                </div>
                <div class="issueExpansionDiv">
                    <span class="issueExpansionSpan">'. htmlspecialchars($issueCandidateExpansion, ENT_QUOTES) .'</span>
                </div>
            </div>';
        $issueOutputArray[] = array($key => $currOuputHtml);
    }
    return $issueOutputArray;
}

//returns an array with the issues as keys and the expansions (as strings) as values
function getCandidateIssuesExpansions($basic_info_id)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $expansionsArray = array();
    $candidateExpansionsPrep = "SELECT * FROM theeducatedvote.candidates_issues_expansions WHERE basic_info_id_fk = ? LIMIT 1;";
    if($candidateExpansionsPrep = $conn->prepare($candidateExpansionsPrep))
    {
        $candidateExpansionsPrep->bind_param('i', $basic_info_id);
        $candidateExpansionsPrep->execute();
        
        $params = array(); //to hold all the column names
        $meta = $candidateExpansionsPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params, referencing them to $expansionsArray
        {
            $params[] = &$expansionsArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($candidateExpansionsPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        //the following line gets a SINGLE array with column names as keys. It actually dumps the info into $params (using numbered indexes), but 
        //because $expansionsArray has the same references, it also filled $expansionsArray, using associative keys
        $candidateExpansionsPrep->fetch(); 
        //var_dump_pre($expansionsArray);

        $candidateExpansionsPrep->close();
        return $expansionsArray;
    }
    else return 'error in Expansions Fetch';
}

//returns an array with the issues as keys and stances (as 40 bit binaries) as values
//@@@@@ turn binaries into stance strings before returning 
function getCandidateIssuesStances($basic_info_id)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stancesArray = array();
    $candidateStancesPrep = "SELECT * FROM theeducatedvote.candidates_issues_stances WHERE basic_info_id_fk = ? LIMIT 1;";
    if($candidateStancesPrep = $conn->prepare($candidateStancesPrep))
    {
        $candidateStancesPrep->bind_param('i', $basic_info_id);
        $candidateStancesPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $candidateStancesPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params, referencing them to $stancesArray
        {
            $params[] = &$stancesArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($candidateStancesPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        //the following line gets a SINGLE array with column names as keys. It actually dumps the info into $params (using numbered indexes), but 
        //because $stancesArray has the same references, it also filled $stancesArray, using associative keys
        $candidateStancesPrep->fetch();
        //var_dump_pre($stancesArray);
        
        $candidateStancesPrep->close();
        return $stancesArray;
    }
    else return 'error in Stances Fetch';
}

//returns an array with the issues as keys and issue importances (as tinyints) as values
function getCandidateIssuesImportances($basic_info_id)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $importancesArray = array();
    $candidateImportancesPrep = "SELECT * FROM theeducatedvote.candidates_issues_importances WHERE basic_info_id_fk = ? LIMIT 1;";
    if($candidateImportancesPrep = $conn->prepare($candidateImportancesPrep))
    {
        $candidateImportancesPrep->bind_param('i', $basic_info_id);
        $candidateImportancesPrep->execute();

        $params = array(); //to hold all the column names
        $meta = $candidateImportancesPrep->result_metadata(); //gets column names
        while ($field = $meta->fetch_field()) //puts them in $params, referencing them to $importancesArray
        {
            $params[] = &$importancesArray[$field->name]; //&$whatever is where we want the results of the query to end up 
        }
        call_user_func_array(array($candidateImportancesPrep, 'bind_result'), $params); //binds results to an array, rather an individual variables
        //the following line gets a SINGLE array with column names as keys. It actually dumps the info into $params (using numbered indexes), but 
        //because $importancesArray has the same references, it also filled $importancesArray, using associative keys
        $candidateImportancesPrep->fetch();
        //var_dump_pre($importancesArray);
        
        $candidateImportancesPrep->close();
        return $importancesArray;
    }
    else return 'error in Importances Fetch';
}

//checks to see if the page we're going to exists, and creates it if it doesn't
function checkAndRedirect($basic_info_id)
{
    //debug_to_console('hit check and redirect');
    $consts = Constants::getInstance();

    $docRoot = $consts->getDocumentRoot();
    $serverRoot = $consts->getServerRoot();
    if ($_GET['office'] != 'President') //if we're dealing with anyone who's not running for president, we need their state
    {
        $page_dir = $docRoot . $_GET['year'] .'/'. $_GET['state'] .'/'. $_GET['office']; //directory for page
        $page_link = $serverRoot . $_GET['year'] .'/'. $_GET['state'] .'/'. $_GET['office'] .'/'. $_GET['name'] .'.php';
    }
    else //if it is a presidential candidate, we don't care about the state
    {
        $page_dir = $docRoot . $_GET['year'] .'/'. $_GET['office']; //directory for page
        $page_link = $serverRoot . $_GET['year'] .'/'. $_GET['office'] .'/'. $_GET['name'] .'.php';
    }
    $page_file = $page_dir .'/'. $_GET['name'] .'.php'; //location of page

    if(file_exists($page_file)) //if the page exists
    {
        redirectToPage($page_link); //redirect to it
    }
    else //if not 
    {
        createPage($page_dir, $page_file, $basic_info_id); //make it first (should happen very very rarely
        redirectToPage($page_link); //redirect to it
    }
}

//generates the raw php and html that will make up the page 
function createPage($page_dir, $page_file, $basic_info_id)
{
    //debug_to_console('hit create page');
    $consts = Constants::getInstance();

    checkAndMakeDirectories($page_dir); //make sure there is a place for this file to go 

    if($_GET['office'] == 'President') $officeName = 'President'; //for title tag 
    else $officeName = $_GET['office'] .' in '. $_GET['state']; //for title tag

    $docRoot = $consts->getDocumentRoot();

    //the php that will go in this file that is separate from the html
    $php = '
    ';
    
    $pageStyleHref = $docRoot .'Styles/CandidatePageStyle.css';
    $uniStyleHref = $docRoot .'Styles/TheEducatedVoteUniversalStyle.css';
    $jqueryHref = $docRoot .'Scripts/jquery-1.11.2.js';
    $jsCookieHref = $docRoot .'Scripts/js.cookie.js';
    $pageScriptHref = $docRoot .'Scripts/CandidatePageScript.js';
    $uniScriptHref = $docRoot .'Scripts/TheEducatedVoteUniversalScript.js';
    $iconHref = $docRoot .'Photos/DinosaurPoliticalParty.png';
    $showMoreButtonHref = $docRoot .'Photos/ShowMoreButton.png';
    
    //html starting with <!doctype html> and going until </head>
    $head = '
<!doctype html>
<html>
<head>
    <title>'. $_GET['name'] .' - The Educated Vote </title>
    <link rel="stylesheet" type="text/css" href="'. $pageStyleHref .'">
    <link rel = "stylesheet" type = "text/css" href = "'. $uniStyleHref .'">
    <script src="'. $jqueryHref .'"></script>
    <script src = "'. $jsCookieHref .'"></script>
    <script src = "'. $pageScriptHref .'"></script>
    <script src = "'. $uniScriptHref .'"></script>
    <link rel="shortcut icon" href="'. $iconHref .'"> 

    <meta charset="UTF-8">
    <meta name = "description" content = "'. $_GET['name'] .', Candidate for '. $officeName .', 2016 - The Educated Vote"> 
    <meta name = "author" content = "Kevin Gibbons"> 
</head>
    ';
    
    //html starting with <body> and going until </html>
    $body= '
<body>
    <?php include "'. $docRoot .'TheEducatedVoteHeader.php"?>
    <div class="mainContainer">
        <?php include "'. $docRoot .'TheEducatedVoteSidebar.php"?>
    ';
    
    $body .= getCandidateInfoHTML($basic_info_id);
    
    $body .='
    <div class="issuesOuterContainer">
            <div class="issuesInnerContainer">
            </div>
            <img src="'. $showMoreButtonHref.'" alt="Show More">
        </div>
    </div>
    <?php include "'. $docRoot .'TheEducatedVoteFooter.php"?>
</body>
</html>';

    file_put_contents($page_file, ''.$php.$head.$body.'');
}

//sends us to the page after we've done everything else 
function redirectToPage($page_link)
{
    //debug_to_console('made it to the redirect for' . $page_file);
    header($page_link);
    echo 'You\'ll be redirected in about 5 secs. If not, click <a href="'. $page_link .'">here</a>.';
}

//gets candidate id
function getCandidateId($name, $office)
{
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $id = 0; //instatiate
    $candidateIdPrep = "SELECT id FROM theeducatedvote.candidates_basic_info WHERE name = ? AND office = ? LIMIT 1;"; //MySQL statement prepared, ? will be replaced with variables
    if($candidateIdPrep = $conn->prepare($candidateIdPrep)) //if the prepare connection works
    {
        $candidateIdPrep->bind_param('ss', $name, $office); //replaces ? with s(trings) $var and $var
        $candidateIdPrep->execute(); //does the statement
        $candidateIdPrep->bind_result($id); //going to put result in $id
        $candidateIdPrep->fetch(); //puts result in $id
        $candidateIdPrep->close(); //done with this prepared statement
        return $id; //return the id
    }
    else return 'error in basic info id fetch'; //if not, return error
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
