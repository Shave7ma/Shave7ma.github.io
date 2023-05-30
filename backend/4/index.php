<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 1);
        $messages[] = 'Спасибо, результаты сохранены.';
    }
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['e_mail'] = !empty($_COOKIE['e_mail_error']);
    $errors['data'] = !empty($_COOKIE['data_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['limbs'] = !empty($_COOKIE['limbs_error']);
    $errors['check'] = !empty($_COOKIE['check_error']);
    if ($errors['name']) {
        setcookie('name_error', '', 1);
        $messages[] = '<div class="error-message">Заполните имя. Имя - одно слово с большой буквы</div>';
    }
    if ($errors['e_mail']) {
        setcookie('e_mail_error', '', 1);
        $messages[] = '<div class="error-message">Правильно заполните email.</div>';
    }
    if ($errors['data']) {
        setcookie('data_error', '', 1);
        $messages[] = '<div class="error-message">Заполните год. Он должен быть с 1900 по 2099</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 1);
        $messages[] = '<div class="error-message">Заполните пол.</div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error', '', 1);
        $messages[] = '<div class="error-message">Заполните количество конечностей.</div>';
    }
    if ($errors['check']) {
        setcookie('check_error', '', 1);
        $messages[] = '<div class="error-message">Заполните чекбокс.</div>';
    }
    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['e_mail'] = empty($_COOKIE['e_mail_value']) ? '' : $_COOKIE['e_mail_value'];
    $values['data'] = empty($_COOKIE['data_value']) ? '' : $_COOKIE['data_value'];
    $values['gender'] = !isset($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value']; // использую !isset т к пол может равняться 0 и empty скажет что пол не указан
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
    $values['check'] = !isset($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];
    $values['immortality'] = !isset($_COOKIE['immortality_value']) ? '' : $_COOKIE['immortality_value'];
    $values['passing through walls'] = !isset($_COOKIE['passing through walls']) ? '' : $_COOKIE['passing through walls'];
    $values['levitation'] = !isset($_COOKIE['levitation_value']) ? '' : $_COOKIE['levitation_value'];
    $values['invisibility'] = !isset($_COOKIE['invisibility_value']) ? '' : $_COOKIE['invisibility_value'];
    $values['night vision'] = !isset($_COOKIE['night vision_value']) ? '' : $_COOKIE['night vision_value'];
    include('form.php');
}else {
    $user = 'u54448';
    $pass = '2320838';
    $db = new PDO('mysql:host=localhost;dbname=u54448', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    $errors = FALSE;
    if (empty($_POST['name']) || !preg_match('/^[A-Z][a-z]+$/AD', $_POST['name'])) {
        setcookie('name_error', '1', time() + 24 * 60 * 60);
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['e_mail']) || !preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/", $_POST['e_mail'])) {
        setcookie('e_mail_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('e_mail_value', $_POST['e_mail'], time() + 30 * 24 * 60 * 60);
    }
    if (empty($_POST['data']) || !preg_match("/^(19|20)\d{2}$/", $_POST['data'])) {
        setcookie('data_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('data_value', $_POST['data'], time() + 30 * 24 * 60 * 60);
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
    
    if (isset($_POST['abilities'])) {
        foreach ($_POST['abilities'] as $item) {
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
        header('Location: index.php');
        exit();
    }
    setcookie('name_error', '', 1);
    setcookie('e_mail_error', '', 1);
    setcookie('data_error', '', 1);
    setcookie('gender_error', '', 1);
    setcookie('limbs_error', '', 1);

    try {
        $stmt = $db->prepare("INSERT INTO Person (p_name, mail, year, gender, limbs_num, biography) VALUES (:name, :mail, :year, :gender, :limbs_num, :biography);");
        $stmtErr =  $stmt -> execute(['name' => $_POST['name'],'mail' => $_POST['e_mail'] , 'year' => $_POST['data'], 'gender' => $_POST['gender'], 'limbs_num' => $_POST['limbs'], 'biography' => $_POST['biography']]);
        if (!$stmtErr) {
            header("HTTP/1.1 500 Some server issue");
            exit();
        }
        $strId = $db->lastInsertId();
        if (isset($_POST['abilities'])) {
            foreach ($_POST['abilities'] as $item) {
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
        //print('Error : ' . $e->getMessage());
        exit();
    }

    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');

    // Делаем перенаправление.
    header('Location: index.php');
}
