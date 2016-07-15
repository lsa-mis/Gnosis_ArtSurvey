<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

if (isset($_POST["logout"])) {
    header("Location: https://weblogin.umich.edu/cgi-bin/logout");
    exit;
}
if (isset($_POST["addmore"])) {
    header("Location: index.php");
    exit;
}


if (isset($_GET["sort"])) {
    $sortReq = test_input($_GET["sort"]);
    if ($sortReq == 'RecNum') {
        if ($_SESSION['sortBy'] == 'id') {
                $_SESSION['sortBy'] = 'id DESC';
        } else {
            $_SESSION['sortBy'] = 'id';
        }
    } elseif ($sortReq == 'logger') {
        if ($_SESSION['sortBy'] == 'username') {
            $_SESSION['sortBy'] = 'username DESC';
        } else {
            $_SESSION['sortBy'] = 'username';
        }
    } elseif ($sortReq == 'contact') {
        if ($_SESSION['sortBy'] == 'deptContact') {
            $_SESSION['sortBy'] = 'deptContact DESC';
        } else {
            $_SESSION['sortBy'] = 'deptContact';
        }
    } elseif ($sortReq == 'dept') {
        if ($_SESSION['sortBy'] == 'department') {
            $_SESSION['sortBy'] = 'department DESC';
        } else {
            $_SESSION['sortBy'] = 'department';
        }
    } elseif ($sortReq == 'bldg') {
        if ($_SESSION['sortBy'] == 'locationBldg') {
            $_SESSION['sortBy'] = 'locationBldg DESC';
        } else {
            $_SESSION['sortBy'] = 'locationBldg';
        }
    } elseif ($sortReq == 'cost') {
        if ($_SESSION['sortBy'] == 'value') {
            $_SESSION['sortBy'] = 'value DESC';
        } else {
            $_SESSION['sortBy'] = 'value';
        }
    } else {
        if ($_SESSION['sortBy'] == 'timestamp') {
            $_SESSION['sortBy'] = 'timestamp DESC';
        } else {
            $_SESSION['sortBy'] = 'timestamp';
        }
    }
        $_SESSION['message'] = "<h4>&nbsp;</h4>";
} else {
        $_SESSION['sortBy'] = 'timestamp DESC';
}

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


?>



<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo $siteTitle; ?></title>
  <meta name="description" content="echo $siteTitle;">
  <meta name="author" content="<?php echo METAAUTHOR;?>">

  <link rel="shortcut icon" href="ico/favicon.ico">

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-formhelpers.min.css">
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
      <h3>LSA Art Survey - Collections Management</h3><br>
      <small>This section allows you to manage the collections in the <?php echo $siteTitle; ?> application.<br><br>
      <button class='btn btn-xs btn-danger disabled'><span class='glyphicon glyphicon-remove'></span></button> Clicking this button will delete the item.<br>
      <button class='btn btn-xs btn-success disabled'><span class='glyphicon glyphicon-pencil'></span></button> Click this button to edit the item.<br>
      <strong>Click 'active' column headers</strong> to sort items by that column.</small></h3>
    </div>
  </div>
  <div class="container">
  <div id="notify"><?php echo $_SESSION['message'];  ?></div>
    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
        <div class="btn-group" role="group" aria-label="action buttons">
          <form role="actionStuff" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <button type="addmore" name="addmore" class="btn btn-xs btn-info">Add an Item</button>
            <button type="logout" name="logout" class="btn btn-xs btn-warning">I'm Finished</button>
            <a href="download.php" data-toggle='tooltip' data-placement='top' title='Download these records' class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-save"</span></a>
          </form>
        </div>
      </div>

        <!-- Table -->
        <div class=" table-responsive">
        <table id="catalogueList" class="table table-hover">
          <thead>
            <th>Actions</th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=RecNum'>ID</a></th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=logger'>Logged By</a></th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=contact'>Contact</a></th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=dept'>Dept Name</a></th><th>Description</th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=bldg'>Building</a></th><th>Room</th><th>Acquired</th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=cost'>Value</a></th><th>Value<br>Determined By</th>
            <th>if Other:<br>Described</th>
            <th>Protection</th>
            <th>Image</th>
            <th><a href='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?sort=timestamp'>Date<br>Recorded</a></th>
          </thead>
          <tbody>
          <form id="updateRecord" action="updateOnCatalogue.php" method="POST">
        <?php
          include("getCatalogue.php");
        ?>
        </form>
          </tbody>
        </table>
        </div>
    </div>

  </div><!-- close container -->

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
        All Rights Reserved.</small></p>
  </footer>

  <script src="js/jquery-1.11.2.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-formhelpers.min.js"></script>
  <script src="js/myScripts.min.js"></script>

</body>
</html>
