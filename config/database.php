<?php
return [
    'driver' => '', //Не советую использовать другие драйверы, но возможно потом придется их БД переписать
    'host' => '',
    'user' => '',
    'password' => '',
    'db' => [
    'client' => '',
    ],
];
//host - машина с базой данных
//user - пользователь базы данных
//password - пароль пользователя базы данных
//db - название базы данных с данными
//Соединение обязательно! Иначе, ошибка 500 - ошибка сервера.
