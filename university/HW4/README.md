**add_elective.php:**

![SQL query](https://cloud.githubusercontent.com/assets/8988578/20097548/a7a15ed6-a5b7-11e6-941d-e6b6f0919de6.png)

Имплементирайте php страница с форма и валидация за добавяне на избираема дисциплина.<br>
Добавете колона created_at на таблицата electives.<br>
Трябва по подразбиране да сочи момента на добавяне на реда.

**electives.php:**<br>
Добавете функционалност за редактиране на избираема дисциплина.<br>
Например: HTTP POST на /electives.php?id=1 със съответните параметри трябва да промени избираемата с id 1.

**electives2.php:**<br>
Добавете функционалност за показване на избираеми дисциплини по лектор.<br>
Например HTTP GET /electives.php?lecturer='Nikolay Bachiyski' трябва да покаже<br>
всички избираеми дисциплини на 'Nikolay Bachiyski‘ в html съдържание по ваш избор.
