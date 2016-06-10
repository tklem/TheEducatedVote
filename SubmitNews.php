<?php
include 'TheEducatedVoteConstants.php';

$consts = Constants::getInstance();
$docRoot = $consts->getDocumentRoot();

?>

<!doctype html>
<html>
<head>
    <title>Submit News - The Educated Vote</title>
    <link rel="stylesheet" type="text/css" href="Styles/TheEducatedVoteUniversalStyle.css">
    <link rel="stylesheet" type="text/css" href="Styles/submitInfoStyle.sass"> <!--@@@@@ supposedly this will not work, and I'll need to convert my .sass docs to .css ones. lame. -->
    <script src="Scripts/jquery-1.11.2.js"></script>
    <script src="Scripts/js.cookie.js"></script>
    <script src="Scripts/SubmitNewsScript.js"></script>
    <script src="Scripts/TheEducatedVoteUniversalScript.js"></script>
    <link rel="shortcut icon" href="Photos/DinosaurPoliticalParty.jpg">

    <meta charset="UTF-8">
    <meta name="description" content="Submit News Page, The Educated Vote"> <!-- description of page -->
    <meta name="author" content="Kevin Gibbons"> <!-- who wrote it -->
</head>
<body>
<?php include 'TheEducatedVoteHeader.php'?>
<?php include 'TheEducatedVoteSidebar.php'?>
<div class="submissionPageHeader">
    <p class="submissionRule">Thanks for taking the time to submit @@@@@NEWS for the site! Please follow the submission guidelines - if they aren't followed, your submission won't even be seen by our reviewers, which would be a crying shame.</p>
    <div class="submissionRulesContainer">
        <h2 class="rulesHeader">The Rules:</h2>
        <p class="submissionRule">1. You must include a source link to where you found the information (just copy and paste the website url where you found it).</p>
        <p class="submissionRule">2. Make sure to select which issue your information falls under.</p>
    </div>
</div>
<div class="submissionContainer">
    <form method="post" action="SubmittedNews.php" enctype="multipart/form-data" autocomplete="on">
        <div class="singleNewsSubmissionContainer">
            <div class="basicsOuterContainer">
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">Article Title</p>
                    <label for="title">
                        <input type="text" name="title" placeholder="Title of the Article"/>
                    </label>
                </div>
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">Article Author(s)</p>
                    <label for="author">
                        <input type="text" name="author" placeholder="Jane Smith, Mike Johnson, and Monty Python"/>
                    </label>
                </div>
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">News Outlet</p>
                    <label for="outlet">
                        <input type="text" name="outlet" placeholder="CNN, BBC, Reuters, other?"/>
                    </label>
                </div>
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">Date Published</p>
                    <label for="date">
                        <input type="text" name="published" placeholder="dd/mm/yyyy"/>
                        <input type="checkbox" value="today" name="todayCheck"/> Today
                    </label>
                </div>
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">Link</p>
                    <label for="link">
                        <input type="text" name="link" placeholder="copy-paste url here"/>
                    </label>
                </div>
                <div class="basicInnerContainer">
                    <p class="submissionAreaTitle">Media Types</p>
                    <label for="mediaTypes">
                        <input type="checkbox" value="text" name="media[]"/> Text
                        <input type="checkbox" value="video" name="media[]"/> Video
                        <input type="checkbox" value="audio" name="media[]"/> Audio Recording
                        <input type="checkbox" value="gallery" name="media[]"/> Photo Gallery
                    </label>
                </div>
            </div>
            <div class="textareaContainer">
                <p class="submissionAreaTitle">Primary People</p>
                <label for="secondaries">
                    <textarea rows="7" cols="30" name="primaries" placeholder="list names like this: John Smith, Bill Nye, Jane Doe" required></textarea> <!--@@@@@ &#10; does not work on all browsers@-->
                </label>
            </div>
            <div class="textareaContainer">
                <p class="submissionAreaTitle">Secondary People</p>
                <label for="primaries">
                    <textarea rows="7" cols="30" name="secondaries" placeholder="list names like this: John Smith,Bill Nye, Jane Doe" required></textarea>
                </label>
            </div>
        </div>
        <input type="submit" name="submitInfo" value="Submit">
    </form>
</div>
<?php include 'TheEducatedVoteFooter.php'?>
</body>
</html>