<?php
session_start();
include '../../TheEducatedVoteConstants.php'; //@@@@@ Yah no
function orderIssues()
{
    $consts = Constants::getInstance();
    $own_cache_file = $consts::getDocumentRoot() . 'Caches/2016/President/Bernie Sanders.txt'; //@@@@@ pass from previous page or dynamic
    $issuesHtml = json_decode(file_get_contents($own_cache_file), true);
    $issuesOutput = '';
    if(isset($_SESSION['issuesImportances'])) //should be array of importances for the issues
    {
        $issuesImportances = $_SESSION['issuesImportances']; //should already be in order
        foreach($issuesImportances as $issue => $import)
        {
            $issuesOutput .= $issuesHtml[$issue];
        }
    }
    else
    {
        foreach($issuesHtml as $issue => $html)
        {
            $issuesOutput .= $html;
        }
    }
    return $issuesOutput;
}
?>


    
<!doctype html>
<html>
<head>
    <title>Bernie Sanders - The Educated Vote </title>
    <link rel="stylesheet" type="text/css" href="C://Users/Kevin/PhpstormProjects/TheEducatedVote/Styles/CandidatePageStyle.css">
    <link rel = "stylesheet" type = "text/css" href = "C://Users/Kevin/PhpstormProjects/TheEducatedVote/Styles/TheEducatedVoteUniversalStyle.css">
    <script src="C://Users/Kevin/PhpstormProjects/TheEducatedVote/Scripts/jquery-1.11.2.js"></script>
    <script src = "C://Users/Kevin/PhpstormProjects/TheEducatedVote/Scripts/js.cookie.js"></script>
    <script src = "C://Users/Kevin/PhpstormProjects/TheEducatedVote/Scripts/CandidatePageScript.js"></script>
    <script src = "C://Users/Kevin/PhpstormProjects/TheEducatedVote/Scripts/TheEducatedVoteUniversalScript.js"></script>
    <link rel="shortcut icon" href="C://Users/Kevin/PhpstormProjects/TheEducatedVote/Photos/DinosaurPoliticalParty.png"> 

    <meta charset="UTF-8">
    <meta name = "description" content = "Bernie Sanders, Candidate for President, 2016 - The Educated Vote"> 
    <meta name = "author" content = "Kevin Gibbons"> 
</head>
    
<body>
    <?php include "C:/Users/Kevin/PhpstormProjects/TheEducatedVote/TheEducatedVoteHeader.php"?>
    <div class="mainContainer">
        <?php include "C:/Users/Kevin/PhpstormProjects/TheEducatedVote/TheEducatedVoteSidebar.php"?>
    
        <div class="basicInfoContainer">
            <div class="candidateNameContainer">
                <h1 class="candidateName">Bernie Sanders</h1>
            </div>
            <div class="basicInfoCandidatePictureContainer">
                <img src="C://Users/Kevin/PhpstormProjects/TheEducatedVote/Photos/Bernie SandersPresident2016" alt="Photo of Bernie Sanders">
            </div>
            <div class="basicInfoBioContainer">
                <p class="bio">Bernie Sanders is from Vermont and is cul</p>
            </div>
            <div class="basicInfoPartyContainer">
                <p class="party">Democratic</p>
            </div>
            <div class="basicInfoElectionDetailsContainer">
                <p class="electionDetails">Federal President</p>
                <p class="electionDetails"> </p>
            </div>
        </div>
    <div class="heldOfficesOuterContainer">
            <div class="heldOfficesInnerContainer">
                <p class="officeDetails">Federal Senator in Vermont</p>
                <p class="officeDetails">From 1998-06-18 to 2015-06-15</p>
                <div class="comitteeOuterContainer>"
        
                    <div class="committeeInnerContainer">
                        <p class="committeeDetails">Committee for dank memes</p>
                        <p class="committeeDetails">Chairman</p>
                        <p class="committeeDetails">From 2012-06-14 to 2015-06-18</p>
                    </div>
                
                    <div class="committeeInnerContainer">
                        <p class="committeeDetails">Govanator</p>
                        <p class="committeeDetails">Haha yah</p>
                        <p class="committeeDetails">From 2001-06-20 to 2013-10-24</p>
                    </div>
                </div>
            <div class="heldOfficesInnerContainer">
                <p class="officeDetails">Mayor of Kenja in Kenaj</p>
                <p class="officeDetails">From 2013-06-10 to 2014-08-06</p>
                <div class="comitteeOuterContainer>"
        
                    <div class="committeeInnerContainer">
                        <p class="committeeDetails">Boss man</p>
                        <p class="committeeDetails">The Boss</p>
                        <p class="committeeDetails">From 2016-06-12 to 2016-06-23</p>
                    </div>
                
                    <div class="committeeInnerContainer">
                        <p class="committeeDetails">Master of the dank memes</p>
                        <p class="committeeDetails">admin</p>
                        <p class="committeeDetails">From 2016-06-05 to 2016-06-17</p>
                    </div>
                </div></div>
        <div class="contactInfoContainer">
            <div class="contactInfoNonSocialMedia">
                <p class="contactInfo">Email: berniesanders@gmail.com</p>
                <p class="contactInfo">Website: berniesanders.com</p>
            </div>
            <div class="contactInfoSocialMediaContainer">
                <p class="socialMediaLink"><a href="facebook for bernie">Facebook</a></p>
                <p class="socialMediaLink"><a href="twitter for berni">Twitter</a></p>
                <p class="socialMediaLink"><a href="youtube for bernie">YouTube</a></p>
                <p class="socialMediaLink"><a href="google plus for bernie">Google Plus</a></p>
                <p class="socialMediaLink"><a href="insta for bernie">Instagram</a></p>
                <p class="socialMediaLink"><a href="flickr for bernie">Flickr</a></p>
                <p class="socialMediaLink"><a href="tumblr for bernie">Tumblr</a></p>
            </div>
            <div class="officeOuterContainer">
        
        <div class="officeInnerContainer">
            <p class="officePhone">Phone:1847 Fax: </p>
            <div class="officeAddressContainer">
                <p class="officeAddress">705 Springfield</p>
                <p class="officeAddress">Apt 2</p>
                <p class="officeAddress">Urbana IL 61801</p>
            </div>
        </div>
        
        <div class="officeInnerContainer">
            <p class="officePhone">Phone:2847 Fax: </p>
            <div class="officeAddressContainer">
                <p class="officeAddress">304 Daniel</p>
                <p class="officeAddress">Apt 204</p>
                <p class="officeAddress">Champaign IL 61820</p>
            </div>
        </div>
        
        <div class="officeInnerContainer">
            <p class="officePhone">Phone:3847 Fax: 69</p>
            <div class="officeAddressContainer">
                <p class="officeAddress">5701 Highland Drive</p>
                <p class="officeAddress"></p>
                <p class="officeAddress">Palatine IL 60067</p>
            </div>
        </div>
        
        </div>
    </div>
    <div class="issuesOuterContainer">
            <div class="issuesInnerContainer">
                <?php echo orderIssues()?>
            </div>
            <img src="C://Users/Kevin/PhpstormProjects/TheEducatedVote/Photos/ShowMoreButton.png" alt="Show More">
        </div>
    </div>
    <?php include "C:/Users/Kevin/PhpstormProjects/TheEducatedVote/TheEducatedVoteFooter.php"?>
</body>
</html>