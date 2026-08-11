<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
            "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
<title>uMovies :: Administration</title>
<style type="text/css">
@import url(uMovies.css);
</style>
</head>
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


// Verify that the session ID and password are set
$id = $_POST['session-id'];

// Get password from the session
if (!isset($_SESSION[$id]['password'])) {
    echo '<h3>Incorrect Password</h3>';
    exit;
}

$password = $_SESSION[$id]['password'];

$moviesdb = new mysqli('localhost', 'uMoviesAdmin', $password, 'uMovies');
$moviesdb->set_charset("utf8");

if ($moviesdb->connect_errno) {
    echo '<h3>Incorrect Password</h3>';
    exit;
}

// Check if a file is uploaded
if (isset($_FILES['uploaded'])) {
    // Debug: Print file upload details
    echo '<pre>';
    var_dump($_FILES['uploaded']);
    echo '</pre>';

    // Check for upload errors
    if ($_FILES['uploaded']['error'] !== UPLOAD_ERR_OK) {
        echo "<h3>Error during file upload: " . $_FILES['uploaded']['error'] . "</h3>";
    } else {
        $filename = $_FILES['uploaded']['tmp_name'];
        echo "<h3>File uploaded: $filename</h3>";

        // Check if the file exists and is readable
        if (file_exists($filename) && is_readable($filename)) {
            echo '<h3>File is readable, proceeding with upload...</h3>';
            $file = fopen($filename, 'r');
            if ($file) {
                $lineCount = 0; // Counter for the number of lines read
                while (($line = fgetcsv($file, 10000, "\t")) !== false) {
                    $lineCount++;
                    echo "<h3>Processing line $lineCount:</h3>";
                    echo '<pre>';
                    print_r($line);
                    echo '</pre>';

                    // Skip empty lines
                    if (empty($line[0])) {
                        echo "<h3>Skipping empty line</h3>";
                        continue;
                    }

                    // Process movie records
                    if ($line[0] === 'movie') {
                        $year = DateTime::createFromFormat("Y", $line[2]);
                        if ($year) {
                            $stmt = $moviesdb->prepare("INSERT IGNORE INTO movies (name, year) VALUES (?, ?)");
                            $stmt->bind_param("si", $line[1], $year->format('Y'));
                            if (!$stmt->execute()) {
                                echo "<h3>Database Error: " . $stmt->error . "</h3>";
                            }
                            $stmt->close();
                        }
                    }

                    // Process actor and actress records
                    if ($line[0] === 'actor' || $line[0] === 'actress') {
                        $gender = $line[0] === 'actor' ? 'Male' : 'Female';
                        $year = DateTime::createFromFormat("Y", $line[3]);
                        if ($year) {
                            $stmt = $moviesdb->prepare("INSERT IGNORE INTO actors (name, gender) VALUES (?, ?)");
                            $stmt->bind_param("ss", $line[1], $gender);
                            if (!$stmt->execute()) {
                                echo "<h3>Database Error: " . $stmt->error . "</h3>";
                            }
                            $stmt->close();

                            $stmt = $moviesdb->prepare("INSERT IGNORE INTO performed_in (actor, movie, year, role) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssis", $line[1], $line[2], $year->format('Y'), $line[4]);
                            if (!$stmt->execute()) {
                                echo "<h3>Database Error: " . $stmt->error . "</h3>";
                            }
                            $stmt->close();
                        }
                    }

                    // Process director records
                    if ($line[0] === 'director') {
                        $year = DateTime::createFromFormat("Y", $line[3]);
                        if ($year) {
                            $stmt = $moviesdb->prepare("INSERT IGNORE INTO directors (name) VALUES (?)");
                            $stmt->bind_param("s", $line[1]);
                            if (!$stmt->execute()) {
                                echo "<h3>Database Error: " . $stmt->error . "</h3>";
                            }
                            $stmt->close();

                            $stmt = $moviesdb->prepare("INSERT IGNORE INTO directed_by (movie, year, director) VALUES (?, ?, ?)");
                            $stmt->bind_param("sis", $line[2], $year->format('Y'), $line[1]);
                            if (!$stmt->execute()) {
                                echo "<h3>Database Error: " . $stmt->error . "</h3>";
                            }
                            $stmt->close();
                        }
                    }
                }
                fclose($file);
                echo "<h3>Total lines processed: $lineCount</h3>";
                echo '<p>File uploaded successfully!</p>';
                echo '<p><a href="admin.html"><input type="submit" value="Back to Administrator Menu" /></a></p>';
            } else {
                echo "<h3>Failed to open file</h3>";
            }
        } else {
            echo "<h3>File exists but is not readable</h3>";
        }
    }
} else {
    echo "<h3>No file uploaded or upload error occurred</h3>";
}

?>
</p>

<p><copyright>Roberto .A. Flores &copy; 2027</copyright></p>
</div>

</body>
</html>