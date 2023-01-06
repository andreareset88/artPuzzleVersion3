<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Access to DB</title>
</head>

<body>

<?php

$id =  "1";
echo "ID: $id <br>" ;
$connection = mysqli_connect('localhost', 'artpuzzle', '', 'my_artpuzzle'); // Establishing Connection with Server
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Connection established<br>";
}

$path='';
$title='';
$query = mysqli_query($connection, 'select * from images');
echo "Query effettuata <br>";
$row_number = mysqli_num_rows($query);
echo "Numero righe: $row_number<br>";
$rows = array();

echo "<script> var images = new Array(); </script>";

while ($row = mysqli_fetch_array($query)) {
    $path = $row['located_at_path'];
    $title = $row['title'];
    echo "Path: $path - Title: $title";
    echo "<br>";

    echo "<script>
        
        images.push({'src': '$path', 'title': '$title'});

    </script>";

    echo "<script>
            console.log('Elemento di Image');
            console.log(images[0]);
            console.log(images[1]);
        </script>";

}
?>

</body>