<head>
    <title>Задание 6</title>
    <link rel="stylesheet" href="styleadmin.css">
    <meta name="viewport" content="width=device-width initial-scale=1">
</head>
<body>
<?php

function authorize() {
    header('HTTP/1.1 401 Unanthorized');
    // realm - область безопасности (место с хранимой информацией на сервере, для доступа
    // к которой нужен соотв-й пароль)
    // отправляем информацию браузеру о том, как нужно авторизовываться
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

// Пример HTTP-аутентификации
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Если мы не авторизованы, мы авторизуемся
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW'])) {
  authorize();
}
$user = 'u54448';
$pass = '2320838';
$db = new PDO('mysql:host=localhost;dbname=u54448', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
$stmt = $db->prepare("SELECT * FROM Admin;");
$stmtErr = $stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
$isAdmin = false;
foreach ($admins as $admin){
    if ($admin['login'] == $_SERVER['PHP_AUTH_USER'] && $admin['pass'] == md5($_SERVER['PHP_AUTH_PW'])) {
        $isAdmin = true;
        break;
    }
}
if (!$isAdmin) {
    authorize();
}

// Если мы тут, то мы админ

if ($_SERVER['REQUEST_METHOD']=="GET") {
    // в $_GET['delete'] хранится ID юзера, которого удаляем
    if (!empty($_GET['delete'])) {
        $stmt = $db->prepare("DELETE FROM Person_Ability WHERE p_id=:p_id;");
        $stmtErr = $stmt->execute(['p_id' => $_GET['delete']]);
        $stmt = $db->prepare("DELETE FROM Person WHERE p_id=:p_id;");
        $stmtErr = $stmt->execute(['p_id' => $_GET['delete']]);
        header('Location: ./admin.php');
    }
    if (!empty($_GET['change'])) {
        $stmt = $db->prepare("SELECT * FROM Person WHERE p_id=:p_id;");
        $stmtErr = $stmt->execute(['p_id' => $_GET['change']]);
        $person = $stmt->fetch();
        $stmt = $db->prepare("SELECT * FROM Person_Ability WHERE p_id=:p_id;");
        $stmtErr = $stmt->execute(['p_id' => $_GET['change']]);
        $personAbilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT * FROM Ability;");
        $stmtErr =  $stmt -> execute();
        $abilities = $stmt->fetchAll();
        foreach ($abilities as $ability) {
            $person[$ability['a_name']] = 0;
        foreach ($personAbilities as $personAbility) {
            foreach ($abilities as $ability) {
                if ($ability['a_id'] == $personAbility['a_id']) {
                    $person[$ability['a_name']]=1;
                    break;
                }
            }
            }
        }
        // сохраняем в куки ID пользователя, которого будем изменять
        setcookie('changed_uid', $person['p_id'], time() + 30 * 24 * 60 * 60);
        ?>
    <p>Изменение данный пользователя с ID <?php print ($person['p_id']);?></p>
        <form action="" method="POST">

    <label>
        Имя:<br>
        <input name="name"
               placeholder="Имя" required <?php print('value="' . $person['p_name'] . '"'); ?>>
</label><br>

<label>
    E-mail:<br>
    <input name="email"
           type="email"
           placeholder="e-mail" required <?php print('value="' . $person['mail'] . '"');  ?>>
</label><br>

<label>
    Год рождения:<br>
    <select name="year">
        <?php
        for ($i = 1923; $i <= 2023; $i++) {
            printf('<option value="%d"'. (intval($person['year'])==$i ? 'selected' : '') .'>%d год</option>', $i, $i);
        }
        ?>
    </select>
</label><br>

Пол: <br>
<label><input type="radio"
              name="gender" value="0" required <?php if(intval($person['gender'])==0) print ("checked"); ?>>
    Мужской</label>
<label><input type="radio"
              name="gender" value="1" required <?php if(intval($person['gender'])==1) print ("checked");?>>
    Женский</label><br>

Количество: <br>
<label><input type="radio"
              name="limbs" value="1" required <?php if(!$person['limbs_num']=='' && intval($person['limbs_num'])==1) print ("checked");?>>
    1</label>
<label><input type="radio"
              name="limbs" value="2" required <?php if(!$person['limbs_num']=='' && intval($person['limbs_num'])==2) print ("checked");?>>
    2</label>
<label><input type="radio"
              name="limbs" value="3" required <?php if(!$person['limbs_num']=='' && intval($person['limbs_num'])==3) print ("checked");?>>
    3</label>
<label><input type="radio"
              name="limbs" value="4" required <?php if(!$person['limbs_num']=='' && intval($person['limbs_num'])==4) print ("checked");?>>
    4</label><br>

<label>
    Суперсилы:
    <br>
    <select name="powers[]"
            multiple="multiple">
        <option value="Invincibility" <?php if(intval($person['Invincibility'])==1) print ("selected") ?>>Бессмертие</option>
        <option value="Noclip" <?php if(intval($person['Noclip'])==1) print ("selected") ?>>Хождение сквозь стены</option>
        <option value="Levitation" <?php if(intval($person['Levitation'])==1) print ("selected") ?>>Левитация</option>
    </select>
</label><br>

<label>
    Биография:<br>
    <textarea name="biography"><?php print($person['biography']); ?></textarea>
</label><br>

            <label>
                Логин:<br>
                <input name="p_login"
                       placeholder="Логин" required <?php print('value="' . $person['p_login'] . '"'); ?>>
            </label><br>

            <label>
                Пароль:<br>
                <input name="p_pass"
                       placeholder="" required>
            </label><br>


<input type="submit" value="Отправить">
</form>
<?php
    // после вывода формы останавливаем выполнение кода
    exit();
    // либо мы delete, либо мы change, либо мы просто получаем таблицу для юзеров
    }
    print('Вы успешно авторизовались и видите защищенные паролем данные.');

    $stmt = $db->prepare("SELECT * FROM Person ORDER BY p_id;");
    $stmtErr = $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $db->prepare("SELECT * FROM Person_Ability ORDER BY p_id;");
    $stmtErr = $stmt->execute();
    $resultAbility = $stmt->fetchAll(PDO::FETCH_ASSOC);

    print('<table>
	<thead>
		<tr>
			<td>ID</td>
			<td>Имя</td>
			<td>Почта</td>
			<td>Год рождения</td>
			<td>Пол</td>
			<td>Количество конечностей</td>
			<td>Биография</td>
			<td>Логин</td>
			<td>Хеш пароля</td>
			<td>Способности</td>
			<td>Удалить</td>
			<td>Изменить</td>
		</tr>
	</thead>
	<tbody>');

    foreach ($result as $person) {
        print('<tr>');
        foreach ($person as $key => $value) {
            if ($key=="gender") print ('<td>' . ($value=="0" ? "Муж" : "Жен") . '</td>');
            else print('<td>' . $value . '</td>');
        }
        print('<td>');
        foreach ($resultAbility as $personAbility) {
            if ($personAbility['p_id'] == $person['p_id']){
                switch ($personAbility['a_id']) {
                    case 1:
                        print('Неуязвимость ');
                        break;
                    case 3:
                        print('Ходить сквозь стены ');
                        break;
                    case 2:
                        print('Левитация ');
                        break;
                }
            }
        }
        print ('</td>');
        print ('<td><a href="./admin.php?delete=' . $person['p_id'] . '">Удалить данные</a></td>');
        print ('<td><a href="./admin.php?change=' . $person['p_id'] . '">Изменить данные</a></td>');
        print ('</tr>');
    }
    print ('</tbody>
    </table>');

    // ищем для каждой способности кол-во человек, ей обладающей
    $stmt = $db->prepare("SELECT COUNT(1), a_id FROM Person_Ability GROUP BY a_id;");
    $stmtErr = $stmt->execute();
    $statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($statistics as $statistic) {
        print ('<p>' . $statistic['COUNT(1)'] . ' человек обладают ');
        switch ($statistic['a_id']) {
            case 1:
                print ('неуязвимостью ');
                break;
            case 3:
                print ('хождением сквозь стены ');
                break;
            case 2:
                print ('левитацией ');
                break;
        }
        print ('</p>');
    }

// если мы обновляем данные для пользователя
} else if ($_SERVER['REQUEST_METHOD']=="POST") {
    $stmt = $db->prepare("UPDATE Person SET p_name= :name, mail= :mail, year= :year, gender= :gender, limbs_num= :limbs_num, biography= :biography, p_login=:p_login, p_pass=:p_pass where p_id = :p_id");
    // вытаскиваем из $_COOKIE['changed_uid'] id человека
    $stmtErr = $stmt->execute(['p_id' => $_COOKIE['changed_uid'], 'name' => $_POST['name'],'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography'], 'p_login' => $_POST['p_login'], 'p_pass' => hash("adler32",$_POST['p_pass'])]);
    $stmt = $db->prepare("DELETE FROM Person_Ability WHERE p_id=:p_id;");
    $stmtErr = $stmt->execute(['p_id' => $_COOKIE['changed_uid']]);
    $stmt = $db->prepare("SELECT * FROM Ability;");
    $stmtErr =  $stmt -> execute();
    $abilities = $stmt->fetchAll();
    if (isset($_POST['powers'])) {
        foreach ($_POST['powers'] as $item) {
            foreach ($abilities as $ability) {
                if ($ability['a_name'] == $item) {
                    $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                    $stmtErr = $stmt->execute(['p_id' => $_COOKIE['changed_uid'], 'a_id' => $ability['a_id']]);
                    break;
                }
            }
            if (!$stmtErr) {
                header("HTTP/1.1 500 Some server issue");
                exit();
            }
        }
    }
    setcookie('changed_uid', '', 1);
    header('Location: admin.php');
}
