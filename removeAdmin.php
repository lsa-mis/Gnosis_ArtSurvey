<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

    $recToRemove = test_input($_POST["deleteMe"]);
    if ($recToRemove != 1) {
    $sqlSelect = <<<SQL
			DELETE FROM tbl_admmgr
      WHERE id = $recToRemove
SQL;

if (!$result = $db->query($sqlSelect)) {
    db_fatal_error("data admin maintenance issue", $db->error);
} else {
    echo $recToRemove;
}
$db->close();
} else {
	db_fatal_error("$login_name is trying to delete the rsmoke Master account", $db->error);
}
