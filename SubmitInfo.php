<?php
include 'TheEducatedVoteConstants.php';

$consts = Constants::getInstance();
$docRoot = $consts->getDocumentRoot();

?>

<!doctype html>
<html>
<head>
    <title>Submit Info - The Educated Vote</title>
    <link rel="stylesheet" type="text/css" href="Styles/TheEducatedVoteUniversalStyle.css">
    <link rel="stylesheet" type="text/css" href="Styles/submitInfoStyle.sass"> <!--@@@@@ supposedly this will not work, and I'll need to convert my .sass docs to .css ones. lame. -->
    <script src="Scripts/jquery-1.11.2.js"></script>
    <script src="Scripts/js.cookie.js"></script>
    <script src="Scripts/SubmitInfoScript.js"></script>
    <script src="Scripts/TheEducatedVoteUniversalScript.js"></script>
    <link rel="shortcut icon" href="Photos/DinosaurPoliticalParty.jpg">

    <meta charset="UTF-8">
    <meta name="description" content="Submit Info Page, The Educated Vote"> <!-- description of page -->
    <meta name="author" content="Kevin Gibbons"> <!-- who wrote it -->
</head>
<body>
<?php include 'TheEducatedVoteHeader.php'?>
<?php include 'TheEducatedVoteSidebar.php'?>
<div class="submissionPageHeader">
    <p class="submissionRule">Thanks for taking the time to submit new information for the site! Please follow the submission guidelines - if they aren't followed, your submission won't even be seen by our reviewers, which would be a crying shame.</p>
    <div class="submissionRulesContainer">
        <h2 class="rulesHeader">The Rules:</h2>
        <p class="submissionRule">1. You must include a source link to where you found the information (just copy and paste the website url where you found it).</p>
        <p class="submissionRule">2. Make sure to select which issue your information falls under.</p>
        <p class="submissionRule">3. You are entitled to your opinion, but it has no place here. If you aren't copying and pasting, make sure your submission has a neutral tone and only contains facts.</p>
        <p class="submissionRule">4. Relatedly, only factual submission are allowed. Just because "Obama is a muslim" or "Ted Cruz is the Zodiac Killer" may be things you believe, doesn't make them true.</p>
        <p class="submissionRule">5. If your submission isn't relevant to any of the issues, then it probably isn't relevant to the election. If you wholeheartedly believe your information is relevant despite it not fitting into a category, submit under a category that it at least relates to.</p>
        <p class="submissionRule">6. Keep things as short and sweet as possible. Don't coy-paste entire articles. Don't write 1500 words.</p>
        <p class="submissionRule">7. We reserve the rights to only publish some submitted content (again in the spirit of keeping things succinct) and edit submissions to adopt an even more neutral tone.</p>
        <p class="submissionRule">8. If you have information from a reliable non-internet source (i.e. you work for a candidate), we'd love to hear about it (especially if you work for a candidate). Rather than filling out the form, send us an email at <a href="mailto:TheEducatedVote@gmail.com?Subject=Candidate Info">Send Mail</a>
    </div>
</div>
<div class="submissionContainer">
    <form method="post" action="SubmittedInfo.php" enctype="multipart/form-data" autocomplete="on">
        <div class="singleIssueSubmissionContainer" id="singleIssueSubmissionContainer1">
            <div class="topicContainer">
                <p class="submissionAreaTitle">Choose an issue or Topic</p>
                <label for="topic1">
                    <select name="topic1" required>
                        <option value="empty">-</option>
                        <option value="abortion">Abortion</option>
                        <option value="affirmative_action">Affirmative Action</option>
                        <option value="campaign_finance_laws">Campaign Finance Laws</option>
                        <option value="corruption">Corruption</option>
                    </select>
                </label>
            </div>
            <div class="contentContainer">
                <p class="submissionAreaTitle">Information</p>
                <label for="contentTextarea1">
                    <textarea rows="10" cols="60" name="contentTextarea1" placeholder="Copy-Paste or type submission here. Put sources in the next text box!" required></textarea>
                </label>
            </div>
            <div class="sourceContainer">
                <p class="submissionAreaTitle">Sources</p>
                <label for="sourceTextarea1">
                    <textarea rows="5" cols="60" name="sourceTextarea1" placeholder="Sources can be copy-pasted here!" required></textarea>
                </label>
            </div>
        </div>
        <div class="addMoreInfoButtonContainer">
            <img src="Photos/moreInfoButton.png" alt="more info" id="addMoreInfoButton">
        </div>
        <input type="submit" name="submitInfo" value="Submit">
    </form>
</div>
<?php include 'TheEducatedVoteFooter.php'?>
</body>
</html>