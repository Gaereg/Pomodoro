<?php

session_start();

try
{
    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', '', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

$task = trim(htmlspecialchars(addslashes($_GET['task'])));
$nbTaff = $_GET['nbTaff'];
$tabLang = explode(" ", $_GET['lang'], 2);

$_SESSION['nbTaff'] = intval($nbTaff);
$_SESSION['repo'] = intval(1);

if($tabLang[0] != ''){
    $lang1 = trim(htmlspecialchars(addslashes($tabLang[0])));
}
if($tabLang[1] != ''){
    $lang2 = trim(htmlspecialchars(addslashes($tabLang[1])));
} else {
    $lang2 = 'Nothing';
}

if($task != '' && $lang1 != '' && $lang2 != ''){

    $req = $bdd->prepare('INSERT INTO pomodoro(lang1,lang2,task, date_task) VALUES (:lang1, :lang2, :task, NOW())');
    $req->execute(array(
        'lang1' => $lang1,
        'lang2' => $lang2,
        'task' => $task
    ));
}

header('Location: ../index.php');
?>
