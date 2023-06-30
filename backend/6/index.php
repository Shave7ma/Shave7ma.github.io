<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
session_start();
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        $messages[] = 'Спасибо, результаты сохранены.';
        // Если в куках есть пароль, то выводим сообщение
        if (!empty($_COOKIE['login'])) {
            $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass']));
            setcookie('login', '', 1);
            setcookie('pass', '', 1);
        }
    }
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['year'] = !empty($_COOKIE['year_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['limbs'] = !empty($_COOKIE['limbs_error']);
    $errors['check'] = !empty($_COOKIE['check_error']);
    // Выдаем сообщения об ошибках.
    if ($errors['name']) {
        setcookie('name_error', '', 100000);
        $messages[] = '<div class="error-message">Заполните имя. Имя - одно слово с большой буквы.</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error-message">Правильно заполните email.</div>';
    }
    if ($errors['year']) {
        setcookie('year_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите год. Он должен быть с 1923 по 2023</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите пол.</div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error', '', 100000);
        $messages[] = '<div class="error-message">Укажите количество конечностей.</div>';
    }
    if ($errors['check']) {
        setcookie('check_error', '', 100000);
        $messages[] = '<div class="error-message">Согласитесь с соглашением.</div>';
    }

    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
        printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }
    $values = array();
    // тернарный оператор: если значения не было, запишется пустая строка, иначе само значение
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    $values['gender'] = !isset($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value']; 
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
    $values['check'] = !isset($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];
    $values['invincibility'] = !isset($_COOKIE['Invincibility_value']) ? '' : $_COOKIE['Invincibility_value'];
    $values['noclip'] = !isset($_COOKIE['Noclip_value']) ? '' : $_COOKIE['Noclip_value'];
    $values['levitation'] = !isset($_COOKIE['Levitation_value']) ? '' : $_COOKIE['Levitation_value'];


    include('form.php');
} // Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    $user = 'u54448';
    $pass = '2320838';
    $db = new PDO('mysql:host=localhost;dbname=u54448', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    // Проверяем ошибки.
    $errors = FALSE;
    if (empty($_POST['name']) || !preg_match('/^[A-Z][a-z]+$/AD', $_POST['name'])) {
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        // Сохраняем ранее введенное в форму значение на месяц
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['email']) || !preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $_POST['email'])) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['year']) || !preg_match("/^(19|20)\d{2}$/", $_POST['year'])) {
        setcookie('year_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
    }
    if (!isset($_POST['gender']) || ($_POST['gender']!='0' && $_POST['gender']!='1')) {
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    }
    if (!isset($_POST['limbs']) || !preg_match('/^[1234]$/AD', $_POST['limbs'])) {
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
    }
    $stmt = $db->prepare("SELECT * FROM Ability;");
    $stmtErr =  $stmt -> execute();
    $abilities = $stmt->fetchAll();
    foreach ($abilities as $ability) {
        setcookie($ability['a_name'].'_value', '', 100000);
    }
    if (isset($_POST['powers'])) {
        foreach ($_POST['powers'] as $item) {
            foreach ($abilities as $ability) {
                if ($ability['a_name'] == $item) {
                    setcookie($item.'_value', '1', time() + 30 * 24 * 60 * 60);
                    break;
                }
            }
        }
    }
    setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
    if ($_POST['check']!="on") {
        setcookie('check_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('check_value', '1', time() + 30 * 24 * 60 * 60);
    }

    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        // Т.е. в базу данных ничего не будет добавлено
        header('Location: index.php');
        exit();
    }
    // Если мы не вышли строчкой выше (exit()), то у нас нет ошибок и мы чистим предыдущие ошибки
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('check_error', '', 100000);

    // Сохранение в БД

    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])) {
        $stmt = $db->prepare("UPDATE Person SET p_name= :name, mail= :mail, year= :year, gender= :gender, limbs_num= :limbs_num, biography= :biography where p_id = :p_id");
        $stmtErr = $stmt->execute(['p_id' => $_SESSION['uid'], 'name' => $_POST['name'],'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
        $stmt = $db->prepare("DELETE FROM Person_Ability WHERE p_id=:p_id;");
        $stmtErr = $stmt->execute(['p_id' => $_SESSION['uid']]);
        if (isset($_POST['powers'])) {
            foreach ($_POST['powers'] as $item) {
                foreach ($abilities as $ability) {
                    if ($ability['a_name'] == $item) {
                        $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                        $stmtErr = $stmt->execute(['p_id' => $_SESSION['uid'], 'a_id' => $ability['a_id']]);
                        break;
                    }
                }
                if (!$stmtErr) {
                    header("HTTP/1.1 500 Some server issue");
                    exit();
                }
            }
        }
    } else {
        try {
            srand(time());
            $login = strval(rand(10000,99999));
            $pass = strval(rand(10000,99999));
            $passcode = hash("adler32",intval($pass));
            $stmt = $db->prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography, p_login, p_pass) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography, :p_login, :p_pass);");
            $stmtErr =  $stmt -> execute(['name' => $_POST['name'],'mail' => $_POST['email'] , 'year' => $_POST['year'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography'],'p_login' => $login, 'p_pass' => $passcode]);
            if (!$stmtErr) {
                header("HTTP/1.1 500 Some server issue");
                exit();
            }
            // достали Id последнего добавленного юзера
            $strId = $db->lastInsertId();
            $_SESSION['login'] = $login;
            $_SESSION['uid'] = intval($strId);
            setcookie('login', $login, time() + 30 * 24 * 60 * 60);
            setcookie('pass', $pass, time() + 30 * 24 * 60 * 60);
            // Аналогичный цикл был выше, только добавляли в куки, а не в БД
            if (isset($_POST['powers'])) {
                foreach ($_POST['powers'] as $item) {
                    foreach ($abilities as $ability) {
                        if ($ability['a_name'] == $item) {
                            $stmt = $db->prepare("INSERT INTO Person_Ability (p_id, a_id) VALUES (:p_id, :a_id);");
                            $stmtErr = $stmt->execute(['p_id' => intval($strId), 'a_id' => $ability['a_id']]);
                            break;
                        }
                    }
                   
                    if (!$stmtErr) {
                        header("HTTP/1.1 500 Some server issue");
                        exit();
                    }
                }
            }
        }
        catch(PDOException $e){
            header("HTTP/1.1 500 Some server issue");
            exit();
        }
    }
    setcookie('save', '1');
    header('Location: index.php');
}
