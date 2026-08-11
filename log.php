<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>Geocache :: Log</title>
<style type="text/css">
@import url(geocache_style.css);
</style>
</head>
<body>

<div id="links">
<a href="./">Home<span> Welcome to Geocache!</span></a>
<a href="caches.php">Caches<span> Access the database of all geocaches.</spam></a>
<a href="log.php">Log<span> Access personal log.</span></a>
<a href="create.html">Create Cache<span> Create your own cache.</span></a>
</div>


<div id="content">
<h1>Geocache&trade;</h1>
<p>
Welcome to <em>Geocache</em>, your destination for information on geocaches in  your area!
</p>

<h2>Cache Log</h2>

<p>
<?php
@$cachedb = new mysqli('localhost','geocacheUser','','geocache');
@$cachedb->set_charset("utf8");

if ($cachedb->connect_errno) {
    echo '<h3>Database Access Error!</h3>';
}
else {
    $select = 'select * from log';

    switch (@$_GET['order']) {
        case 'log_name':
        case 'date_found': $select .= ' order by '.$_GET['order'];
    }

    $result = $cachedb->query( $select );
    $rows   = $result->num_rows;

    echo "<table class=\"uMovies\">\n";
    echo "<tr>\n";
    echo "<th></th>";
    echo "<th><a href=\"log.php?order=log_name\" /> Cache Name </a></th>";
    echo "<th><a href=\"log.php?order=date_found\" /> Date Found </a></th>";
    echo "<tr>\n";
    if ($rows == 0) {
        echo "<tr>\n";
        echo "<td colspan=\"3\">No Caches to Display</td>";
        echo "</tr>\n";
    }
    else {
        for ($i=0; $i<$rows; $i++) {
            $row = $result->fetch_assoc();
            echo "<tr class=\"highlight\">";
            echo "<td>".($i+1)."</td>";
            echo "<td><a href=\"cache.php?name=".$row['log_name']."\" />".$row['log_name']."</a></td>";
            echo"<td>".$row['date_found']."</td>";
            echo "</tr>\n";
        }
    }
    echo "</table>\n";

    $result->free();
    $cachedb->close();
}

?>
</p>

</div>

</body>
</html>
