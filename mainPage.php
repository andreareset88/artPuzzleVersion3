<!DOCTYPE html>
<html lang="en">
<head>

    <title>Art Puzzle</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <script src="js/puzzle-game.js"></script>
    <script src="js/dragDropTouch.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/puzzle-game.css" rel="stylesheet">
    <link href="css/mediaScreenBiggest.css" rel="stylesheet">
    <link href="css/mediaScreenBigger.css" rel="stylesheet">
    <link href="css/mediaScreenBig.css" rel="stylesheet">
    <link href="css/mediaScreenSmall.css" rel="stylesheet">
    <link href="css/mediaScreenSmaller.css" rel="stylesheet">
    <link href="css/mediaScreenSmallest.css" rel="stylesheet">
    <link href="css/mediaScreenMini.css" rel="stylesheet">
    <link href="css/mediaScreenMinimum.css" rel="stylesheet">

</head>

<!-- PRENDENDO LE IMMAGINI DAL DATABASE ONLINE, L'APPLICAZIONE NON FUNZIONA SE ESEGUITA IN LOCALE.
     PER UTILIZZARLA RECARSI AL LINK http://artpuzzle.altervista.org/ -->

<body class="bg-info">

<p id="noContent">Attenzione, il dispositivo non è supportato: larghezza troppo limitata
    (<360px) per giocare. Si prega di ruotare lo schermo</p>

