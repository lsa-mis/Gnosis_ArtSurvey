<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

    $recToEdit = test_input($_POST["updateMe"]);

if (isset($_POST['updateRecord'])) {
      $recid = test_input($_POST["recid"]);
      $deptContact = $db->real_escape_string(test_input($_POST["deptContact"]));
      $department = test_input($_POST["department"]);
      $description = $db->real_escape_string(test_input($_POST["description"]));
      $locationBldg = $db->real_escape_string(test_input($_POST["locationBldg"]));
      $locationRoom = $db->real_escape_string(test_input($_POST["locationRoom"]));
      $dateAcquired = test_input($_POST["dateAcquired"]);
      $value = $db->real_escape_string((int)test_input(($_POST["value"])));
      $valDetermined = test_input($_POST["valDetermined"]);
      $valDeterminedOther = $db->real_escape_string(test_input($_POST["valDeterminedOther"]));
      $protection = $db->real_escape_string(test_input($_POST["protection"]));

    if (strlen(basename($_FILES["fileToUpload"]["name"])) > 0) {
            $target_dir = "imagefiles/";
            $target_file = $target_dir . getUTCTime() . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $fileErrMessage = "";
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image

            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
                //echo "Image file is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
        } else {
                $fileErrMessage = $fileErrMessage . " The file selected is not an image.";
                $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $fileErrMessage = $fileErrMessage . " Sorry, image file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 2048000) {
            $fileErrMessage = $fileErrMessage . " Sorry, your image file was too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $fileErrMessage = $fileErrMessage . " Sorry, only JPG, JPEG, PNG & GIF image files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $fileErrMessage = $fileErrMessage . " <br />=>Your image file was not uploaded. Confirm the image file is 2 megabytes or less and one of these types (JPG, JPEG, PNG & GIF), then edit the record and upload the image.";
            $target_file = "empty";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file ". basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                $target_file = "empty";
                $fileErrMessage = $fileErrMessage . "Sorry, there was an error uploading your file.";
            }
        }
    } else {
            $target_file = $_SESSION['imageFileHolder'];
    }


    if (strlen($deptContact) > 0 && strlen($department) > 0 && strlen($description) > 0 && strlen($locationBldg) > 0 && $value > 0 && strlen($valDetermined) > 0) {
            $sqlUpdate = <<<SQL
        UPDATE tbl_responses
        SET
              `deptContact` = '$deptContact',
              `department` = '$department',
              `description` = '$description',
              `locationBldg` = '$locationBldg',
              `locationRoom` = '$locationRoom',
              `dateAcquired` = '$dateAcquired',
              `value` = '$value',
              `valDetermined` = '$valDetermined',
              `valDeterminedOther` = '$valDeterminedOther',
              `protection` = '$protection',
              `imageName` = '$target_file'
        WHERE id = '$recid'

SQL;
        if (!$result = $db->query($sqlUpdate)) {
            db_fatal_error("data insert issue", $db->error);
        } else {
              echo $recToEdit;
        }
            unset($_POST["updateRecord"]);
            $_SESSION['message'] = "<h4 style='color: #00CC00;'>The record for $description has been updated</h4>". $fileErrMessage;
            header("Location: reviewItems.php");
            exit;
    }
} else {
      $sqlSelect = <<<SQL
      SELECT *
      FROM tbl_responses
      WHERE id = "$recToEdit"

SQL;

    if (!$result = $db->query($sqlSelect)) {
        db_fatal_error("data read issue", $db->error);
    }
      $_SESSION['message'] = "<h4 style='color: #FF0066;'>Be sure to fill in all the red outlined fields</h4>";

    while ($row = $result->fetch_assoc()) {
          $deptContact = $row["deptContact"];
          $department = $row["department"];
          $description = $row["description"];
          $locationBldg = $row["locationBldg"];
          $locationRoom = $row["locationRoom"];
          $dateAcquired = $row["dateAcquired"];
          $value = $row["value"];
          $valDetermined = $row["valDetermined"];
          $valDeterminedOther = $row["valDeterminedOther"];
          $protection = $row["protection"];
          $imageFile = $row["imageName"];
          $_SESSION['imageFileHolder'] = $imageFile;
    }
}
        // id
        // username
        // deptContact *
        // department *
        // description *
        // locationBldg *
        // locationRoom
        // dateAcquired
        // value *
        // valDetermined *
        // protection
        // timestamp

