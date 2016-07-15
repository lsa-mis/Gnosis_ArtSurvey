<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

    $recToRemove = test_input($_POST["deleteMe"]);

    $sqlSelect = <<<SQL
			UPDATE tbl_responses
			SET deleted = 1
            WHERE id = "$recToRemove"

SQL;
if (!$result = $db->query($sqlSelect)) {
    db_fatal_error("data insert issue", $db->error, $sqlSelect, $login_name);
} else {
                echo $recToRemove;
}
$db->close();
