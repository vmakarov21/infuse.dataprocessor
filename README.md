# Задача
Создать модуль, который содержит метод
getUserNameVowels(<ID пользователя>),
возвращающий все гласные буквы из `NAME`, `LAST_NAME`, `SECOND_NAME` пользователя в нижнем регистре без пробелов с соответствующим параметру
<ID пользователя>.
Модуль должен возвращать результат данного метода:
- через REST endpoint вида `<domain>/rest/<id>/<token>/get.username.vowels`
- через стандартный внутренний сервис, доступный в BX.ajax.runAction('<описание  сервиса>.getUserNameVowels')
  Код должен быть одинаково функционален на:
- php 7.1;
- php 8.1 и выше

Результат теста выложить на GITHub у себя в репозитории в открытом доступе и выслать ссылку

# Реализация 
Создан модуль для обработки данных infuse.dataprocessor
- Основной класс MainDataProcessor и требуемым методом getUserNameVowels путь к файлу: local/modules/infuse.dataprocessor/lib/Core/MainDataProcessor.php
- Результат через REST endpoint доступен по ссылке https://cc4dev2dht.pp.ua/rest/1/rr94o1o8cu1wolqf/get.username.vowels/?USER_ID=1 
- Результат через BX.ajax.runAction доступен по ссылке https://cc4dev2dht.pp.ua/dev/ либо выполнением кода где USER_ID - идентификатор пользователя
```
BX.ready(function(){
  BX.ajax.runAction('infuse:dataprocessor.api.MainDataProcessorController.getUserNameVowels', {
    data: { USER_ID:2,}
  }).then(function(response) {
    console.log(response.data);
  }, function(error) {
    console.error('Error:', error);
  });
});
```
