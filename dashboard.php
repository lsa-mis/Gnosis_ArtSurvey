<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/configArtSurvey.php");
  require_once($_SERVER["DOCUMENT_ROOT"] . "/../Support/basicLib.php");

if ($userMaster){

if (isset($_POST["logout"])) {
    header("Location: https://weblogin.umich.edu/cgi-bin/logout");
    exit;
}

$sqlTotalRecords = <<<SQL
        SELECT COUNT(id) AS ttl
        FROM tbl_responses
        WHERE deleted = 0
SQL;
if (!$result = $db->query($sqlTotalRecords)) {
    db_fatal_error("User type query issue", $db->error, $sqlCheckUser, $login_name);
    exit;
} else {
    while ($row = $result->fetch_assoc()) {
      $totalRecords = $row['ttl'];
    }
}
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
          <li><a href="reviewItems.php">Catalogue</a></li>
          <li><a href="adminmanager.php">Manage Access</a></li>
          <li class="active"><a href="dashboard.php">Dashboard</a></li>
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
      <p>This is the dashboard for the <?php echo $siteTitle; ?> application.</p>

      <div class="well well-sm">There are a total of <strong><?php echo $totalRecords ?></strong> records for all departments.</div>
    </div>
  </div>
  <div class="container">
  <div id="notify"><?php echo $_SESSION['message'];  ?></div>
    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading">
        <div class="btn-group" role="group" aria-label="action buttons">
          <form role="actionStuff" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <button type="logout" name="logout" class="btn btn-xs btn-warning">I'm Finished</button>
            <a href="download.php" data-toggle='tooltip' data-placement='top' title='Download these records' class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-save"</span></a>
          </form>
        </div>
      </div>

        <!-- Table -->
        <div class=" table-responsive">
        <table id="summaryList" class="table table-hover">
          <thead>
            <th>Dept</th>
            <th>Number of entries</th>
          </thead>
          <tbody>
        <?php
          $sqlSummary = <<<SQL
          SELECT COUNT(department) AS counted, department
          FROM tbl_responses
          WHERE deleted = 0
          GROUP BY department
          ORDER BY department
SQL;

        if (!$result = $db->query($sqlSummary)) {
            db_fatal_error("data select issue", $db->error, $sqlSummary, $login_name);
            exit(user_err_message);
        } else {
          while ($row = $result->fetch_assoc()) {
            $html = '<tr>';
            $html .= '<td>' . $row['department'] . '</td><td>' . $row['counted']. '</td>';
            $html .= '</tr>';
          echo $html;
          }
        }

        ?>
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
<?php
$db->close();
} else {
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
          <div id="notAdmin">
          <div class="row clearfix">
            <div class="col-xs-8 col-xs-offset-2">
              <div id="instructions" style="color:sienna;">
                <h1 class="text-center" >You are not authorized to this space!!!</h1>
                <h4 class="text-center" >University of Michigan - LSA Computer System Usage Policy</h4>
                <p>This is the University of Michigan information technology environment. You
                  MUST be authorized to use these resources. As an authorized user, by your use
                  of these resources, you have implicitly agreed to abide by the highest
                  standards of responsibility to your colleagues, -- the students, faculty,
                  staff, and external users who share this environment. You are required to
                  comply with ALL University policies, state, and federal laws concerning
                  appropriate use of information technology. Non-compliance is considered a
                  serious breach of community standards and may result in disciplinary and/or
                legal action.</p>
                <div class="text-center">
                  <a href="http://www.umich.edu"><img alt="University of Michigan" src="img/michigan.png" height:280px;width:280px; /> </a>
                </div>
                </div><!-- #instructions -->
              </div>
            </div>
          </div>
        </body>
      </html>
<?php
}
