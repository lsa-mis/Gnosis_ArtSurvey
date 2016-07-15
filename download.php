<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");


// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="ArtSurvey.csv"');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Record_ID', 'UniqName', 'Dept_Contact', 'Department', 'Description', 'Building_Location', 'Room_Location', 'Acquired', 'Value', 'How_Value_Was_Determined', 'Security', 'If_Other_Value_Determination', 'Entered_On'));


$sortType = $_SESSION['sortBy'];

if ($userMaster) {
            $sqlSelect = <<<SQL
            SELECT id, username, deptContact, department, description, locationBldg, locationRoom, dateAcquired, value, valDetermined, protection, valDeterminedOther, timestamp
            FROM tbl_responses
            WHERE deleted = 0
            ORDER BY $sortType
SQL;
} elseif ($userDeptAdmin) {
    $deptString = implode(",", $deptList);
        $sqlSelect = <<<SQL
            SELECT id, username, deptContact, department, description, locationBldg, locationRoom, dateAcquired, value, valDetermined, protection, valDeterminedOther, timestamp
            FROM tbl_responses
            WHERE ((department IN ($deptString)) OR username = "$login_name") AND deleted = 0
            ORDER BY $sortType
SQL;
} else {
    $sqlSelect = <<<SQL
      SELECT id, username, deptContact, department, description, locationBldg, locationRoom, dateAcquired, value, valDetermined, protection, valDeterminedOther, timestamp
      FROM tbl_responses
      WHERE username = "$login_name" AND deleted = 0
      ORDER BY $sortType
SQL;

}
if (!$result = $db->query($sqlSelect)) {
            db_fatal_error("data select issue", $db->error, $sqlSelect, $login_name);
            exit;
}

// loop over the rows, outputting them
while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
}



// // fetch the data
// $rows = mysql_query('SELECT field1,field2,field3 FROM table');

// // loop over the rows, outputting them
// while ($row = mysql_fetch_assoc($rows)) {
//     fputcsv($output, $row);
// }
