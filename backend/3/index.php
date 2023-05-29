<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <title>Задание 3</title>
    <link rel="stylesheet" href="style3.css" />
    <script src="script3.js" defer></script>
  </head>

  <body>
    <form action="form.php" method="POST">
      <h2>Форма</h2>
      <label> Имя: <input placeholder="Введите имя" name="name"/> </label>
      <br />
      <label>
        E-mail:
        <input type="email" name="e_mail" placeholder="Введите e-mail" />
      </label>
      <br />
      <label> Год рождения: <select name="data" required> 
        <?php 
                    for ($i = 2023; $i >= 1923; $i--) {
                      printf('<option value="%d">%d год</option>', $i, $i);
                    }
                    ?>
                  </select>
      </label>
      <br />
      Пол:
      <label> <input type="radio" value="m" name="gender" /> М </label>
      <label> <input type="radio" value="f" name="gender" /> Ж </label>
      <br />
      Количество конечностей:
      <label> <input type="radio" value="v1" name="limbs" /> 1 </label>
      <label> <input type="radio" value="v2" name="limbs" /> 2 </label>
      <label> <input type="radio" value="v3" name="limbs"/> 3 </label>
      <label> <input type="radio" value="v4" name="limbs" /> 4 </label>
      <label> <input type="radio" value="v5" name="limbs" /> 5 </label>
      <br />
      <label>
        Сверхспособности:
        <select multiple="multiple" name="abilities[]">
          <option value="immortality">Бессмертие</option>
          <option value="passing through walls">Прохождение сквозь стены</option>
          <option value="levitation">Левитация</option>
          <option value="invisibility">Невидимость</option>
          <option value="night vision">Ночное зрение</option>
        </select>
      </label>
      <br />
      <label>
        Биография:
        <textarea placeholder="Введите текст" name="biography"></textarea>
      </label>
      <br />
      <label>
        <input type="checkbox" name="check" onchange="document.getElementById('send').disabled = !this.checked;"/>С контрактом
        ознакомлен(а)
      </label>
      <br />
      <input type="submit" class="button" disabled="disabled" id="send" value="Отправить" />
    </form>
  </body>
</html>
