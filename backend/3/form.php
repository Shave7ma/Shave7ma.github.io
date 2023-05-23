<?php

// теперь уже идет валидация посредством PHP
// $_POST - один из суперглобальных массивов, инициализирован всегда
if (empty($_POST['name'])) {
    // создаем HTTP header
    header("HTTP/1.1 400 Name is not set");
    // exit() завершает выполнение скрипта
    exit();
}
// встроенная в php функция для валидации email
if (empty($_POST['e_mail']) || !filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL)) {
    header("HTTP/1.1 400 Mail is not set or is invalid");
    exit();
}
if (empty($_POST['data'])) {
    header("HTTP/1.1 400 Year is not set");
    exit();
}
if (empty($_POST['limbs'])) {
    header("HTTP/1.1 400 Limbs number is not set");
    exit();
}
if (empty($_POST['gender'])) {
    header("HTTP/1.1 400 Gender is not set");
    exit();
}
$user = 'u54448';
$pass = '2320838';
// экземпляр класса PHP Data Objects для взаимодействия с бд
// :: - scope resolution operator, обратиться к полю класса
// в кач-ве значения он должен быть true для постоянного соединения с бд
// => исп-тся для задания значений массива []
$db = new PDO('mysql:host=localhost;dbname=u54448', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

try {
    // вызываем (-> - аналог . в Java для доступа к полям и методам класса)
    // создаем с помощью prepare подготовленный стейтмент (шаблон SQL-запроса)
    // prepare() вернет PDOStatement
    $stmt = $db -> prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
    // у PDOStatement есть метод execute, передаем массив
    // execute вернет true|false
    $stmtErr = $stmt -> execute(['name' => $_POST['name'], 'mail' => $_POST['e_mail'] , 'year' => $_POST['data'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
    if (!$stmtErr) {
        // в случае ошибки
        header("HTTP/1.1 500 Some server issue");
        exit();
    }
    // с помощью метода lastInsertId() получаем в виде строки id последнего вставленного человека
    $strId = $db -> lastInsertId();
    if (isset($_POST['abilities'])) {
        foreach ($_POST['abilities'] as $item) {
            switch ($item) {
                // заполняем Person_Ability - в зав-и от суперсилы a_id отличается
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