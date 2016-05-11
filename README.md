Проект "Тестовый"
========================


1) Установка проекта
----------------------------------

После клонирования проекта необходимо загрузить подключаемые библиотеки

### Используйте Composer (*recommended*)

Для контроля зависимостей проекта используется composer:

```bash
$ php composer.phar install
```

После первой установки выполняем обновление структуры БД:

```bash
$ php app/console doctrine:schema:update --force
$ php app/console doctrine:fixtures:load
```
