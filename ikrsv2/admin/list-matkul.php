<?php require_once('../Connections/LocalCoy.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$maxRows_Matkul = 25;
$pageNum_Matkul = 0;
if (isset($_GET['pageNum_Matkul'])) {
  $pageNum_Matkul = $_GET['pageNum_Matkul'];
}
$startRow_Matkul = $pageNum_Matkul * $maxRows_Matkul;

mysql_select_db($database_LocalCoy, $LocalCoy);
$query_Matkul = "SELECT matakuliah.kode_matkul, matakuliah.nama_matkul, matakuliah.sks, dosen.nama FROM matakuliah, dosen WHERE matakuliah.dosen=dosen.nidn ORDER BY kode_matkul ASC";
$query_limit_Matkul = sprintf("%s LIMIT %d, %d", $query_Matkul, $startRow_Matkul, $maxRows_Matkul);
$Matkul = mysql_query($query_limit_Matkul, $LocalCoy) or die(mysql_error());
$row_Matkul = mysql_fetch_assoc($Matkul);

if (isset($_GET['totalRows_Matkul'])) {
  $totalRows_Matkul = $_GET['totalRows_Matkul'];
} else {
  $all_Matkul = mysql_query($query_Matkul);
  $totalRows_Matkul = mysql_num_rows($all_Matkul);
}
$totalPages_Matkul = ceil($totalRows_Matkul/$maxRows_Matkul)-1;

  defined('__NOT_DIRECT') || define('__NOT_DIRECT',1);
  include '../cek-akses.php'; 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>iKRS | Matakuliah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/font-awesome.css" rel="stylesheet">
    <link href="../css/sorter.style.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="../ico/favicon.ico" />
    <script src="../js/jquery-1.8.2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.tablesorter.min.js"></script>
    <script src="../js/jquery.tablesorter.pager.js"></script>
    <script>
        $(document).ready(function() 
          { 
            $("#myTable").tablesorter()
            .tablesorterPager({container: $("#pager")});
          } 
        );
      </script>
  </head>

  <body>
     <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container" style="text-shadow:none;">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="">iKRS</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="index.php"><i class="icon-home"></i> Home</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mahasiswa <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="add-mhs.php"><i class="icon-plus"></i> Input Mahasiswa</a></li>
                  <li class="divider"></li>
                  <li><a href="list-mhs.php"><i class="icon-user"></i> Daftar Mahasiswa</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dosen <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="add-dosen.php"><i class="icon-plus"></i> Input Dosen</a></li>
                  <li class="divider"></li>
                  <li><a href="list-dosen.php"><i class="icon-user"></i> Daftar Dosen</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Matakuliah <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="add-matkul.php"><i class="icon-plus"></i> Input Matakuliah</a></li>
                  <li class="divider"></li>
                  <li><a href="list-matkul.php"><i class="icon-list"></i> Daftar Matakuliah</a></li>
                </ul>
              </li>
              <li><a href="../logout.php"><i class="icon-signout"></i>Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <h1>Daftar Matakuliah</h1>
      <table id="myTable" class="table table-bordered table-hover tablesorter">
        <thead>
          <tr>
            <th class="header">Kode Matkul</th>
            <th class="header">Nama Matkul</th>
            <th class="header">SKS</th>
            <th class="header">Dosen</th>
            <th class="header" colspan="2">Fungsi</th>
          </tr>
        </thead>
        <tbody>
        <?php do { ?>
          <tr>
            <td><?php echo $row_Matkul['kode_matkul']; ?></td>
            <td><?php echo $row_Matkul['nama_matkul']; ?></td>
            <td><?php echo $row_Matkul['sks']; ?></td>
            <td><?php echo $row_Matkul['nama']; ?></td>
            <td style="text-align:center">
                  <input type=button class="btn btn-info" onClick="location.href='editmatkul.php?km=<?php echo $row_Matkul['kode_matkul']; ?>'" value='Edit'></td>
            <td style="text-align:center">
            	<form name="hapusmhs" method="post" action="hapusmatkul.php" onsubmit="return confirm('Click OK or Cancel to Continue');">
                <input type="hidden" name="kode_matkul" value=<?=$row_Matkul['kode_matkul'];?>> 
                <button type="submit" class="btn btn-danger" title="Hapus"><i class="icon-trash icon-white icon-large"></i></button>
              </form>
            </td>
          </tr>
          <?php } while ($row_Matkul = mysql_fetch_assoc($Matkul)); ?>
        </tbody>
      </table>
      <div id="pager" class="pager" style="position: absolute;">
        <form>
          <img src="../img/first.png" class="first">
          <img src="../img/prev.png" class="prev">
          <input type="text" class="pagedisplay">
          <img src="../img/next.png" class="next">
          <img src="../img/last.png" class="last">
          <select class="pagesize">
            <option selected="selected" value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
          </select>
        </form>
      </div>
    </div> 
    <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

  </body>
</html>
<?php
mysql_free_result($Matkul);
?>
