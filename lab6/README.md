# 🧑‍💻 Лабораторная работа №5

**Тема:** Работа с базой данных MySQL через PHP и Docker.
**Вариант:** 8. Запись на экскурсию.
**Статус:** Выполнено основное задание + все штрафные задания.

---

## 🎯 Цель работы

1. Научиться работать с базой данных MySQL через PHP.
2. Создать таблицу для данных формы.
3. Сохранять данные формы в базу данных.
4. Выводить данные из базы на странице.
5. Использовать классы PHP для работы с таблицей.
6. Работать с Docker-контейнерами: nginx, PHP-FPM, MySQL, Adminer.

---

## 🛠 Стек технологий

* **Docker / Docker Compose**
* **Nginx** (1.27-alpine) — веб-сервер
* **PHP-FPM** (8.2-fpm) + расширение `pdo_mysql`
* **MySQL** (8.0)
* **Adminer** — веб-интерфейс для работы с БД

---
![{4ED0EAD2-A383-4103-9FBA-673EC65922FF}.png](%7B4ED0EAD2-A383-4103-9FBA-673EC65922FF%7D.png)
![{55744A12-92AB-40EE-84AB-FD03BDA3220B}.png](%7B55744A12-92AB-40EE-84AB-FD03BDA3220B%7D.png)
![{4E268417-40AC-4405-AF15-29BA39D3CB7E}.png](%7B4E268417-40AC-4405-AF15-29BA39D3CB7E%7D.png)
![{45F7135C-2315-4506-B32F-EBE424E8AD97}.png](%7B45F7135C-2315-4506-B32F-EBE424E8AD97%7D.png)
![{B562B50D-BCA3-4190-BDEE-5BD58F9B99FE}.png](%7BB562B50D-BCA3-4190-BDEE-5BD58F9B99FE%7D.png)

---
## 📁 Структура проекта

```text
lab5/
 ├── Dockerfile               # PHP-FPM с PDO MySQL
 ├── docker-compose.yml       # 4 сервиса: web, php, db, adminer
 ├── .gitignore
 ├── nginx/
 │    └── default.conf
 └── www/
      ├── db.php              # подключение к БД через PDO
      ├── Excursion.php       # класс для работы с таблицей
      ├── index.php           # список записей + статистика + фильтр
      ├── form.html           # HTML-форма
      ├── process.php         # сохранение данных в БД
      ├── style.css
      └── script.js