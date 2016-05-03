<?php
    session_start();

    if(!isset($_SESSION['nbTaff']) && !isset($_SESSION['repo'])){
        $_SESSION['nbTaff'] = 0;
        $_SESSION['repo'] = 0;
    }

    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '741741');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }

 ?>

<!doctype html>
<html lang="fr" ng-app="pomoApp">
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
    <script src = "js/controller.js"></script>

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
    <title>Pomodoro</title>

</head>

<body>
    <header>
        <?php echo $_SESSION['repo'] ?>
    </header>

    <main ng-controller="MainCtrl">
        <div class="container">
            <h1>My pomodoro app</h1>
            <h2>Doing</h2>
                {{nbTaff}}
                {{repo}}
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
            <h2>Done {{heureTaf}}h{{minTaf}}</h2>
            <ul id="done" class="list-group">
                <?php
                    $dateduJour = strval(date("Y-m-d"));
                    $dateduJour .= ' 00:00:00';
                    if($dateduJour === '2016-04-29 00:00:00'){
                        echo 'true';
                    }
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
    </main>

    <footer>
    </footer>
    <!-- Script -->
</body>
</html>
