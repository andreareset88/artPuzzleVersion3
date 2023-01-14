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

    // Inserimento query
    $sql = "INSERT INTO images (title, author, date_of_realization, description, located_at_path) VALUES ('$title',
			'$author','$date_of_realization','$description','$located_at_path')";

    if(mysqli_query($conn, $sql)){
        echo "<h3>I dati sono stati inseriti nel database."
            . " Per vedere i cambiamenti aprire phpMyAdmin 
               (NOTA: il codice funziona, l'errore riguardante ftp_connect() è causato da
                Altervista, che non permette l'abilitazione del caricamento tramite FTP se
                non tramite FileZilla, le alternative cercate erano tutte a pagamento e 
                richiedono alcuni giorni per l'attivazione della pagina, quindi ho lasciato perdere)</h3>";

        echo nl2br("\n$title\n $author\n "
            . "$date_of_realization\n $description\n $located_at_path");
    } else{
        echo "ERROR: Hush! Sorry $sql. "
            . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);

    $ftp_server = "ftp.artpuzzle.altervista.org";
    $ftp_username = "artpuzzle";
    $ftp_password = "YHWH3KCr2FCY";

    // Restituisce il filename temporaneo del file in cui viene memorizzato sul server
    // il file caricato
    $file = $_FILES['file']['tmp_name'];
    // Restituisce il nome originale del file da caricare
    $remote_file = $_FILES['file']['name'];
    // connection_id è l'handler
    $connection_id = ftp_connect($ftp_server);

    $login_result = ftp_login($connection_id, $ftp_username, $ftp_password);

    if ((!$connection_id) || (!$login_result)) {
        die("Connessione FTP fallita...");
    }

    if (ftp_put($connection_id, $remote_file, $file, FTP_BINARY)) {
        echo "file caricato con successo";
    } else
        echo "caricamento del file fallito";

    // Chiudi connessione FTP
    ftp_close($connection_id);

    ?>
</center>
</body>

</html>

