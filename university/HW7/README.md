# Домашно Javascript 1
Да се направи функция validateForm(form_params), където form_params е обект (речник) с ключове: атрибутът "name" на елементите във формата (трябва да се вземе предвид възможността някой ключ да не е подаден) и стойности: стойността, въведена от потребителя в този елемент. Функцията трябва да върне нов обект,  в който за всяко невалидно поле (като ключ) има текст на грешката (пример - долу). Параметрите на формата, които трябва да проверим, са следните:

 - title - string, задължителен, максимална дължина 20

 - description - string, задължителен, максимална дължина 200

 - lecturer_name - string, задължителен, максимална дължина 20

 - type - string, една от стойностите "elective" и "mandatory"

 - program - string, една от стойностите "bachelor", "master"

 - course - число, номер на курс - 1, 2, 3, 4, 0 (за пояснение, ако се чудите какво е 0 - ще означава "за всички курсове"). Заб: ако е избрана програма master от полето program, тогава само курсове 1 и 2 са валидни (както и 0 - "всички курсове"). Заб2: стойността на полето course е число, но то ще пристигне като string от input-а на потребителя - например "3".

 - lecturer_email - string, незадължителен, максимална дължина 20, трябва да e валиден имейл според регулярен израз

 

 

ПРИМЕР:

Ако имаме

var form_params = {

      title: "Твърде дълго име, което е въведено като стойност във формата", // дължина 60 > 20

      description: 'Описание',

      ...... други полета

};

Тогава ако извикаме функцията

var errors = validateForm(form_params);

 

Стойността на errors трябва да бъде

{

      title: 'Името е твърде дълго. Максималната дължина  е 20' // или друг текст за грешка по ваш избор

      // за desription не пише нищо, понеже стойноста е валидна

      ...... и така за всяко поле от формата

}