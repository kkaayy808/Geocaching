<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>uMovies :: Administration</title>
<style type="text/css">
@import url(uMovies.css);
</style>
</head>

<SCRIPT language=JavaScript>
function verify(form){
    file = form.elements["uploaded"];
    if((file.value != null) && (file.value != "")){
        return confirm("Uploading data file " + file.value);
    }
    alert("The file name cannot be empty.");
    return false;
}

function verifyDelete() {
    return confirm("All data will be deleted. Proceed?");
}
</SCRIPT>



<body>

<div id="links">
<a href="./">Home<span> Access the database of movies, actors and directors. Free to all!</span></a>
<a href="admin.html">Administrator<span> Administrator access. Password required.</span></a>
</div>


<div id="content">
<h1>uMovies&trade;</h1>
<p>
Welcome to <em>uMovies</em>, your destination for information on <a href="movies.php" title="access movies information">movies</a>, <a href="actors.php" title="access actors information">actors</a> and <a href="directors.php" title="access directors information">directors</a>.
</p>

<h2>Administrator Menu</h2>

<p>
<?php

session_start();

//admin pass: adminPassword
//$password = $_POST['password'];
// Get the entered password from the POST data
$password = isset($_POST['password']) ? $_POST['password'] : '';

$id = md5(microtime().$_SERVER['REMOTE_ADDR']);

$_SESSION[$id]['password'] = $password;

@$moviesdb = new mysqli('localhost','uMoviesAdmin',$password,'uMovies');
$moviesdb->set_charset("utf8");


//currently does not catch error when wrong pass is provided

if ($moviesdb->connect_errno) {
    echo '<h3>Incorrect Password</h3>';
    exit;
}
else{

    echo '<h3>Uploading Data File</h3>';

    echo '<p><form enctype="multipart/form-data" action="adminUpload.php" method="post" onsubmit="return verify(this);">';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />';
    echo '<input type="hidden" name="session-id" value="'.$id.'" />';
    echo 'data file <input type="file" name="uploaded" size="30" />';
    echo '<input type="submit" value="Upload" />';
    echo '<br />';
    echo '</p></form>';

    echo '<h3>Deleting Information</h3>';
    echo '<form method="post" onsubmit="return verifyDelete();">';
    echo '<input type="hidden" name="password" value="' . htmlspecialchars($password) . '">';
    echo '<input type="submit" name="delete-data" value="Delete All Data" />';
    echo '</form>';

}

?>
</p>

<p><copyright>Roberto .A. Flores &copy; 2027</copyright></p>
</div>

</body>
</html>
