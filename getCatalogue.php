<?php
  session_start();
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

$sortType = $_SESSION['sortBy'];
if ($userMaster) {
            $sqlSelect = <<<SQL
            SELECT *
            FROM tbl_responses
            WHERE deleted = 0
            ORDER BY $sortType
SQL;
} elseif ($userDeptAdmin) {
    $deptString = implode(",", $deptList);
        $sqlSelect = <<<SQL
            SELECT *
            FROM tbl_responses
            WHERE ((department IN ($deptString)) OR username = "$login_name") AND deleted = 0
            ORDER BY $sortType
SQL;
} else {
    $sqlSelect = <<<SQL
			SELECT *
			FROM tbl_responses
			WHERE username = "$login_name" AND deleted = 0
			ORDER BY $sortType
SQL;

}
if (!$result = $db->query($sqlSelect)) {
            db_fatal_error("data select issue", $db->error);
} else {
    while ($row = $result->fetch_assoc()) {
        $cleanDate = new DateTime($row["timestamp"]);
        if (strlen($row["imageName"]) < 8) {
            $imageFile = "imagefiles/empty.png";
        } else {
            $imageFile = $row["imageName"];
        }
        echo "<tr id=" . $row["id"] . ">";
        echo "<td><button id='delRecord' data-toggle='tooltip' data-placement='bottom' title='Delete this record' class='btn btn-xs btn-danger' value='" . $row["id"] . "'><span class='glyphicon glyphicon-remove'></span></button><button name='updateMe' id='editRecord' data-toggle='tooltip' data-placement='right' title='Edit this record' class='btn btn-xs btn-success' value='" . $row["id"] . "''><span class='glyphicon glyphicon-pencil'></span></button></td><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["deptContact"] . "</td><td>" . $row["department"] . "</td><td>" . $row["description"];
        echo "</td><td>" . $row["locationBldg"] . "</td><td>" . $row["locationRoom"] . "</td><td>" . $row["dateAcquired"];
        echo  "</td><td>$" . $row["value"] . "</td><td>" . $row["valDetermined"]  . "</td><td>" . $row["valDeterminedOther"] . "</td><td>" . $row["protection"]  . "</td><td><a href='" . $imageFile . "' target='_blank'><img src='" . $imageFile . "' width='30' height='30' class='img-rounded' ></a></td><td>" . $cleanDate->format('m/d/y') . "</td>";
        echo "</tr>";
    }
}
          $db->close();


        // id +
        // username +
        // deptContact * +
        // department * +
        // description *
        // locationBldg * +
        // locationRoom
        // dateAcquired
        // value * +
        // valDetermined *
        // protection
        // timestamp +
        // valDeterminedOther