?>



<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo $siteTitle; ?></title>
  <meta name="description" content="<?php echo $siteTitle; ?>">
  <meta name="rsmoke" content="LSA_MIS">

  <link rel="shortcut icon" href="ico/favicon.ico">

  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css">
  <link rel="stylesheet" href="css/bootstrap-formhelpers.min.css" type="text/css">
  <link rel="stylesheet" type="text/css" href="css/myStyles.css">

  <!--[if lt IE 9]>
  <script src="http://html5shiv-printshiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
   <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><?php echo $siteTitle ?></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li><a href="index.php">Home</a></li>
          <li class="active"><a href="reviewItems.php">Catalogue</a></li>
          <?php if ($userMaster || $userDeptAdmin) {
          echo '<li><a href="adminmanager.php">Manage Access</a></li>';
          } ?>
        </ul>
        <div class="navbar-right">
        <span style="color:#eee;"><small>You are logged in as <?php echo $login_name; ?></small></span><br>
          <form class="navbar-form" role="logout" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <button type="logout" name="logout" class="btn btn-default btn-xs">LogOut</button>
          </form>
        </div>
      </div><!--/.nav-collapse -->
    </div>
  </div>



  <div class="container">
    <div class="jumbotron">
      <div class="centerfy"><img src="img/banner.png" class="img-responsive" alt="LSA Logo" /></div>
            <h3>LSA Art Survey - Collections Management</h3>
    </div>
  </div>
  <div class="container">
    <div class="col-xs-7 col-xs-offset-2">
      <div id="notify"><?php echo $_SESSION['message'];  ?></div>
  <!-- Entry form, if existing record was clicked populate with filled in fields from DB -->
      <form id="recordForm" name="recordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
      <input type="hidden"  name="recid" value="<?php echo $recToEdit; ?>" />
        <!-- append form questions from database -->
        <div class="form-group required">
          <label for="description">Item Description</label>
          <textarea class="form-control" tabindex="110" id="description" required name="description"><?php echo isset($description) ? $description : '';  ?></textarea>
        </div>
        <div class="form-group required">
          <label for="department">Department</label>
          <div class="bfh-selectbox" data-name="department" required tabindex="120" data-value="<?php echo isset($department) ? $department : '';  ?>" data-filter="true">
            <div data-value="0"></div>
            <div data-value="American Culture - 193000">American Culture - 193000</div>
            <div data-value="Anthro-History - 179200">Anthro-History - 179200</div>
            <div data-value="Anthropology - 172000">Anthropology - 172000</div>
            <div data-value="Applied Physics - 184600">Applied Physics - 184600</div>
            <div data-value="Asian Languages & Cultures - 176000">Asian Languages & Cultures - 176000</div>
            <div data-value="Astronomy - 172500">Astronomy - 172500</div>
            <div data-value="Biological Station - 172700">Biological Station - 172700</div>
            <div data-value="Biological Station: Osborn - 206610">Biological Station: Osborn - 206610</div>
            <div data-value="Biology - Undergraduate - 188900">Biology - Undergraduate - 188900</div>
            <div data-value="Biophysics - 554000">Biophysics - 554000</div>
            <div data-value="Botanical Gardens - 206000">Botanical Gardens - 206000</div>
            <div data-value="CAAS: S. African Init. - 550100">CAAS: S. African Init. - 550100</div>
            <div data-value="CaMLA: AEE - 181770">CaMLA: AEE - 181770</div>
            <div data-value="CaMLA: Consulting - 181780">CaMLA: Consulting - 181780</div>
            <div data-value="CaMLA: ECCE (B2) - 181740">CaMLA: ECCE (B2) - 181740</div>
            <div data-value="CaMLA: ECPE (C2) - 181750">CaMLA: ECPE (C2) - 181750</div>
            <div data-value="CaMLA: General Admin - 181700">CaMLA: General Admin - 181700</div>
            <div data-value="CaMLA: GSI-OET - 181760">CaMLA: GSI-OET - 181760</div>
            <div data-value="CaMLA: MELAB - 181710">CaMLA: MELAB - 181710</div>
            <div data-value="CaMLA: MET - 181720">CaMLA: MET - 181720</div>
            <div data-value="CAMLA: Practice Materials - 181790">CAMLA: Practice Materials - 181790</div>
            <div data-value="CaMLA: Test Publications - 181730">CaMLA: Test Publications - 181730</div>
            <div data-value="CAMLA: Young Learners - 181800">CAMLA: Young Learners - 181800</div>
            <div data-value="Chemistry - 173500">Chemistry - 173500</div>
            <div data-value="Classical Art/Archaeology - 173700">Classical Art/Archaeology - 173700</div>
            <div data-value="Classical Studies - 174000">Classical Studies - 174000</div>
            <div data-value="Cognitive Neuroscience Program - 550500">Cognitive Neuroscience Program - 550500</div>
            <div data-value="Cognitive Sci&Mach Intel - 174800">Cognitive Sci&Mach Intel - 174800</div>
            <div data-value="College of Lit, Science & Arts - 170000">College of Lit, Science & Arts - 170000</div>
            <div data-value="Communication Studies - 188300">Communication Studies - 188300</div>
            <div data-value="Comparative Literature - 191400">Comparative Literature - 191400</div>
            <div data-value="Complex Systems - 550400">Complex Systems - 550400</div>
            <div data-value="Computer Science - 174600">Computer Science - 174600</div>
            <div data-value="Culture and Cognition Program - 550300">Culture and Cognition Program - 550300</div>
            <div data-value="DAAS - 190300">DAAS - 190300</div>
            <div data-value="Dean: Academic Affairs - 170300">Dean: Academic Affairs - 170300</div>
            <div data-value="Dean: Auxiliary Services - 170700">Dean: Auxiliary Services - 170700</div>
            <div data-value="Dean: Classrooms - 171000">Dean: Classrooms - 171000</div>
            <div data-value="Dean: Dean's Office - 174200">Dean: Dean's Office - 174200</div>
            <div data-value="Dean: Development - 170200">Dean: Development - 170200</div>
            <div data-value="Dean: East Hall Tech Svcs - 185600">Dean: East Hall Tech Svcs - 185600</div>
            <div data-value="Dean: Facilities - 170110">Dean: Facilities - 170110</div>
            <div data-value="Dean: Facilities - USB - 171250">Dean: Facilities - USB - 171250</div>
            <div data-value="Dean: Facilities Projects - 170100">Dean: Facilities Projects - 170100</div>
            <div data-value="Dean: Finance - 170500">Dean: Finance - 170500</div>
            <div data-value="Dean: Human Resources - 173800">Dean: Human Resources - 173800</div>
            <div data-value="Dean: Info. Technology - 172900">Dean: Info. Technology - 172900</div>
            <div data-value="Dean: Instruc Support Svcs - 171200">Dean: Instruc Support Svcs - 171200</div>
            <div data-value="Dean: Interdept Activity - 179900">Dean: Interdept Activity - 179900</div>
            <div data-value="Dean: Invested Department - 173600">Dean: Invested Department - 173600</div>
            <div data-value="Dean: Machine Shop - 170150">Dean: Machine Shop - 170150</div>
            <div data-value="Dean: Mgmt. Info. Systems - 173900">Dean: Mgmt. Info. Systems - 173900</div>
            <div data-value="Dean: Outreach Staffing - 174100">Dean: Outreach Staffing - 174100</div>
            <div data-value="Dean: Planning/Operations - 174400">Dean: Planning/Operations - 174400</div>
            <div data-value="Dean: Research/Grad. Stud. - 174300">Dean: Research/Grad. Stud. - 174300</div>
            <div data-value="Dean: Shared Services - 174250">Dean: Shared Services - 174250</div>
            <div data-value="Dean: Shared Svc-Dennison - 174253">Dean: Shared Svc-Dennison - 174253</div>
            <div data-value="Dean: Shared Svc-MLB-Thayr - 174251">Dean: Shared Svc-MLB-Thayr - 174251</div>
            <div data-value="Dean: Shared Svc-West Hall - 174252">Dean: Shared Svc-West Hall - 174252</div>
            <div data-value="Dean: ShSvc-South State St - 174255">Dean: ShSvc-South State St - 174255</div>
            <div data-value="Dean: Software Recharge - 172950">Dean: Software Recharge - 172950</div>
            <div data-value="Dean: TA Training - 171600">Dean: TA Training - 171600</div>
            <div data-value="Dean: Undergrad. Education - 171300">Dean: Undergrad. Education - 171300</div>
            <div data-value="Earth & Env Sci: CampDavis - 177075">Earth & Env Sci: CampDavis - 177075</div>
            <div data-value="Earth & Env Sci: CC - 177050">Earth & Env Sci: CC - 177050</div>
            <div data-value="Earth & Env Sci: EMAL - 177100">Earth & Env Sci: EMAL - 177100</div>
            <div data-value="Earth & Environmental Sci. - 177000">Earth & Environmental Sci. - 177000</div>
            <div data-value="Ecology & Evolutionary Bio - 189100">Ecology & Evolutionary Bio - 189100</div>
            <div data-value="Economics - 175000">Economics - 175000</div>
            <div data-value="ELI Testing and Cert Div - 181501">ELI Testing and Cert Div - 181501</div>
            <div data-value="ELI Testing and Cert Div - 181600">ELI Testing and Cert Div - 181600</div>
            <div data-value="English Language & Lit. - 175500">English Language & Lit. - 175500</div>
            <div data-value="English Language Institute - 181500">English Language Institute - 181500</div>
            <div data-value="Germanic Languages & Lit. - 178000">Germanic Languages & Lit. - 178000</div>
            <div data-value="GIEU Program - 517250">GIEU Program - 517250</div>
            <div data-value="Greek & Roman History Pgm - 174010">Greek & Roman History Pgm - 174010</div>
            <div data-value="Herbarium - 201200">Herbarium - 201200</div>
            <div data-value="History - 179000">History - 179000</div>
            <div data-value="History of Art - 179500">History of Art - 179500</div>
            <div data-value="Humanities Departments - 173000">Humanities Departments - 173000</div>
            <div data-value="Humanities Institute - 171100">Humanities Institute - 171100</div>
            <div data-value="II: African Studies Center - 195400">II: African Studies Center - 195400</div>
            <div data-value="II: Armenian Studies - 195200">II: Armenian Studies - 195200</div>
            <div data-value="II: Atlantic Studies Init. - 191800">II: Atlantic Studies Init. - 191800</div>
            <div data-value="II: Chinese Studies - 191000">II: Chinese Studies - 191000</div>
            <div data-value="II: CSST - 195300">II: CSST - 195300</div>
            <div data-value="II: European Studies - 195000">II: European Studies - 195000</div>
            <div data-value="II: Human Rights Initiatve - 194200">II: Human Rights Initiatve - 194200</div>
            <div data-value="II: Islamic Studies Prog - 192600">II: Islamic Studies Prog - 192600</div>
            <div data-value="II: Japanese Studies - 192000">II: Japanese Studies - 192000</div>
            <div data-value="II: Latin Amer & Carib St - 195100">II: Latin Amer & Carib St - 195100</div>
            <div data-value="II: ME & N. African St - 192500">II: ME & N. African St - 192500</div>
            <div data-value="II: Nam Ctr Korean Studies - 194300">II: Nam Ctr Korean Studies - 194300</div>
            <div data-value="II: Polish Studies - 194100">II: Polish Studies - 194100</div>
            <div data-value="II: Prg Intl Comp Studies - 193700">II: Prg Intl Comp Studies - 193700</div>
            <div data-value="II: Russ, EE & Eurasian St - 194000">II: Russ, EE & Eurasian St - 194000</div>
            <div data-value="II: S. Asian Studies - 194400">II: S. Asian Studies - 194400</div>
            <div data-value="II: SE Asian Studies - 194500">II: SE Asian Studies - 194500</div>
            <div data-value="II: Weiser Emerging Democ - 195500">II: Weiser Emerging Democ - 195500</div>
            <div data-value="II: Weiser Europe/Eurasia - 195600">II: Weiser Europe/Eurasia - 195600</div>
            <div data-value="II: World Performance St - 193500">II: World Performance St - 193500</div>
            <div data-value="International Institute - 190000">International Institute - 190000</div>
            <div data-value="Judaic Studies - 179100">Judaic Studies - 179100</div>
            <div data-value="Kelsey Museum/Archaeology - 201500">Kelsey Museum/Archaeology - 201500</div>
            <div data-value="Linguistics - 181200">Linguistics - 181200</div>
            <div data-value="LS&A Asia-American Studies - 193100">LS&A Asia-American Studies - 193100</div>
            <div data-value="LS&A Coll Inst/Values&Sciences - 170900">LS&A Coll Inst/Values&Sciences - 170900</div>
            <div data-value="LS&A Comm on Comp/Historic Res - 175100">LS&A Comm on Comp/Historic Res - 175100</div>
            <div data-value="LS&A Ctr Great Lakes & Aquatic - 206600">LS&A Ctr Great Lakes & Aquatic - 206600</div>
            <div data-value="LS&A Environment Learning Comm - 173350">LS&A Environment Learning Comm - 173350</div>
            <div data-value="LS&A Inteflex - 171800">LS&A Inteflex - 171800</div>
            <div data-value="LS&A Latino Studies - 193200">LS&A Latino Studies - 193200</div>
            <div data-value="LS&A Mathematical Reviews - 206500">LS&A Mathematical Reviews - 206500</div>
            <div data-value="LS&A Observatories - 172600">LS&A Observatories - 172600</div>
            <div data-value="LS&A Pgm in Amer. Institutions - 170600">LS&A Pgm in Amer. Institutions - 170600</div>
            <div data-value="LS&A Research Sem Quan Econ - 175200">LS&A Research Sem Quan Econ - 175200</div>
            <div data-value="LSA- ITC Fund Stdnt Tech Fees - 171350">LSA- ITC Fund Stdnt Tech Fees - 171350</div>
            <div data-value="Marketing & Communications - 174500">Marketing & Communications - 174500</div>
            <div data-value="Mathematics - 183000">Mathematics - 183000</div>
            <div data-value="Molec./Cell./Develop. Bio - 189000">Molec./Cell./Develop. Bio - 189000</div>
            <div data-value="Museum of Anthro Arch - 200500">Museum of Anthro Arch - 200500</div>
            <div data-value="Museums - 200000">Museums - 200000</div>
            <div data-value="Natural Science Department - 172200">Natural Science Department - 172200</div>
            <div data-value="Near Eastern Studies - 183500">Near Eastern Studies - 183500</div>
            <div data-value="Organizational Studies - 174700">Organizational Studies - 174700</div>
            <div data-value="OS: Barger Leadership Inst - 174705">OS: Barger Leadership Inst - 174705</div>
            <div data-value="Paleontology Museum - 202000">Paleontology Museum - 202000</div>
            <div data-value="Philosophy - 184000">Philosophy - 184000</div>
            <div data-value="Physics - 184500">Physics - 184500</div>
            <div data-value="Physics: Atomic/Molec./Opt - 184550">Physics: Atomic/Molec./Opt - 184550</div>
            <div data-value="Physics: Condensed Matter - 184540">Physics: Condensed Matter - 184540</div>
            <div data-value="Physics: Focus Center - 184560">Physics: Focus Center - 184560</div>
            <div data-value="Physics: High Energy Exper - 184510">Physics: High Energy Exper - 184510</div>
            <div data-value="Physics: High Energy SPIN - 184530">Physics: High Energy SPIN - 184530</div>
            <div data-value="Physics: High Energy Theor - 184520">Physics: High Energy Theor - 184520</div>
            <div data-value="Political Science - 185000">Political Science - 185000</div>
            <div data-value="Political Science: MIW - 185100">Political Science: MIW - 185100</div>
            <div data-value="Program in Neuroscience - 189050">Program in Neuroscience - 189050</div>
            <div data-value="Programs & Centers - 172300">Programs & Centers - 172300</div>
            <div data-value="Psychology - 185500">Psychology - 185500</div>
            <div data-value="Psychology: CSBYC - 185510">Psychology: CSBYC - 185510</div>
            <div data-value="Religious Studies - 194700">Religious Studies - 194700</div>
            <div data-value="Romance Languages & Lit. - 186500">Romance Languages & Lit. - 186500</div>
            <div data-value="Science, Tech and Society - 191700">Science, Tech and Society - 191700</div>
            <div data-value="Screen Arts & Cultures - 191600">Screen Arts & Cultures - 191600</div>
            <div data-value="Slavic Languages & Lit. - 187000">Slavic Languages & Lit. - 187000</div>
            <div data-value="Social Science Departments - 172400">Social Science Departments - 172400</div>
            <div data-value="Sociology - 187500">Sociology - 187500</div>
            <div data-value="Statistics - 188500">Statistics - 188500</div>
            <div data-value="Summer Language Inst. - 191300">Summer Language Inst. - 191300</div>
            <div data-value="Sweetland Writing Center - 175600">Sweetland Writing Center - 175600</div>
            <div data-value="UG: CGIS - 171500">UG: CGIS - 171500</div>
            <div data-value="UG: Comprehensive Studies - 191200">UG: Comprehensive Studies - 191200</div>
            <div data-value="UG: Curriculum Support - 171900">UG: Curriculum Support - 171900</div>
            <div data-value="UG: Environment - 173300">UG: Environment - 173300</div>
            <div data-value="UG: Global Scholars Prog - 191270">UG: Global Scholars Prog - 191270</div>
            <div data-value="UG: Hlth. Science Scholars - 174900">UG: Hlth. Science Scholars - 174900</div>
            <div data-value="UG: Honors - 180000">UG: Honors - 180000</div>
            <div data-value="UG: IDEA Institute - 171390">UG: IDEA Institute - 171390</div>
            <div data-value="UG: InterGroup Relations - 191250">UG: InterGroup Relations - 191250</div>
            <div data-value="UG: Language Resource Ctr. - 182000">UG: Language Resource Ctr. - 182000</div>
            <div data-value="UG: Lloyd Hall Scholars - 170400">UG: Lloyd Hall Scholars - 170400</div>
            <div data-value="UG: M-SCI Program - 172801">UG: M-SCI Program - 172801</div>
            <div data-value="UG: Mich Community Schlrs - 171700">UG: Mich Community Schlrs - 171700</div>
            <div data-value="UG: Mich Research Comm - 171401">UG: Mich Research Comm - 171401</div>
            <div data-value="UG: Museum of Nat History - 201000">UG: Museum of Nat History - 201000</div>
            <div data-value="UG: Museum Studies Minor - 170850">UG: Museum Studies Minor - 170850</div>
            <div data-value="UG: Residential College - 186000">UG: Residential College - 186000</div>
            <div data-value="UG: Science Learning Ctr. - 172800">UG: Science Learning Ctr. - 172800</div>
            <div data-value="UG: Student Acad. Affairs - 170800">UG: Student Acad. Affairs - 170800</div>
            <div data-value="UG: Student Recruitment - 173200">UG: Student Recruitment - 173200</div>
            <div data-value="UG: University Courses - 172100">UG: University Courses - 172100</div>
            <div data-value="UG: UROP - 171400">UG: UROP - 171400</div>
            <div data-value="Undergraduate Education - 171301">Undergraduate Education - 171301</div>
            <div data-value="Weinberg Inst for Cog Science - 181250">Weinberg Inst for Cog Science - 181250</div>
            <div data-value="Women's Studies - 188700">Women's Studies - 188700</div>
            <div data-value="Zoology Museum - 202500">Zoology Museum - 202500</div>
            <div data-value="Zoology Museum: ES George - 202600">Zoology Museum: ES George - 202600</div>
          </div>
        </div>
        <div class="form-group required">
          <label for="deptContact">Department Contact</label>
          <input type="text" class="form-control" tabindex="130" id="deptContact" required name="deptContact" value="<?php echo isset($deptContact) ? $deptContact : '';  ?>"/>
        </div>
        <div class="form-group required">
          <label for="locationBldg">Location - Building</label>
          <input type="text" class="form-control" name="locationBldg" tabindex="140" required value="<?php echo isset($locationBldg) ? $locationBldg : '';  ?>" />
        </div>
        <div class="form-group">
          <label for="locationRoom">Location - Room</label>
          <input type="text" class="form-control" tabindex="150" id="locationRoom" name="locationRoom" value="<?php echo isset($locationRoom) ? $locationRoom : '';  ?>" />
        </div>
        <div class="form-group">
          <label for="dateAcquired">Acquisition Year <em class="text-primary">(if known)</em></label>
          <div class="input-group">
             <input type="text" class="form-control bfh-phone" required tabindex="160" id="dateAcquired" name="dateAcquired" value="<?php echo isset($dateAcquired) ? $dateAcquired : '';  ?>" data-format="dddd" />
          </div>
        </div>
        <div class="form-group required">
          <label for="value">Approximate Value <em class="text-primary">(to the nearest dollar amount)</em></label>
          <div class="input-group">
          <span class="input-group-addon">$</span>
          <input type="text" class="form-control bfh-phone" required tabindex="170" id="value" name="value" value="<?php echo isset($value) ? $value : null;  ?>" data-format="dddddddd" />
          </div>
        </div>
        <div class="form-group required">
          <label for="valDetermined">How Value was Determined</label><br>
            <div class="col-xs-offset-1">
              <label class="radio">
                <input type="radio" name="valDetermined" tabindex="171" id="inlineRadio1" value="receipt" <?php echo ($valDetermined == "receipt") ? 'checked' : '';  ?> > Payment Receipt
              </label>
              <label class="radio">
                <input type="radio" name="valDetermined" id="inlineRadio2" value="comparableValue" <?php echo ($valDetermined == "comparableValue") ? 'checked' : '';  ?> > Comparable Value (like or comaparble item)
              </label>
              <label class="radio">
                <input type="radio" name="valDetermined" id="inlineRadio3" value="extAppraisal" <?php echo ($valDetermined == "extAppraisal") ? 'checked' : '';  ?> > External Appraisal - outside vendor
              </label>
              <label class="radio">
                <input type="radio" name="valDetermined" required id="inlineRadio3" value="other" <?php echo ($valDetermined == "other") ? 'checked' : '';  ?> ><input type="text" class="form-control" tabindex="180" id="valDeterminedOther" placeholder="please explain" value="<?php echo isset($valDeterminedOther) ? $valDeterminedOther : null;  ?>" name="valDeterminedOther" />
              </label>
            </div>
        </div>
 <!--        <div class="form-group">
          <label for="protection">Protective Measures in place</label>
          <textarea class="form-control" rows="3" tabindex="190" id="protection" name="protection"><?php echo isset($protection) ? $protection : '';  ?></textarea>
        </div> -->
        <div class="form-group">
        <?php
        if ($imageFile === 'empty'){ $imageFile = "imagefiles/empty.png";}
        ?>
          <a href="<?php echo $imageFile ?>"  target='_blank'><img src='<?php echo $imageFile ?>' width='30' height='30' class='img-rounded' ></a><br />
            <label for="fileToUpload">Select new image to upload</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>

        <div class="text-center">
         <button class="btn btn-info" tab-index="200" type="submit" name="updateRecord" id="updateRecord">Update</button>
        </div>
      </form>
    </div>
  </div><!-- close container -->
  <div class="row clearfix">
    <div class="col-xs-10 col-xs-offset-1">
    <!-- confirm section with answers -->
        <div id="existingRecords"></div>
     </div>
  </div>

  <footer class="container">
    <div id="contentBlock" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="col-xs-5 col-xs-offset-1">
        <address>
          LSA Dean's Office - Budget and Finance<br>
          500 South State Street<br>
          Ann Arbor, MI 48109-1382
        </address>
      </div>
      <div class="col-xs-4 col-xs-offset-2" >
        <img src="img/lsa_mis.png" class="img-responsive" alt="MIS Logo">
      </div>
    </div>
    <div class="row clearfix">
        <p class="text-center"><small>Copyright &copy; 2014 by The Regents of the University of Michigan<br />
        All Rights Reserved.</small><br></p>
  </footer>

  <script src="js/jquery-1.11.2.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-formhelpers.min.js"></script>
  <script src="js/myScripts.min.js"></script>

</body>
</html>
<?php ;


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


