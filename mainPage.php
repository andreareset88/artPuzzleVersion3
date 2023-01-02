<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Game</title>

    <link href="css/puzzle-game.css" rel="stylesheet">
    <script src="js/puzzle-game.js">

    </script>
</head>
<body class="bg-info">

<div id="collage">

    <?php
    echo "<h3>" . "Benvenuti " . strtoupper($_POST['user1']) . " e " . strtoupper($_POST['user2']) . " al Puzzle Game</h3>";
    ?>

    <hr/>

    <div id="mainPanel" style="padding: 5px; display: none">
        <div id="player1">

            <ul id="fillable" class="fillable">
            </ul>
            <div id="originalImageBox">
                <img id="originalImage"/>
                <div>

                    <!--<button class="popup" onclick="showRules()">
                        Mostra regole
                        <span class="popupText" id="popupRules">
                                    I 2 giocatori si sfidano nel comporre un puzzle che rappresenta la ricostruzione
                                dell'immagine proposta dal gioco. La partita tiene traccia del tempo impiegato:
                                il primo giocatore che completa il puzzle vince la partita, visualizzando una breve
                                descrizione dell'opera alla destra del puzzle.
                                Il tempo trascorso viene visualizzato nella parte inferiore della schermata.
                                Entrambi i giocatori possono richiedere di cambiare l'immagine da ricomporre.
                                </span>
                    </button>


                    <button id="beginGame" type="button" class="btn" onclick="start();">
                        Start
                    </button>


                    <button id="changedPhoto" type="button" class="btn" onclick="restart();">
                        Restart
                    </button>-->

                </div>
            </div>
            <ul id="sortable" class="sortable">
            </ul>

            <span id="showEndGame" class="popupText">
            </span>

        </div>
        <div id="player2">
            <div id="originalImageBoxSecondPlayer">

                <img id="originalImageSecondPlayer"/>
            </div>
            <ul id="fillableSecondPlayer" class="fillableSecondPlayer">
            </ul>

            <ul id="sortableSecondPlayer" class="sortableSecondPlayer">
            </ul>

            <span id="showEndGameSecondPlayer" class="popupText">
            </span>

        </div>

    </div>

    <div id="currentTimeBox">
        Tempo impiegato: <span id="timerPanel"></span> secondi
    </div>

    <div id="numStepBox">
        Steps eseguiti da <?php echo strtoupper($_POST['user1']) ?>: <span id="stepPanel"></span>
    </div>
    <div id="numStepBoxSecondPlayer">
        Steps eseguiti da <?php echo strtoupper($_POST['user2']) ?>: <span id="stepPanelSecondPlayer"></span>
    </div>

    <div id="buttons">
        <button id="rules" class="popup" onclick="showRules()">
            Mostra regole
            <span class="popupText" id="popupRules">
                    I 2 giocatori si sfidano nel comporre un puzzle che rappresenta la ricostruzione
                dell'immagine proposta dal gioco. La partita tiene traccia del tempo impiegato:
                il primo giocatore che completa il puzzle vince la partita, visualizzando una breve
                descrizione dell'opera alla destra del puzzle.
                Il tempo trascorso viene visualizzato nella parte inferiore della schermata.
                Entrambi i giocatori possono richiedere di cambiare l'immagine da ricomporre.
                </span>
        </button>

        <button id="startGame" type="button" class="btn" onclick="start();">
            Start
        </button>

        <button id="changeThePhoto" type="button" class="btn" onclick="changeThePhoto();">
            Reload
        </button>

    </div>


    <div id="endGame" style="display: none;">
        <div id ="endGameDeep" style="background-color: #f1580e; padding: 5px 10px 20px 10px; text-align: center; ">

            <h2 style="text-align: center">Game Over</h2>
            <br/>
            <span id="winner"></span> vince la partita!
            <br/>
            L'immagine è stata ricostruita correttamente!
            <br/>
            Titolo: <span id="imageTitle"></span>
            <br/>
            Descrizione: <span id="imageDescription"></span>
            <br/>
            Steps: <span id="stepEnd"></span>
            <br/>
            Tempo impiegato: <span id="timerEnd"></span> secondi
        </div>
    </div>

    <?php

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

    echo "<script> const images = []; </script>";

    while ($row = mysqli_fetch_array($query)) {
        $path = $row['located_at_path'];
        $title = $row['title'];
        $description = $row['description'];
        echo "Path: $path - Title: $title";
        echo "<br>";

        echo "<script>

            images.push({'src': '$path', 'title': '$title', 'description': '$description'});

             </script>";

    }

    echo "<script>

            for(var index=0; index<images.length; index++){
                console.log(images[index].title);
            }

             </script>";
    ?>








    <script>



    /*var images = [
        {src: 'images/David-Resized.jpg', title: 'David di Michelangelo'},
        {src: 'images/statue-of-libertyResized.jpg', title: 'Statua della Libertà'},
        {src: 'images/napoleone-Resized.jpg', title: 'Napoleone valica le Alpi'},
        {src: 'images/Urlo-resized.jpg', title: 'Urlo'},
        {src: 'images/Viandante-sul-mare-resized.jpg', title: 'Viandante sul mare'},
        {src: 'images/bacio-hayez-resized.jpg', title: 'Il bacio (di Hayez)'},
        {src: 'images/il-bacio-di-klimt-resized.jpg', title: 'Il bacio (di Klimt)'},
        {src: 'images/notte-stellata-resized.jpg', title: 'Notte stellata'},
        {src: 'images/ragazza-orecchino-resized.jpg', title: 'La Ragazza col Turbante'}
    ];
*/
        window.onload = function () {
            puzzleGame.startGame(images, 4, "<?php echo strtoupper($_POST['user1']) ?>", "<?php echo strtoupper($_POST['user2']) ?>");

            helper.doc('currentTimeBox').style.display = 'none';
            helper.doc('numStepBox').style.display = 'none';
            helper.doc('numStepBoxSecondPlayer').style.display = 'none';

            for (var j=0; j<16; j++){
                document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'false');
                document.getElementById('S'.concat(j.toString())).ondragstart = "return false;";
                document.getElementById('S'.concat(j.toString())).ondrop = "return false;";
                document.getElementById(j.toString()).setAttribute('draggable', 'false');
                document.getElementById(j.toString()).ondragstart = "return false;";
                document.getElementById(j.toString()).ondrop = "return false;";
            }
        }


        function start() {
            
            helper.doc('showEndGame').style.display = 'none';
            helper.doc('showEndGameSecondPlayer').style.display = 'none';
            helper.doc('sortable').style.display = 'inline';
            helper.doc('sortableSecondPlayer').style.display = 'inline';
            helper.doc('stepPanel').innerHTML = 0;
            helper.doc('stepPanelSecondPlayer').innerHTML = 0;
            helper.doc('currentTimeBox').style.display = '';
            helper.doc('currentTimeBox').style.textAlign = 'center';
            helper.doc('numStepBox').style.display = '';
            helper.doc('numStepBox').style.textAlign = 'center';
            helper.doc('numStepBoxSecondPlayer').style.display = '';
            helper.doc('numStepBoxSecondPlayer').style.textAlign = 'center';

            puzzleGame.stepsNumber = 0;
            puzzleGame.stepsNumberSecondPlayer = 0;
            puzzleGame.clock();
            
            for (var j=0; j<16; j++){
                document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'true');
                document.getElementById('S'.concat(j.toString())).ondragstart = "return true;";
                document.getElementById('S'.concat(j.toString())).ondrop = "return true;";
                document.getElementById(j.toString()).setAttribute('draggable', 'true');
                document.getElementById(j.toString()).ondragstart = "return true;";
                document.getElementById(j.toString()).ondrop = "return true;";
            }

            puzzleGame.startGame(images, 4, "<?php echo strtoupper($_POST['user1']) ?>", "<?php echo strtoupper($_POST['user2']) ?>");
        }

        function changeThePhoto() {

            helper.doc('showEndGame').style.display = 'none';
            helper.doc('showEndGameSecondPlayer').style.display = 'none';
            helper.doc('sortable').style.display = 'inline';
            helper.doc('sortableSecondPlayer').style.display = 'inline';
            helper.doc('stepPanel').innerHTML = 0;
            helper.doc('stepPanelSecondPlayer').innerHTML = 0;
            helper.doc('currentTimeBox').style.display = '';
            helper.doc('currentTimeBox').style.textAlign = 'center';
            helper.doc('numStepBox').style.display = '';
            helper.doc('numStepBox').style.textAlign = 'center';
            helper.doc('numStepBoxSecondPlayer').style.display = '';
            helper.doc('numStepBoxSecondPlayer').style.textAlign = 'center';

            puzzleGame.stepsNumber = 0;
            puzzleGame.stepsNumberSecondPlayer = 0;
            puzzleGame.clock();

            start();
        }

        function showRules() {
            var popup = document.getElementById("popupRules");
            popup.classList.toggle("show");
        }

    </script>
</div>

</body>
</html>