<?php
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '741741');
    }
    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }

    $response = [];

    $beginDate = isset($_GET['beginDate']) ? $_GET['beginDate'] : null;
    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

    if($_GET['recherche'] == 'time'){
        $tacheDay = $bdd->prepare("SELECT lang1, lang2, task, date_task FROM pomodoro WHERE date_task >= :beginDate && date_task <= :endDate  ORDER BY date_task DESC");
        $tacheDay->bindParam('beginDate', $beginDate, PDO::PARAM_STR);
        $tacheDay->bindParam('endDate', $endDate, PDO::PARAM_STR);

        $tacheDay->execute();
        while ($donnees = $tacheDay->fetch()) {
            if ($donnees['lang2'] != 'Nothing') {
                array_push($response, [
                    'lang1' => $donnees['lang1'],
                    'lang2' => $donnees['lang2'],
                    'task' => $donnees['task'],
                    'date' => $donnees['date_task']
                ]);
            } else {
                array_push($response, [
                    'lang1' => $donnees['lang1'],
                    'task' => $donnees['task'],
                    'date' => $donnees['date_task']
                ]);
            }
        }

    } else if ($_GET['recherche'] == 'lang'){
        $temp1 = 0;
        $temp2 = 0;
        $timeLang = $bdd->query("SELECT lang1, lang2 FROM pomodoro");
        while ($donnees = $timeLang->fetch()) {
            foreach ($response as $key => $value) {
                if ($key === $donnees['lang1']) {
                    $temp1 = 1;
                }
                if ($key === $donnees['lang2']) {
                    $temp2 = 1;
                }
            }
            if ($temp1 == 1){
                ++$response[$donnees['lang1']];
                $temp1 = 0;
            } else if ($donnees['lang1'] !== "Nothing"){
                $response[$donnees['lang1']] = 1;
            }
            if ($temp2 == 1){
                ++$response[$donnees['lang2']];
                $temp2 = 0;
            } else if ($donnees['lang2'] !== "Nothing"){
                $response[$donnees['lang2']] = 1;
            }
        }
        arsort($response);
        foreach ($response as $key => $value) {
            $pomodori = $value;
            $heure = intval($pomodori*25/60, 10);
            $min = $pomodori*25-$heure*60;
            $response[$key] = $heure.'h'.$min;
        }
    }
    if ($response !== []){
        echo json_encode($response);
    }

 ?>