<div id="collage">

    <?php
    echo "<h3>" . "Benvenuti " . strtoupper($_POST['user1']) . " e " . strtoupper($_POST['user2']) . " all'Art Puzzle</h3>";
    ?>

    <hr/>

    <div id="mainPanel" style="padding: 5px; display: block">

        <!-- Ciascun giocatore ha 2 griglie, quella di partenza (sortable) con l'immagine spezzettata,
        quella di destinazione (fillable) dove inserire i quadratini selezionati-->
        <div id="player1">

            <ul id="fillable" class="fillable"></ul>

            <div id="originalImageBox">
                <img id="originalImage" width="300" height="300"/>
            </div>

            <ul id="sortable" class="sortable"></ul>

            <span id="showEndGame" class="popupText"></span>

        </div>

        <div id="player2">

            <div id="originalImageBoxSecondPlayer">
                <img id="originalImageSecondPlayer" width="300" height="300"/>
            </div>

            <ul id="fillableSecondPlayer" class="fillableSecondPlayer"></ul>

            <ul id="sortableSecondPlayer" class="sortableSecondPlayer"></ul>

            <span id="showEndGameSecondPlayer" class="popupText"></span>

        </div>

    </div>

    <div id="currentTimeBox" class="playingPanel">
        Tempo impiegato: <span id="timerPanel1"></span> secondi
    </div>

    <div id="currentTimeBoxSecondPlayer" class="playingPanel" style="text-align: center">
        Tempo impiegato: <span id="timerPanel2"></span> secondi
    </div>

    <div id="numStepBox" class="playingPanel">
        Steps eseguiti da <?php echo strtoupper($_POST['user1']) ?>: <span id="stepPanel"></span>
    </div>

    <div id="numStepBoxSecondPlayer" class="playingPanel">
        Steps eseguiti da <?php echo strtoupper($_POST['user2']) ?>: <span id="stepPanelSecondPlayer"></span>
    </div>

    <div id="buttons">

        <button id="rules" class="popup" onclick="showRules()">
            Mostra regole
            <span class="popupText" id="popupRules">
                    I 2 giocatori si sfidano nel comporre un puzzle che rappresenta la ricostruzione
                dell'immagine proposta dal gioco. La partita tiene traccia del tempo impiegato:
                il giocatore che completa il puzzle nel minor tempo possibile vince la partita,
                visualizzando una breve descrizione dell'opera.
                Il tempo trascorso viene visualizzato nella parte inferiore della schermata.
                Entrambi i giocatori possono richiedere di cambiare l'immagine da ricomporre.
                Vince la partita chi arriva primo a 2 vittorie (al meglio di 3).
                </span>
        </button>

        <button id="startGame" type="button" class="btn" onclick="start();">
            Start
        </button>

        <button id="changeThePhoto" type="button" class="btn" onclick="changeThePhoto();">
            Reload
        </button>

        <button id="addImage" type="button" class="btn" onclick="location.href='addImage.php'">
            Aggiungi opera
        </button>

    </div>


    <div id="endGame" style="display: none;">
        <div id ="endGameDeep" style="background-color: lawngreen; text-align: center; z-index: 100">

            <h2 style="text-align: center">Fine partita</h2>
            <span id="winner" class="resizeEndGame"></span> <p class="resizeEndGame">vince la partita!</p>
            <br/>
            <p class="resizeEndGame">L'immagine è stata ricostruita correttamente!</p>
            <br>
            <p class="resizeEndGame" style="text-decoration: underline black">Titolo:</p> <span id="imageTitle" class="resizeEndGame"></span>
            <br>
            <p class="resizeEndGame" style="text-decoration: underline black">Descrizione: </p> <span id="imageDescription" class="resizeEndGame"></span>
            <br/>
            <p class="resizeEndGame" style="text-decoration: underline black">Steps:</p> <span id="stepEnd" class="resizeEndGame"></span>
            <br>
            <p class="resizeEndGame" style="text-decoration: underline black">Tempo impiegato:</p> <span id="timerEnd" class="resizeEndGame"></span> <p class="resizeEndGame">secondi</p>

        </div>

    </div>

    <div id="endGameScore" style="display: none">
        <div id="endGameScoreDeep" style="text-align: center; background-color: orangered">
            <p class="resizeEndGame">Punteggio finale:</p>
            <br>
            <span id="scoreFirst"></span> - <span id="scoreSecond"></span>
        </div>
    </div>


    <!-- Connessione al db per recupero immagini e creazione lista di oggetti contenenti le principali
    informazioni (relative all'immagine) da usare durante la partita (ad esempio, descrizione e titolo). -->
    <?php

        $connection = mysqli_connect('localhost', 'artpuzzle', '', 'my_artpuzzle'); // Creazione connessione con server
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $path='';
        $title='';
        $query = mysqli_query($connection, 'select * from images');

        // Questi servivano solo per delle stampe in fase di verifica, ora non vengono usati...
        $row_number = mysqli_num_rows($query);
        $rows = array();

    echo "<script> const images = []; </script>";

    while ($row = mysqli_fetch_array($query)) {
        $path = $row['located_at_path'];
        $title = $row['title'];
        $description = $row['description'];

        echo "<script>
            images.push({'src': '$path', 'title': '$title', 'description': '$description'});
             </script>";

    } ?>

    <script>

        let gridSize = 4;

        window.onload = function () {
            puzzleGame.startGame(images, gridSize, "<?php echo strtoupper($_POST['user1']) ?>", "<?php echo strtoupper($_POST['user2']) ?>");

            /* Al primo caricamento della pagina vengono rese non visualizzabili le informazioni
             riguardanti la partita stessa: numero di passi eseguiti e tempo di gioco.*/
            helper.doc('currentTimeBox').style.display = 'none';
            helper.doc('currentTimeBoxSecondPlayer').style.display = 'none';
            helper.doc('numStepBox').style.display = 'none';
            helper.doc('numStepBoxSecondPlayer').style.display = 'none';

            /*Al primo caricamento della pagina, vengono disabilitate tutte le operazioni sulle griglie,
              verranno abilitate al momento dell'effettivo inizio della partita.*/
            for (let j=0; j<gridSize*gridSize; j++){
                document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'false');
                document.getElementById('S'.concat(j.toString())).ondragstart = "return false;";
                document.getElementById('S'.concat(j.toString())).ondrop = "return false;";
                document.getElementById(j.toString()).setAttribute('draggable', 'false');
                document.getElementById(j.toString()).ondragstart = "return false;";
                document.getElementById(j.toString()).ondrop = "return false;";
            }
        }

        function start() {
            
            setParametersForStart();

            helper.doc('stepPanel').innerHTML = 0;
            helper.doc('stepPanelSecondPlayer').innerHTML = 0;
            helper.doc('currentTimeBoxSecondPlayer').style.display = 'none';


            puzzleGame.clock1();
            puzzleGame.stepsNumber = 0;
            puzzleGame.stepsNumberSecondPlayer = 0;
            // puzzleGame.clock();
            
            for (let j=0; j<gridSize*gridSize; j++){
                document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'true');
                document.getElementById('S'.concat(j.toString())).ondragstart = "return true;";
                document.getElementById('S'.concat(j.toString())).ondrop = "return true;";
                document.getElementById(j.toString()).setAttribute('draggable', 'true');
                document.getElementById(j.toString()).ondragstart = "return true;";
                document.getElementById(j.toString()).ondrop = "return true;";
            }

            puzzleGame.startGame(images, gridSize, "<?php echo strtoupper($_POST['user1']) ?>", "<?php echo strtoupper($_POST['user2']) ?>");
        }

        function changeThePhoto() {

            setParametersForStart();

            helper.doc('currentTimeBoxSecondPlayer').style.display = 'none';

            puzzleGame.clock1();

            for (let j=0; j<gridSize*gridSize; j++){
                document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'true');
                document.getElementById('S'.concat(j.toString())).ondragstart = "return true;";
                document.getElementById('S'.concat(j.toString())).ondrop = "return true;";
                document.getElementById(j.toString()).setAttribute('draggable', 'true');
                document.getElementById(j.toString()).ondragstart = "return true;";
                document.getElementById(j.toString()).ondrop = "return true;";
            }

            puzzleGame.startGame(images, gridSize, "<?php echo strtoupper($_POST['user1']) ?>", "<?php echo strtoupper($_POST['user2']) ?>");
        }

        function showRules() {
            let popup = document.getElementById("popupRules");
            popup.classList.toggle("show");
        }

        function setParametersForStart() {
            helper.doc('player2').style.display = 'none'
            helper.doc('showEndGame').style.display = 'none';
            helper.doc('showEndGameSecondPlayer').style.display = 'none';
            helper.doc('sortable').style.display = 'inline';
            helper.doc('sortableSecondPlayer').style.display = 'inline';
            helper.doc('currentTimeBox').style.display = '';
            helper.doc('currentTimeBox').style.textAlign = 'center';
            helper.doc('numStepBox').style.display = '';
            helper.doc('numStepBox').style.textAlign = 'center';
            helper.doc('numStepBoxSecondPlayer').style.display = '';
            helper.doc('numStepBoxSecondPlayer').style.textAlign = 'center';
            helper.doc('originalImageBox').style.display = 'inline-block';
            helper.doc('originalImageBoxSecondPlayer').style.display = 'inline-block';
            helper.doc('fillable').style.display = 'inline-block';
            helper.doc('fillableSecondPlayer').style.display = 'inline-block';
            helper.doc('sortable').style.display = 'inline-block';
            helper.doc('sortableSecondPlayer').style.display = 'inline-block';
        }

    </script>
</div>

</body>
</html>