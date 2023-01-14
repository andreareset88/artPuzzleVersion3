<!DOCTYPE html>
<html lang="en">
<head>
    <title>Caricamento file</title>
</head>
<body style="background-color: burlywood">
<center>
    <h1>Aggiunta immagini al Database</h1>
    <h2>Si raccomanda d'inserire immagini di dimensione 300x300 (si consiglia
    l'utilizzo di <a href="https://www.iloveimg.com/resize-image">questo sito</a> per
    effettuare una resize dell'immagine).</h2>
    <form action="insert.php" method="post" style="font-size: large" enctype="multipart/form-data">

        <p>
            <label for="operaTitle">Titolo:</label>
            <input type="text" name="title" id="operaTitle">
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
            <label for="description">Descrizione:</label>
            <textarea rows="7" cols="50" maxlength="1000" name="description" id="description">
            </textarea>
        </p>

        <p>
            <label for="file">Seleziona un file da caricare:</label>
            <input type="file" name="file" id="file">
        </p>

        <p>
            <label for="path">Path:</label>
            <input type="text" name="located_at_path" id="path">
        </p>

        <input type="submit" value="Carica">

        <button id="back" type="button" onclick="location.href='index.html'">
            Indietro
        </button>
    </form>
</center>
</body>
</html>
