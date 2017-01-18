<?php

error_reporting(0);

session_start();

// ClearDB
// @link https://devcenter.heroku.com/articles/cleardb
/*
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);*/

//$conn = new mysqli($server, $username, $password, $db);
//$link = mysqli_connect($server, $username, $password, $db);
// End ClearDB

$link = mysqli_connect("localhost", "root", "", "twitter");

if (mysqli_connect_errno()) {

    print_r(mysqli_connect_error());
    exit();

}

if ($_GET['function'] == "logout") {

    session_unset();

}

// @link http://stackoverflow.com/questions/18685/how-to-display-12-minutes-ago-etc-in-a-php-webpage
function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'min'),
        array(1 , 'sec')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}


function displayTweets($type) {

    global $link;

    if ($type == 'public') {

        $whereClause = "";

    } else if ($type == 'isFollowing') {

        $query = "SELECT * FROM isfollowing WHERE follower = "
            . mysqli_real_escape_string($link, $_SESSION['id']);

        $result = mysqli_query($link, $query);

        $whereClause = "";

        while ($row = mysqli_fetch_assoc($result)) {

            if ($whereClause == "") $whereClause = "WHERE";

            else $whereClause .= " OR";
            $whereClause .= " userid = " . $row['isFollowing'];

        }

    } else if ($type == 'yourtweets') {

        $whereClause = "WHERE userid = " . mysqli_real_escape_string($link, $_SESSION['id']);

    } else if ($type == 'search') {

        echo "<p>Showing results for '" . mysqli_real_escape_string($link, $_GET['q']) . "':</p>";

        $whereClause = "WHERE tweet LIKE '%" . mysqli_real_escape_string($link, $_GET['q']) . "%'";

    } else if (is_numeric($type)) {


        $userQuery = "SELECT * FROM users WHERE id = " . mysqli_real_escape_string($link, $type) . " LIMIT 1";

        $userQueryResult = mysqli_query($link, $userQuery);

        $user = mysqli_fetch_assoc($userQueryResult);

        echo "<h2>" . mysqli_real_escape_string($link, $user['email']) . "'s Tweets</h2>";


        $whereClause = "WHERE userid = " . mysqli_real_escape_string($link, $type);

    }

    $query = "SELECT * FROM tweets " . $whereClause . " ORDER BY `datetime` DESC LIMIT 10";

    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) == 0) {

        echo "There are no tweets to display";

    } else {

        while ($row = mysqli_fetch_assoc($result)) {

            $userQuery = "SELECT * FROM users WHERE id = " . mysqli_real_escape_string($link, $row['userid']) . " LIMIT 1";

            $userQueryResult = mysqli_query($link, $userQuery);

            $user = mysqli_fetch_assoc($userQueryResult);

            echo "<div class='tweet'><p><a href='?page=publicprofiles&userid=" . $user['id'] . "'>" . $user['email'] . " </a><span class='time'>" . time_since(time() - strtotime($row['datetime'])) . " ago</span>:</p>";

            echo "<p>" . $row['tweet'] . "</p>";

            echo "<p><a class='toggleFollow' data-userId='" . $row['userid'] . "'>";

            $isFollowingQuery = "SELECT * FROM isfollowing WHERE follower = "
                . mysqli_real_escape_string($link, $_SESSION['id']) . " AND isFollowing = " . mysqli_real_escape_string($link, $row['userid']) . " LIMIT 1";

            $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);

            if (mysqli_num_rows($isFollowingQueryResult) > 0) {

                echo "Unfollow";

            } else {

                echo "Follow";

            }

            echo "</a></p></div>";

        }

    }

}

// @link http://www.w3schools.com/php/php_forms.asp
function displaySearch() {

    echo '<form class="form-inline">
    <div class="form-group">
    <input type ="hidden" name="page" value="search">
    <input type="text" name="q" class="form-control" id="search" placeholder="Search">
    </div>
    <button class="btn btn-primary">Search Tweets</button>
    </form>';

}


function displayTweetBox() {

    if ($_SESSION['id'] > 0) {

        echo '<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
        <div id="tweetFail" class="alert alert-danger">Your tweet was posted successfully.</div>
        <div class="form">
        <div class="form-group">
        <textarea class="form-control" id="tweetContent"></textarea>
        </div>
        <button class="btn btn-primary" id="postTweetButton">Post Tweet</button>
        </div>';

    }

}


function displayUsers() {

    global $link;

    $query = "SELECT * FROM users LIMIT 10";

    $result = mysqli_query($link, $query);

    // NOTE Search for a pagination system
    while ($row = mysqli_fetch_assoc($result)) {

        echo "<p><a href='?page=publicprofiles&userid=" . $row['id'] . "'>" . $row['email'] . "</a></p>";

    }

}

// Debug PHP in the console.
/*function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}*/
