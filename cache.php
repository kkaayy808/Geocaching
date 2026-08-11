<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>Geocache :: Cache</title>
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

<h2>Cache</h2>

<p>
<?php
@$cachedb = new mysqli('localhost','geocacheUser','','geocache');
@$cachedb->set_charset("utf8");

if ($cachedb->connect_errno) {
    echo '<h3>Database Access Error!</h3>';
}
else {
    $select = 'select * from cache';
    if (@$_GET['name'] != "") {
        $select .= ' where name = "'.$_GET['name'].'"';
    }

    $result = $cachedb->query( $select );
    $rows   = $result->num_rows;

    if ($rows == 0) {
        echo "<h3>No Cache to Display</h3>";
    }
    else {
        $cache = $result->fetch_assoc();

        echo "<h3><span class=\"uTitle\">".$cache['name']."</span></h3>";
	echo "<strong>Description:</strong> ".$cache['cache_description']."<br />";
        echo "<strong>Hide Difficulty:</strong> ".$cache['hide_difficulty']."<br />";
	echo "<strong>Terrain Difficulty:</strong> ".$cache['terrain_difficulty']."<br />";
	echo "<strong>Size:</strong> ".$cache['size']."<br />";
	echo "<strong>Latitude:</strong> ".$cache['latitude']."<br />";
	echo "<strong>Longitude:</strong> ".$cache['longitude']."<br />";
	echo "<strong>Date Hidden:</strong> ".$cache['hide_date']."<br />";
	echo "<strong>Hint:</strong> ".$cache['hint']."<br />";
	


        $result->free();
        $cachedb->close();
    }
}
?>
</p>


</div>

</body>
</html>
