<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>Geocache :: Create</title>
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

<h2>Create a Cache</h2>



<p>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    @$cachedb = new mysqli('localhost','geocacheUser','','geocache');
    @$cachedb->set_charset("utf8");

    if ($cachedb->connect_errno) {
        echo '<h3>Database Access Error!</h3>';
    }
    else {

        $cacheID = $_POST['cache_id'];
        $userID = $_POST['user_id'];
        $hint = $_POST['hint'];
        $description = $_POST['description'];
        $lat = $_POST['latitude'];
        $long = $_POST['longitude'];
        $terrain = $_POST['terrain'];
        $hide = $_POST['hide_difficulty'];
        $size = $_POST['size'];
        $date = $_POST['date'];
        $name = $_POST['name'];

        $insert = "INSERT INTO cache (cache_id, user_id, hint, cache_description, latitude, longitude, terrain_difficulty, hide_difficulty, size, hide_date, name) VALUES ($cacheID, $userID, '$hint', '$description', $lat, $long, $terrain, $hide, $size, '$date', '$name')";

        if($cachedb->query($insert) == TRUE) {
            echo "Cache create successfully!";
        } else {
            echo "Error " . $insert . "<br>" . $cachedb->error;
        }


        $cachedb->close();
    }
} else {
    echo "Invalid request.";
}

?>
</p>


</div>

</body>
</html>
