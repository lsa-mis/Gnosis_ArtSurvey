<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('USERNAME', getenv('db_user', true) ?: getenv('db_user'));
define('PASSWORD', getenv('db_password', true) ?: getenv('db_password'));
define('DATABASE', getenv('db_name', true) ?: getenv('db_name'));
define('HOST', getenv('db_host', true) ?: getenv('db_host'));
define('URL', "");
define('METAAUTHOR', "LSA_MIS_rsmoke");

echo(USERNAME);
exit;
//database connection setup
$db = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

/* check connection */
if ($db->connect_errno) {
    //printf("Connect failed: %s\n", $db->connect_error);
    db_fatal_error($db->connect_error, "Connect failed: [" . $db->connect_errno . "] ");
    exit($user_err_message);
}

//set chatacter set so $db->escape_string is affective
if (!$db->set_charset('utf8')) {
    printf("Error loading character set utf8: %s\n", $db->error);
}

date_default_timezone_set('America/Detroit');

$login_name = $_SERVER['REMOTE_USER'];

// } else {
//     printf("Current character set: %s\n", $db->character_set_name());
// }

// //Error reporting settings
// // Turn off all error reporting
// error_reporting(0);

// // Report simple running errors
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

// // Reporting E_NOTICE can be good too (to report uninitialized
// // variables or catch variable name misspellings ...)
// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// // Report all errors except E_NOTICE
// error_reporting(E_ALL & ~E_NOTICE);

// Report all PHP errors (see changelog)
error_reporting(E_ALL);

// // Report all PHP errors
// error_reporting(-1);

$user_err_message = <<< _END
    <div style='color: #FF0000'>Yipes!!! Somethings very wrong.<br>
    I notified the webmaster and the issue will be corrected as soon as possible.
    </div>
_END;

function db_fatal_error($errorMsg, $msg = "ERROR: ", $queryString = "No queryString available", $current_user = "no login_name provided")
{
//error handler
    error_log(
        "Error with=> $errorMsg :: $msg
        QueryString=> $queryString
        login_name=> $current_user",
        1,
        "rsmoke@umich.edu",
        "From: ArtSurveyWebApp@umich.edu"
    );
}

function non_db_error($errorMsg, $current_user = "no login_name provided")
{
    error_log(
        "Error with=> $errorMsg
        login_name=> $current_user",
        1,
        "rsmoke@umich.edu",
        "From: ArtSurveyWebApp@umich.edu"
    );
}

//Application Specific variables

$userMaster = false;
$userDeptAdmin = false;
$workerbee = false;
$deptList = [];

$sqlCheckUser = <<<SQL
      SELECT dept, userType
      FROM tbl_admmgr
      WHERE uniqname = '$login_name'
SQL;
if (!$result = $db->query($sqlCheckUser)) {
    db_fatal_error("User type query issue", $db->error, $sqlCheckUser, $login_name);
    exit;
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['userType'] === "Master") {
                $userMaster = true;
            }
            if ($row['userType'] === "DeptAdmin") {
                $userDeptAdmin = true;
                array_push($deptList,"'{$row['dept']}'");
            }
            if ($row['userType'] === "Recorder") {
                $workerbee = true;
                array_push($deptList,"'{$row['dept']}'");
            }
        }
    }
}

$siteTitle = "LSA Art Survey";
$deptShtName = "facstaff/budgetandfinance";
$deptLngName = "LSA Budget and Finance";
$deptURL = "http://lsa.umich.edu/soc";
$addressBldg = "LSA Building";
$address2 = "Room 2122 LSA";
$addressStreet = "500 South State Street";
$addressZip = "48109-1382";
$addressEmail = "*";
$addressPhone = "(734) 647-2224";
$addressFax = "*";
