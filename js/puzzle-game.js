let timer;

let puzzleGame = {
    stepsNumber: 0,
    stepsNumberSecondPlayer: 0,
    winsFirstPlayer: 0,
    winsSecondPlayer: 0,
    startTime: new Date().getTime(),
    startGame: function (images, gridSize, user1, user2) {

        this.setValuesForEndGame('winner', '');
        this.setValuesForEndGame('imageTitle', '');
        this.setValuesForEndGame('imageDescription', '');
        this.setValuesForEndGame('stepEnd', '');
        this.setValuesForEndGame('timerEnd', '');

        this.startTime = new Date().getTime();
        this.clock();

        this.setImage(images, gridSize, user1, user2);

        helper.doc('mainPanel').style.display = 'block';
        helper.shuffle('sortable');
        helper.shuffle('sortableSecondPlayer');
    },

    setValuesForEndGame: function (spanName, value) {
        let endGameSpanNodes = document.getElementById('endGame').getElementsByTagName('span');
        let endGameScoreSpanNodes = document.getElementById('endGameScore').getElementsByTagName('span');

        let endGameSpanNodesLength = endGameSpanNodes.length;
        let endGameScoreSpanNodesLength = endGameScoreSpanNodes.length;

        for (let currentItem=0; currentItem<endGameSpanNodesLength; currentItem++){
            let currentSpan = endGameSpanNodes[currentItem];
            if (currentSpan.id == spanName)
                currentSpan.innerHTML = value;
        }

        for (let currentItem=0; currentItem<endGameScoreSpanNodesLength; currentItem++){
            let currentSpan = endGameScoreSpanNodes[currentItem];
            if (currentSpan.id == spanName)
                currentSpan.innerHTML = value;
        }
    },

    clock: function () {
        let now = new Date().getTime();
        let elapsedTime = parseInt((now - puzzleGame.startTime) / 1000, 10);
        helper.doc('timerPanel').innerHTML = elapsedTime;
        timer = setTimeout(puzzleGame.clock, 1000);
    },

    setImage: function (images, gridSize, user1, user2) {
        let percentage = 100 / (gridSize - 1);
        let image = images[Math.floor(Math.random() * images.length)];
        let imageSecondPlayer = images[Math.floor(Math.random() * images.length)];

        helper.doc('originalImage').setAttribute('src', image.src);
        helper.doc('originalImageSecondPlayer').setAttribute('src', imageSecondPlayer.src);
        helper.doc('sortable').innerHTML = '';
        helper.doc('sortableSecondPlayer').innerHTML = '';
        helper.doc('fillable').innerHTML = '';
        helper.doc('fillableSecondPlayer').innerHTML = '';

        for(let i = 0; i < (gridSize * gridSize); i++){
            let posX = (percentage * (i % gridSize)) + '%';
            let posY = (percentage * Math.floor(i / gridSize)) + '%';
            let posYVerticalShift = (percentage * Math.floor(i / gridSize)) + 500 + '%';
            let posXHorizontalShift = (percentage * (i % gridSize)) + 700 + '%';

            // Creazione della tabella con celle vuote, dove andranno inseriti i pezzi
            // dell'immagine
            let fillableLi = document.createElement('li');
            let FiId = 'F'.concat(i.toString());
            fillableLi.id = FiId;
            fillableLi.setAttribute('data-value', FiId);
            fillableLi.style.backgroundSize = (gridSize * 100) + '%';
            fillableLi.style.backgroundPosition = posX + ' ' + posY;
            fillableLi.setAttribute('draggable', 'true');

            // Creazione tabella con pezzi dell'immagine disordinata

            let sortableLi = document.createElement('li');
            sortableLi.id = i;
            sortableLi.setAttribute('data-value', i);
            sortableLi.style.backgroundImage = 'url(' + image.src + ')';
            sortableLi.style.backgroundSize = (gridSize * 100) + '%';

            /* La griglia con i pezzi d'immagine sparsi viene posizionata nella
            * stessa ascissa di fillable, ma in ordinata differente (è più in basso) */
            sortableLi.style.backgroundPosition = posX + ' ' + posYVerticalShift;
            sortableLi.setAttribute('draggable', 'true');

            // Creazione della tabella con celle vuote, dove andranno inseriti i pezzi
            // dell'immagine

            let fillableLiSecondPlayer = document.createElement('li');
            let FiIdSecondPlayer = 'FS'.concat(i.toString());
            fillableLiSecondPlayer.id = FiIdSecondPlayer;
            fillableLiSecondPlayer.setAttribute('data-value', FiIdSecondPlayer);
            fillableLiSecondPlayer.style.backgroundSize = (gridSize * 100) + '%';

            /* La griglia vuota del secondo giocatore viene posizionata con la stessa ordinata
            * della fillable del primo giocatore, ma con ascissa diversa (si trova infatti "sulla stessa
            * riga" di fillable) */
            fillableLiSecondPlayer.style.backgroundPosition = posXHorizontalShift + ' ' + posY;
            fillableLiSecondPlayer.setAttribute('draggable', 'true');

            // Creazione tabella con pezzi dell'immagine disordinata

            let sortableLiSecondPlayer = document.createElement('li');
            let sortableLiSecondPlayerId = 'S'.concat(i.toString());
            sortableLiSecondPlayer.id = sortableLiSecondPlayerId;
            sortableLiSecondPlayer.setAttribute('data-value', sortableLiSecondPlayerId);
            sortableLiSecondPlayer.style.backgroundImage = 'url(' + imageSecondPlayer.src + ')';
            sortableLiSecondPlayer.style.backgroundSize = (gridSize * 100) + '%';

            /* La griglia con i pezzi sparsi del secondo giocatore viene posizionata con
            * la stessa ascissa della fillableSecondPlayer e la stessa ordinata della
            * sortable (è alla stessa altezza di quest'ultima e sotto a fillableSecondPlayer) */
            sortableLiSecondPlayer.style.backgroundPosition = posXHorizontalShift + ' ' + posYVerticalShift;
            sortableLiSecondPlayer.setAttribute('draggable', 'true');

            fillableLi.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
            sortableLi.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
            fillableLi.ondragover = (event) => event.preventDefault();
            fillableLi.ondrop = (event) => {
                let source = helper.doc(event.dataTransfer.getData('data'));
                let destination = helper.doc(event.target.id);
                let p = destination.parentNode;

                if (source && destination && p) {

                    let data = event.dataTransfer.getData('data');
                    event.target.appendChild(document.getElementById(data));

                    let valuesId = Array.from(helper.doc('sortable').children).map(x => x.id);
                    let now = new Date().getTime();
                    let incrementedStep = ++puzzleGame.stepsNumber;
                    helper.doc('stepPanel').innerHTML = incrementedStep;

                    if (isImageSorted(valuesId)) {

                        puzzleGame.winsFirstPlayer++;

                        this.setValuesForEndGame('winner', user1.toString());
                        this.setValuesForEndGame('imageTitle', image.title);
                        this.setValuesForEndGame('imageDescription', image.description);
                        this.setValuesForEndGame('timerEnd', (parseInt((now - puzzleGame.startTime) / 1000, 10)));
                        this.setValuesForEndGame('stepEnd', incrementedStep);
                        this.setValuesForEndGame('scoreFirst', puzzleGame.winsFirstPlayer);
                        this.setValuesForEndGame('scoreSecond', puzzleGame.winsSecondPlayer);

                        helper.doc('showEndGame').innerHTML = helper.doc('endGame').innerHTML;
                        helper.doc('showEndGame').style.removeProperty("display");
                        helper.doc('showEndGame').setAttribute('class', 'popupText');

                        document.getElementById('sortable').setAttribute('style', 'display:none');

                        for (let j=0; j<(gridSize*gridSize); j++){
                            document.getElementById('F'.concat(j.toString())).setAttribute('draggable', 'false');
                            document.getElementById('F'.concat(j.toString())).ondragstart = "return false;";
                            document.getElementById('F'.concat(j.toString())).ondrop = "return false;";
                            document.getElementById('S'.concat(j.toString())).setAttribute('draggable', 'false');
                            document.getElementById('S'.concat(j.toString())).ondragstart = "return false;";
                            document.getElementById('S'.concat(j.toString())).ondrop = "return false;";
                        }

                        document.getElementById('currentTimeBox').setAttribute('style', 'display:none');
                        document.getElementById('numStepBox').setAttribute('style', 'display:none');
                        document.getElementById('numStepBoxSecondPlayer').setAttribute('style', 'display:none');

                        if (puzzleGame.winsFirstPlayer == 2){
                            helper.doc('showScore1').innerHTML = helper.doc('endGameScore').innerHTML;
                            helper.doc('showScore1').style.removeProperty("display");
                            helper.doc('showScore1').setAttribute('class', 'popupText');

                            document.getElementById('originalImageBoxSecondPlayer').setAttribute('style', 'display:none');
                            document.getElementById('sortableSecondPlayer').setAttribute('style', 'display:none');
                            document.getElementById('fillableSecondPlayer').setAttribute('style', 'display:none');

                            puzzleGame.winsFirstPlayer = 0;
                            puzzleGame.winsSecondPlayer = 0;
                        }
                    }
                }
            };

            // GESTIONE DRAG & DROP SECONDO GIOCATORE

            fillableLiSecondPlayer.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
            sortableLiSecondPlayer.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
            fillableLiSecondPlayer.ondragover = (event) => event.preventDefault();
            fillableLiSecondPlayer.ondrop = (event) => {
                let source = helper.doc(event.dataTransfer.getData('data'));
                let destination = helper.doc(event.target.id);
                let p = destination.parentNode;

                if (source && destination && p) {

                    let data = event.dataTransfer.getData('data');
                    event.target.appendChild(document.getElementById(data));

                    let valuesIdSecondPlayer = Array.from(helper.doc('sortableSecondPlayer').children).map(x => x.id);
                    let now = new Date().getTime();
                    let incrementedStepSecondPlayer = ++puzzleGame.stepsNumberSecondPlayer;
                    helper.doc('stepPanelSecondPlayer').innerHTML = incrementedStepSecondPlayer;

                    if (isImageSorted(valuesIdSecondPlayer)) {

                        puzzleGame.winsSecondPlayer++;

                        this.setValuesForEndGame('winner', user2.toString());
                        this.setValuesForEndGame('imageTitle', imageSecondPlayer.title);
                        this.setValuesForEndGame('imageDescription', imageSecondPlayer.description);
                        this.setValuesForEndGame('timerEnd', (parseInt((now - puzzleGame.startTime) / 1000, 10)));
                        this.setValuesForEndGame('stepEnd', incrementedStepSecondPlayer);
                        this.setValuesForEndGame('scoreFirst', puzzleGame.winsFirstPlayer);
                        this.setValuesForEndGame('scoreSecond', puzzleGame.winsSecondPlayer);

                        helper.doc('showEndGameSecondPlayer').innerHTML = helper.doc('endGame').innerHTML;
                        helper.doc('showEndGameSecondPlayer').style.removeProperty("display");
                        helper.doc('showEndGameSecondPlayer').setAttribute('class', 'popupText');

                        document.getElementById('sortableSecondPlayer').setAttribute('style', 'display:none');

                        for (let k=0; k<(gridSize*gridSize); k++){
                            document.getElementById('FS'.concat(k.toString())).setAttribute('draggable', 'false');
                            document.getElementById('FS'.concat(k.toString())).ondragstart = "return false;";
                            document.getElementById('FS'.concat(k.toString())).ondrop = "return false;";
                            document.getElementById(k.toString()).setAttribute('draggable', 'false');
                            document.getElementById(k.toString()).ondragstart = "return false;";
                            document.getElementById(k.toString()).ondrop = "return false;";
                        }

                        document.getElementById('currentTimeBox').setAttribute('style', 'display:none');
                        document.getElementById('numStepBox').setAttribute('style', 'display:none');
                        document.getElementById('numStepBoxSecondPlayer').setAttribute('style', 'display:none');

                        if (puzzleGame.winsSecondPlayer == 2){
                            helper.doc('showScore2').innerHTML = helper.doc('endGameScore').innerHTML;
                            helper.doc('showScore2').style.removeProperty("display");
                            helper.doc('showScore2').setAttribute('class', 'popupText');

                            document.getElementById('originalImageBox').setAttribute('style', 'display:none');
                            document.getElementById('sortable').setAttribute('style', 'display:none');
                            document.getElementById('fillable').setAttribute('style', 'display:none');

                            puzzleGame.winsFirstPlayer = 0;
                            puzzleGame.winsSecondPlayer = 0;
                        }
                    }
                }
            };

            sortableLi.setAttribute('dragstart', 'true');
            sortableLiSecondPlayer.setAttribute('dragstart', 'true');
            fillableLi.setAttribute('dragstart', 'true');
            fillableLiSecondPlayer.setAttribute('dragstart', 'true');

            helper.doc('sortable').appendChild(sortableLi);
            helper.doc('sortableSecondPlayer').appendChild(sortableLiSecondPlayer);
            helper.doc('fillable').appendChild(fillableLi);
            helper.doc('fillableSecondPlayer').appendChild(fillableLiSecondPlayer);

        }
        helper.shuffle('sortable');
        helper.shuffle('sortableSecondPlayer');
    }

};

isImageSorted = (idsList) =>
    idsList.every((id, index) => {
        return id == index;
    });

let helper = {
    doc: (id) => document.getElementById(id) || document.createElement("div"),

    shuffle: (id) => {
        let ul = document.getElementById(id);
        for (let i = ul.children.length; i >= 0; i--){
            ul.appendChild(ul.children[Math.random() * i | 0]);
        }
    }
}
