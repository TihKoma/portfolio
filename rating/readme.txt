Маршутизация в .htaccess

Запросы вида rating.ru/admin/.. редиректятся на admin.php
Запросы вида rating.ru/server/.. -> внутренние запросы, обрабатываются в user.php (редактирование инф-ии о юзере в БД)
Все остальные запросы (обычного юзера) -> page.php


Структура классов ООП

m_Base -> m_User (+m_MYSQL) -> User
m_Base -> m_Page (+m_MYSQL) -> Page
m_Admin (+m_MYSQL) -> Admin