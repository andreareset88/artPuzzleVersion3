<!DOCTYPE html>
<html>

<head>
    <title>Pagina di inserimento</title>
</head>

<body>
<center>
    <?php

    $conn = mysqli_connect("localhost", "artpuzzle", "", "my_artpuzzle");

    // Check connection
    if($conn === false){
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    }

    // Taking all 5 values from the form data(input)
    $title = $_REQUEST['title'];
    $author = $_REQUEST['author'];
    $date_of_realization = $_REQUEST['date_of_realization'];
    $description = $_REQUEST['description'];
    $located_at_path = $_REQUEST['located_at_path'];

    // Performing insert query execution
    // here our table name is college
    $sql = "INSERT INTO images (title, author, date_of_realization, description, located_at_path) VALUES ('$title',
			'$author','$date_of_realization','$description','$located_at_path')";

    if(mysqli_query($conn, $sql)){
        echo "<h3>I dati sono stati inseriti nel database."
            . " Per vedere i cambiamenti aprire phpMyAdmin</h3>";

        echo nl2br("\n$title\n $author\n "
            . "$date_of_realization\n $description\n $located_at_path");
    } else{
        echo "ERROR: Hush! Sorry $sql. "
            . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
    ?>
</center>
</body>

</html>

