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
    <div class="indexLeftContainer">
        <h2 style="text-align:center"> Presidential Candidates </h2>
        <div class="indexPresidentialCandidateContainer">
            <a href="ConstructUpdateRedirect.php?name=Bernie Sanders&office=President&year=2016">
                <img src="Photos/Bernie SandersPresident2016.jpg" alt="Democratic nominee headshot">
            </a>
            <h1> Bernie Sanders </h1>
            <h2> Democrat </h2>
            <h3><a href="2016/President/Bernie%20Sanders.php">Go to Bernie Page</a></h3>
        </div>

        <div class="indexPresidentialCandidateContainer">
            <a href="ConstructUpdateRedirect.php">
                <img src="Photos/DonaldTrumpHeadshot.jpg" alt="Republican nominee headshot">
            </a>
            <h1> Donald Trump </h1>
            <h2> Republican </h2>
        </div>

        <div class="indexPresidentialCandidateContainer">
            <a href="ConstructUpdateRedirect.php">
                <img src="Photos/GaryJohnsonHeadshot.png" alt="Libertarian nominee headshot">
            </a>
            <h1> Gary Johnson </h1>
            <h2> Independent </h2>
        </div>

        <div class="indexPresidentialCandidateContainer">
            <a href="ConstructUpdateRedirect.php">
                <img src="Photos/RalphNaderHeadshot.jpg" alt="Green nominee headshot">
            </a>
            <h1> Ralph Nadar </h1>
            <h2> Green </h2>
        </div>
    </div>

    <a href="PatronHome.php"><p>Patron Home</p></a>
    <a href="TheIssuesExplained.php"><p>The Issues Explained</p></a>
    <a href="SubmitInfo.php"><p>Submit Info</p></a>
    <a href="SubmitNews.php"><p>Submit News</p></a>

    <!--right third of screen-->
    <div class="indexRightContainer">
        <div class="loginContainer">
            <form method="post" action="@@@@@need somewhere to send this@@@@@" enctype = "multipart/form-data">
                <label for="loginEntry">Username or Email
                    <input type="text" name="loginEntry" id="loginEntry">
                </label>
                <label for="passwordEntry">Password
                    <input type="text" name="passwordEntry" id="passwordEntry">
                </label>
                <label for="keepLoggedIn">Keep me logged in
                    <input type="checkbox" name="keepLoggedIn" value="keepLoggedIn"><br>
                </label>
                <input type="submit" name="Login" value="submitLogin">

                <p>No account? Click here to make one</p> <!-- changes right side to create account -->
            </form>
        </div>

        <div class= "createAccountContainer">
            <form method="post" action="CreatePatron.php" enctype = "multipart/form-data">
                <label for="name">Full Name:
                    <input type="text" name="name" id="name">
                </label>

                <label for="email">Email Address:
                    <input type="text" name="email" id="email">
                </label>

                <label for="username">Username:
                    <input type="text" name="username" id="Username">
                </label>

                <label for="password">Password:
                    <input type="text" name="password" id="password">
                </label>

                <label for="passwordConfirm">Confirm Password:
                    <input type="text" name="passwordConfirm" id="passwordConfirm">
                </label>

                <label for="addressLineOne">Address (Line One):
                    <img src="Photos/QuestionMark.png" alt="Question Mark" class="informationButton" id="whyAddress">
                    <input type="text" name="addressLineOne" id="addressLineOne">
                </label>

                <label for="addressLineTwo">Address (Line Two):
                    <input type="text" name="addressLineTwo" id="addressLineTwo">
                </label>

                <label for="city">City or Town:
                    <input type="text" name="city" id="city">
                </label>

                <label for="state">State:
                    <input type="text" name="state" id="state">
                </label>

                <label for="zipcode">Zipcode (6 digits):
                    <input type="text" name="zipcode" id="zipcode">
                </label>

                <input type="submit" name="submitNewPatron" value="Create New Account">
            </form>
        </div>
    </div>
</body>
</html>