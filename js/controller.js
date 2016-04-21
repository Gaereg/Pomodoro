app.controller('MainCtrl', function($scope, $interval) {
    $scope.timer = { // Object timer avec ses méthode
        secondes: 3,
        minutes: 0,
        nbTaff: 0,
        repo: false
    };

    $scope.languages = [
        {name: 'CSS', logo:     'images/css3.png', select:    false},
        {name: 'HTML', logo:    'images/html.png', select:    false},
        {name: 'JS', logo:      'images/js.png', select:      false},
        {name: 'Angular', logo: 'images/angular.png', select: false},
        {name: 'jQuery', logo:  'images/jquery.png', select:  false},
        {name: 'PhP', logo:     'images/php.png', select:     false},
        {name: 'Ruby', logo:    'images/ruby.png', select:    false}
    ];

    $scope.nbLanguage = 0;
    $scope.task = '';
    $scope.languageInput = '';
    $scope.isPlay = false;
    $scope.work = '';
    $scope.heureTaf = 0;
    $scope.minTaf = 0;

    $scope.languageSelect = function(){
        if($scope.nbLanguage < 2 && this.language.select === false){
            this.language.select = true;
            ++$scope.nbLanguage;
        } else if (this.language.select){
            this.language.select = false;
            --$scope.nbLanguage;
        }
    };

    $scope.languageAutre =  function(){
        if($scope.languageInput !== ''){
            ++$scope.nbLanguage;
        } else{
            --$scope.nbLanguage;
        }
    };

    var done = angular.element(document.getElementById('done'));

    $scope.time = function(){
        if($scope.timer.secondes === 0 && $scope.timer.minutes > 0){ // Quand les seconde sont a zéro, minute -1
            --$scope.timer.minutes;
           $scope.timer.secondes = 59;
        } else if ($scope.timer.secondes > 0 && $scope.timer.minutes >= 0) { // Sinon seconde -1
            --$scope.timer.secondes;
        } else if ($scope.timer.secondes === 0 && $scope.timer.minutes === 0 && $scope.timer.repo === false) { //Quand le timer travail est fini
            var lang = '';
            for(i=0; i<$scope.languages.length; i++){
                if($scope.languages[i].select === true){
                    lang += ' '+"<img src='"+$scope.languages[i].logo+"'>";
                }
            }
            if($scope.languageInput !== ''){
                lang += ' '+$scope.languageInput;
            }
            console.log(lang);
            done.prepend("<li class='list-group-item tache-done'><div>"+lang+'</div><s><div>'+$scope.task+"</div></s></li>");
            $scope.timer.nbTaff++;
            $scope.heureTaf = parseInt($scope.timer.nbTaff*25/60,10); //Calcule le temps passé a travailler
            $scope.minTaf = $scope.timer.nbTaff*25 - $scope.heureTaf*60;
            console.log($scope.heureTaf, $scope.minTaf);
            $scope.timer.repo = true;

            if ($scope.timer.nbTaff%4 === 0){ // Calcul le temps de pause en fonction du nombre de session de travaille faite, et lance diret la pause
                $scope.timer.minutes = 20;
                alert('20 minutes de pause');
            } else {
                $scope.timer.minutes = 5;
                $scope.timer.secondes = 0;
                alert('5 minutes de pause');
            }

        } else { // si timer pause fini, stop le timer
            alert('Fini la pause');
            $scope.stop();
            $scope.timer.repo = false;
        }

        if ($scope.timer.repo){ //Créer une chaine de caractère en fonction de si on est au boulot ou en pause
            $scope.work = 'Pause';
        } else {
            $scope.work = 'Boulot';
        }
        //Ternaire qui permet de rajouté un 0 devant les seconde (11,03, 02 ..) et affiche si on bosse ou si on est en pause
    };
    var playTime;
    $scope.play = function(){ //Fait boucler le timer
        console.log('e');
        if(!$scope.isPlay && $scope.task !== '' && $scope.nbLanguage > 0){
            playTime = $interval(function(){ $scope.time(); }, 1000);
            $scope.isPlay = true;
        }
    };

    $scope.pause = function(){
        if($scope.isPlay){
            $interval.cancel(playTime);
            $scope.isPlay = false;
        }
    };

    $scope.stop = function(){
        $scope.timer.minutes = 25;
        $scope.timer.secondes = 0;
        $scope.isPlay = false;
        $interval.cancel(playTime);
    };
});
