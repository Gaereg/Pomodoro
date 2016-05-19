<?php
    session_start();

    if(!isset($_SESSION['nbTaff']) && !isset($_SESSION['repo'])){
        $_SESSION['nbTaff'] = 0;
        $_SESSION['repo'] = 0;
    }

    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', '', '');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
 ?>

<!doctype html>
<html lang="fr" ng-app="pomoApp"  ng-controller="MainCtrl">
<head>
    <!--Meta-->
    <meta charset = "UTF-8">
    <meta name    = "author" content      = "Gaereg">
    <meta name    = "description" content = "Pomodoro">
    <!--link externe-->
    <link rel     = "stylesheet" href     = "bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src = "bower_components/angular/angular.min.js"></script>

    <!-- Font -->

    <!--link interne-->
    <link rel     ="stylesheet" href="style/master.css">
    <script src = "js/app.js"></script>
    <script src = "js/controller/main.js"></script>
    <script src = "js/controller/historique.js"></script>

    <script>
        window.sessionTaff = parseInt("<?php echo $_SESSION['nbTaff'] ?>");
        var tempRepo = parseInt("<?php echo $_SESSION['repo'] ?>");
        if(tempRepo == 0){
            window.sessionRepo = false;
        } else {
            window.sessionRepo = true;
        }

        function isRepo(){
            "<?php $_SESSION['repo'] = 0; ?>"
            sessionRepo = false;
        }
    </script>
    <!--Titre-->
    <title id="title">{{timer.minutes}}:{{timer.secondes | numberFixedLen:2}} {{work}}</title>

</head>

<body>
    <main class="container-fluid">
        <div class="col-md-6 col-md-offset-2 pomo" >
            <h1 class="text-center">Pomodoro</h1>
            <h3>Languages :</h3>
            <div class="langages">
                <div class="icon-name" ng-repeat="language in languages" ng-click="languageSelect()">
                    <img src="{{language.logo}}" alt="logo {{language.name}}">
                    <p ng-class="{select: language.select}">{{language.name}}</p>
                </div>
            </div>
            <input class="language-autre" ng-model="languageInput" ng-blur="languageAutre()" ng-hide="nbLanguage >= 2 && languageInput == ''" type="text" placeholder="autre" maxlength="50">

            <form ng-submit="play()">
                <div class="form-group">
                    <label for="task"></label>
                    <input type="text" id="task" class="form-control" ng-model="task" autocomplete="off" placeholder="Commentaire" maxlength="200">
                </div>
            </form>
            <div id="controls">
                <p class="time">{{timer.minutes}}:{{timer.secondes | numberFixedLen:2}} {{work}}</p>
                <button type="button" class="btn btn-default btn-lg" ng-click="play()">
                    <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-default btn-lg" ng-click="pause()">
                    <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-default btn-lg" ng-click="stop()">
                    <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                </button>
            </div>
            <h2>Done {{heureTaf}}h{{minTaf | numberFixedLen:2}}</h2>
            <ul id="done" class="list-group">
                <?php
                    $dateduJour = strval(date("Y-m-d"));
                    $dateduJour .= ' 00:00:00';

                    $tacheDay = $bdd->prepare("SELECT lang1, lang2, task, date_task FROM pomodoro WHERE date_task >= :dateDays ORDER BY date_task DESC");
                    $tacheDay->bindParam('dateDays', $dateduJour, PDO::PARAM_STR);
                    $tacheDay->execute();
                    while ($donnees = $tacheDay->fetch()) {
                ?>
                    <li class='tache-done list-group-item'>
                        <div>
                            <?php echo $donnees['lang1']; ?>
                            <?php
                                if($donnees['lang2'] != "Nothing"){
                                    ?>/ <?php
                                    echo $donnees['lang2'];
                                }
                            ?>
                        </div>
                        <div>
                            <?php echo $donnees['task'];?>
                        </div>
                    </li>
                    <?php } ?>
            </ul>
        </div>
        <div class="col-lg-3 col-lg-offset-1" ng-controller="HistoCtrl">
            <h2 class="text-center">HISTORIQUE</h2>
            <div class="date-historique" ng-show="!time">
                <div class="glyphicon glyphicon-chevron-left flecheTime" ng-click="prev()"></div>
                <div>{{date}}</div>
                <div class="glyphicon glyphicon-chevron-right flecheTime" ng-click="next()"></div>
            </div>
            <label for="lang">Par langages :</label>
            <input type="checkbox" name="lang" ng-model="time" value="languages" ng-click="preSearch(time)">
            <ul id="done" class="list-group">
                    <li class='tache-done list-group-item' ng-show="!time" ng-repeat="histo in historique">
                        <div>
                            {{histo.lang1}}
                            {{histo.lang2}}
                        </div>
                        <div>
                            {{histo.task}}
                        </div>
                    </li>
                    <li class='tache-done list-group-item' ng-show="time" ng-repeat="(key,value) in historique">
                        <div>
                            {{key}}
                        </div>
                        <div>
                            {{value}}
                        </div>
            </ul>
        </div>
    </main>

    <footer>
    </footer>
    <!-- Script -->
</body>
</html>
