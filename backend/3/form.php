<?php
$error ="";
if (empty($_POST['name'])) {
    $error=$error . "Name is not set. ";
}
if (empty($_POST['e_mail']) || !filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL)) {
       $error = $error . "Mail is not set or is invalid. ";
}
if (empty($_POST['data'])) {
   $error=$error . "Year is not set. ";
}
if (empty($_POST['limbs'])) {
   $error=$error . "Limbs number is not set. ";
}
if (empty($_POST['gender'])) {
    $error=$error . "Gender is not set. ";
}
if($error != ""){
    print($error);
    header("HTTP/1.1 400 " . $error);
    exit();
}
$user = 'u54448';
$pass = '2320838';
$db = new PDO('mysql:host=localhost;dbname=u54448', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

try {
    $stmt = $db -> prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
    $stmtErr = $stmt -> execute(['name' => $_POST['name'], 'mail' => $_POST['e_mail'] , 'year' => $_POST['data'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
    if (!$stmtErr) {
        header("HTTP/1.1 500 Some server issue");
        exit();
    }
    $strId = $db -> lastInsertId();
    if (isset($_POST['abilities'])) {
        foreach ($_POST['abilities'] as $item) {
            switch ($item) {
                case "immortality":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 1]);
                    break;
                case "passing through walls":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 2]);
                    break;
                case "levitation":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 3]);
                    break;
                case "invisibility":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 4]);
                    break;
                case "night vision":
                    $stmt = $db -> prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt -> execute(['p_id' => intval($strId), 'a_id' => 5]);
                    break;
            }
            if (!$stmtErr) {
                header("HTTP/1.1 500 Some server issue");
                exit();
            }
        }
    }
}
catch (PDOException $e){
    header("HTTP/1.1 500 Some server issue");
    exit();
}
