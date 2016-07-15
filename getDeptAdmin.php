<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

if ($userMaster) {
            $sqlSelect = <<<SQL
            SELECT id, uniqname, dept
            FROM tbl_admmgr
            ORDER BY dept
SQL;
} elseif ($userDeptAdmin) {
    $deptString = implode(",", $deptList);
        $sqlSelect = <<<SQL
            SELECT id, uniqname, dept
            FROM tbl_admmgr
            WHERE (dept IN ($deptString)) AND (usertype <> 'Master')
            ORDER BY uniqname
SQL;
}
if (!$result = $db->query($sqlSelect)) {
            db_fatal_error("data select issue", $db->error, $sqlSelect, $login_name);
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<tr id=" . $row["id"] . ">";
        echo "<td><button id='delRecord' data-toggle='tooltip' data-placement='bottom' title='Delete this record' class='btn btn-xs btn-danger' value='" . $row["id"] . "'><span class='glyphicon glyphicon-remove'></span></button></td><td>" . $row["uniqname"] . "</td><td>" . $row["dept"] . "</td>";
        echo "</tr>";
    }
}
          $db->close();
