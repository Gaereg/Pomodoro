app.controller('MainCtrl', function($scope, $interval) {
    $scope.repo = sessionRepo;
    $scope.nbTaff = sessionTaff;

    $scope.timer = { // Object timer avec ses méthode
        secondes: 0,
        minutes: 25
      };

    if ($scope.repo === true && $scope.nbTaff % 4 === 0) {
      $scope.timer.minutes = 20;
      $scope.timer.secondes = 0;
    } else if ($scope.repo === true) {
      $scope.timer.minutes = 5;
      $scope.timer.secondes = 0;
    }

    $scope.languages = [
        {name: 'CSS', logo:     'images/css3.png', select:    false},
        {name: 'HTML', logo:    'images/html.png', select:    false},
        {name: 'JS', logo:      'images/js.png', select:      false},
        {name: 'Angular', logo: 'images/angular.png', select: false},
        {name: 'jQuery', logo:  'images/jquery.png', select:  false},
        {name: 'Node', logo:  'images/node.png', select:  false},
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

    $scope.heureTaf = parseInt($scope.nbTaff * 25 / 60, 10); //Calcule le temps passé a travailler
    $scope.minTaf = $scope.nbTaff * 25 - $scope.heureTaf * 60;

    $scope.languageSelect = function() {
        if ($scope.nbLanguage < 2 && this.language.select === false) {
          this.language.select = true;
          ++$scope.nbLanguage;
        } else if (this.language.select) {
          this.language.select = false;
          --$scope.nbLanguage;
        }
      };

    $scope.languageAutre =  function() {
        if ($scope.languageInput !== '') {
          ++$scope.nbLanguage;
        } else {
          --$scope.nbLanguage;
        }
      };

    var done = angular.element(document.getElementById('done'));

    if ($scope.repo === true) {
      playTime = $interval(function() { $scope.time(); }, 1000);
    }

    $scope.time = function() {
        if ($scope.timer.secondes === 0 && $scope.timer.minutes > 0) { // Quand les seconde sont a zéro, minute -1
          --$scope.timer.minutes;
          $scope.timer.secondes = 59;
        } else if ($scope.timer.secondes > 0 && $scope.timer.minutes >= 0) { // Sinon seconde -1
          --$scope.timer.secondes;
        } else if ($scope.timer.secondes === 0 && $scope.timer.minutes === 0 && $scope.repo === false) { //Quand le timer travail est fini
          var langLogo = '';
          var langName = '';
          $scope.languages.forEach( language => {
            console.log(language);
            if (language.select === true) {
              langName += language.name + ' ';
            }
        });
          if ($scope.languageInput !== '') {
            langName += $scope.languageInput + ' ';
          }
          $scope.nbTaff++;

          if ($scope.nbTaff % 4 === 0) { // Calcul le temps de pause en fonction du nombre de session de travaille faite, et lance diret la pause
            alert('20 minutes de pause');
            $interval.cancel(playTime);
        } else if ($scope.nbTaff % 4 !== 0){
            alert('5 minutes de pause');
            $interval.cancel(playTime);
          }
          window.location.assign('php/inject.php?nbTaff=' + $scope.nbTaff + '&lang=' + langName + '&task=' + $scope.task);

      } else if ($scope.repo === true){ // si timer pause fini, stop le timer
          alert('Fini la pause');
          $scope.stop();
          $scope.repo = false;
        }

        if ($scope.repo) { //Créer une chaine de caractère en fonction de si on est au boulot ou en pause
          $scope.work = 'Pause';
        } else {
          $scope.work = 'Boulot';
        }
        //Ternaire qui permet de rajouté un 0 devant les seconde (11,03, 02 ..) et affiche si on bosse ou si on est en pause
    };
    var playTime;
    $scope.play = function() { //Fait boucler le timer
        if (!$scope.isPlay && $scope.task !== '' && $scope.nbLanguage > 0) {
          playTime = $interval(function() { $scope.time(); }, 1000);
          $scope.isPlay = true;
        }
    };

    $scope.pause = function() {
        $interval.cancel(playTime);
        $scope.isPlay = false;
    };

    $scope.stop = function() {
        $scope.timer.minutes = 25;
        $scope.timer.secondes = 0;
        $scope.isPlay = false;
        $interval.cancel(playTime);
        $scope.work = '';
        isRepo();
    };
  });
