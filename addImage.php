<!DOCTYPE html>
<html lang="en">
<head>
    <title>Store Data</title>
</head>
<body style="background-color: burlywood">
<center>
    <h1>Aggiunta immagini al Database</h1>
    <h2>Si raccomanda d'inserire immagini di dimensione 300x300 (si consiglia
    l'utilizzo di <a href="https://www.iloveimg.com/resize-image">questo sito</a> per
    effettuare una resize dell'immagine).</h2>
    <form action="insert.php" method="post">

        <p>
            <label for="operaTitle">Titolo:</label>
            <input type="text" name="title" id="operTitle">
        </p>


        <p>
            <label for="authorName">Artista:</label>
            <input type="text" name="author" id="authorName">
        </p>


        <p>
            <label for="dateOfFinish">Data (aaaa-gg-mm):</label>
            <input type="text" name="date_of_realization" id="dateOfFinish">
        </p>


        <p>
            <label for="shortDescription">Descrizione:</label>
            <input type="text" name="description" id="shortDescription">
        </p>


        <p>
            <label for="path">Path:</label>
            <input type="text" name="located_at_path" id="path">
        </p>

        <input type="submit" value="Submit">

        <button id="back" type="button" onclick="location.href='mainPage.php'">
            Indietro
        </button>
    </form>
</center>
</body>
</html>