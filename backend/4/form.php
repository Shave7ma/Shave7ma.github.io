<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <title>Задание 4</title>
    <link rel="stylesheet" href="style4.css" />
  </head>

  <body> 

  <?php
    if (!empty($messages)) {
      print('<div id="messages">');
      // Выводим все сообщения.
      foreach ($messages as $message) {
          print($message);
     }
      print('</div>');
    }
  ?>
    <form action="" method="POST">
      <h2>Форма</h2>
      <label> Имя: <input placeholder="Введите имя" name="name" required
      <?php print('value="' . $values['name'] . '"'); if ($errors['name']) print(' class="error"'); ?>>
    </label>
      <br />
      <label>
        E-mail:
        <input type="email" name="e_mail" placeholder="Введите e-mail" 
        <?php print('value="' . $values['e_mail'] . '"'); if ($errors['e_mail']) print(' class="error"'); ?>>
      </label>
      <br />
      <label> Год рождения: <select name="data" <?php  if ($errors['data']) print('class="error"'); ?>> 
        <?php 
          for ($i = 2023; $i >= 1923; $i--) {
            printf('<option value="%d"'. (intval($values['data'])==$i ? 'selected' : '') .'>%d год</option>', $i, $i);
          }
        ?>
      </select>
      </label>
      <br />
      Пол:
      <label> <input type="radio" value="v1" name="gender" required
      <?php if(strval($values['gender'])=="v1") print ("checked");  if ($errors['gender']) print(' class="error"');?>
      > М </label>
      <label> <input type="radio" value="v2" name="gender" required
      <?php if(strval($values['gender'])=="v2") print ("checked");  if ($errors['gender']) print(' class="error"');?>
      > Ж </label>
      <br />
      Количество конечностей:
      <label> <input type="radio" value="v1" name="limbs" required
      <?php if(!$values['limbs']=='' && strval($values['limbs'])=="v1") print ("checked"); if ($errors['limbs']) print(' class="error"');?>
      > 1 </label>
      <label> <input type="radio" value="v2" name="limbs" required
      <?php if(!$values['limbs']=='' && strval($values['limbs'])=="v2") print ("checked"); if ($errors['limbs']) print(' class="error"');?>
      > 2 </label>
      <label> <input type="radio" value="v3" name="limbs" required
      <?php if(!$values['limbs']=='' && strval($values['limbs'])=="v3") print ("checked"); if ($errors['limbs']) print(' class="error"');?>
      > 3 </label>
      <label> <input type="radio" value="v4" name="limbs" required
      <?php if(!$values['limbs']=='' && strval($values['limbs'])=="v4") print ("checked"); if ($errors['limbs']) print(' class="error"');?>
      > 4 </label>
      <label> <input type="radio" value="v5" name="limbs" required
      <?php if(!$values['limbs']=='' && strval($values['limbs'])=="v5") print ("checked"); if ($errors['limbs']) print(' class="error"');?>
      > 5 </label>
      <br />
      <label>
        Сверхспособности:
        <select multiple="multiple" name="abilities[]">
          <option value="immortality"
          <?php if(intval($values['immortality'])==1) print ("selected") ?>
          >Бессмертие</option>
          <option value="passing through walls"
          <?php if(intval($values['passing through walls'])==1) print ("selected") ?>
          >Прохождение сквозь стены</option>
          <option value="levitation"
          <?php if(intval($values['levitation'])==1) print ("selected") ?>
          >Левитация</option>
          <option value="invisibility"
          <?php if(intval($values['invisibility'])==1) print ("selected") ?>
          >Невидимость</option>
          <option value="night vision"
          <?php if(intval($values['night vision'])==1) print ("selected") ?>
          >Ночное зрение</option>
        </select>
      </label>
      <br />
      <label>
        Биография:
        <textarea placeholder="Введите текст" name="biography"><?php print($values['biography']); ?></textarea>
      </label>
      <br />
      <label>
        <input type="checkbox" name="check" <?php if(intval($values['check'])==1) print ("checked"); if ($errors['check']) print(' class="error"'); ?>/>С контрактом
        ознакомлен(а)
      </label>
      <br />
      <input type="submit" class="button" value="Отправить" />
    </form>
  </body>
</html>
