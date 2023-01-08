function playManagerAndFindWinner(user, image, fillableGrid, sortableGrid, sortablePlayer, steps, stepPanel, showEndGame, fillableToInvalidate, sortableToInvalidate) {
    fillableGrid.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
    sortableGrid.ondragstart = (event) => event.dataTransfer.setData('data', event.target.id);
    fillableGrid.ondragover = (event) => event.preventDefault();
    fillableGrid.ondrop = (event) => {
        let source = helper.doc(event.dataTransfer.getData('data'));
        let destination = helper.doc(event.target.id);
        let p = destination.parentNode;

        if (source && destination && p) {

            let data = event.dataTransfer.getData('data');
            event.target.appendChild(document.getElementById(data));

            let valuesId = Array.from(helper.doc(sortablePlayer).children).map(x => x.id);
            let now = new Date().getTime();
            let incrementedStep = ++steps;
            helper.doc(stepPanel).innerHTML = incrementedStep;

            if (isImageSorted(valuesId)) {
                this.setValuesForEndGame('winner', user.toString());
                this.setValuesForEndGame('imageTitle', image.title);
                this.setValuesForEndGame('imageDescription', image.description);
                this.setValuesForEndGame('timerEnd', (parseInt((now - puzzleGame.startTime) / 1000, 10)));
                this.setValuesForEndGame('stepEnd', incrementedStep);

                helper.doc(showEndGame).innerHTML = helper.doc('endGame').innerHTML;
                helper.doc(showEndGame).style.removeProperty("display");
                helper.doc(showEndGame).setAttribute('class', 'popupText');

                document.getElementById(sortablePlayer).setAttribute('style', 'display:none');

                for (let k=0; k<(gridSize*gridSize); k++){
                    document.getElementById(fillableToInvalidate.concat(k.toString())).setAttribute('draggable', 'false');
                    document.getElementById(fillableToInvalidate.concat(k.toString())).ondragstart = "return false;";
                    document.getElementById(fillableToInvalidate.concat(k.toString())).ondrop = "return false;";
                    document.getElementById(k.toString()).setAttribute('draggable', 'false');
                    document.getElementById(k.toString()).ondragstart = "return false;";
                    document.getElementById(k.toString()).ondrop = "return false;";
                }

                document.getElementById('currentTimeBox').setAttribute('style', 'display:none');
                document.getElementById('numStepBox').setAttribute('style', 'display:none');
                document.getElementById('numStepBoxSecondPlayer').setAttribute('style', 'display:none');
            }
        }
    }
};
