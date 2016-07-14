<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../Support/configArtSurvey.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo "$siteTitle";?> Under Maintenance</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LSA-MIS ArtSurvey">
    <meta name="keywords" content="LSA-MIS, ArtSurvey, UniversityofMichigan">
    <meta name="author" content="LSA-MIS_rsmoke">
    <link rel="icon" href="ico/favicon.ico">
    <style>
    html {
    background: url(img/maintainImage.png) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='img/maintainImage.png', sizingMethod='scale');
    -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='img/maintainImage.png', sizingMethod='scale')";
    }
    body {
      margin: 0;
    }
    #contentwrapper {
      text-align: center;
    }
    #container {
    display: inline-block;
    overflow: hidden;
    min-width: 250px;
    min-height: 200px;
    }
    .header-center {
    color: black;
    font-weight: bold;
    text-align: center;
    }
    footer {
    position: fixed;
    bottom: 10px;
    width: 100%;
    min-width: 250px;
    overflow: hidden;
    text-align:center;
    background-color: #ddd;
    font-size: small;
    }
    .footeritem {
    width: calc(100% / 4);
    display: inline-block;
    vertical-align: top;
    text-align:center;
    padding:10px;
    }
    .clearfix {
    clear: both;
    }
    a {
    background-color: white;
    }
    </style>
  </head>
  <body>
  <div id="contentwrapper">
  <div id="container">

      <h1 class="header-center">The <?php echo "$siteTitle";?> is currently not available.<br>Please
      check back.</h1>
    </div>
    <div class="clearfix"></div>
    <footer>
      <address class="footeritem">
        <?php echo $deptLngName;?><br>
        <?php echo $addressBldg;?>, <?php echo $address2;?><br>
        Ann Arbor, MI <?php echo $addressZip;?>
      </address>
      <div class="footeritem"></div>
      <div class="logo footeritem" >
        <img src="img/lsa_mis.png" alt="MIS Logo">
      </div>
      <div class="clearfix"></div>
      <p><small>Copyright &copy; 2014 by The Regents of the University of Michigan<br />
      All Rights Reserved.</small></p>
    </footer>

    </div>
  </body>
  </html>
