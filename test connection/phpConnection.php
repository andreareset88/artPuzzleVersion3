<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Access to DB</title>
</head>

<body>

<?php

//include('mysqlWrapper.php');

$id =  "1";//Math.floor(Math.random() * 3);
echo "ID: $id <br>" ;
$connection = mysqli_connect('localhost', 'artpuzzle', '', 'my_artpuzzle'); // Establishing Connection with Server
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Connection established<br>";
}
//$db = mysqli_select_db($connection,"my_artpuzzle"); // Selecting Database
//echo "DB selected <br>";
//MySQL Query to read data

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
        //let element = {'src': '$path', 'title': '$title'};
        //console.log(element);
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