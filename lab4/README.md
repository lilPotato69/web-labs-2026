# 🧑‍💻 Лабораторная работа №4

**Тема:** Composer, классы и работа с публичным API.
**Вариант:** 8. Запись на экскурсию (API достопримечательностей OpenTripMap).
**Статус:** Выполнено основное задание + все штрафные задания.

---

## 🎯 Цель работы

1. Освоить Composer и структуру `vendor`.
2. Научиться работать с классами и внешними библиотеками.
3. Научиться получать и отображать данные из публичного API.
4. Отработать работу с куками и пользовательской информацией.

---

## 🛠 Стек технологий

* **Docker / Docker Compose**
* **Nginx** (1.27-alpine)
* **PHP-FPM** (8.2-fpm)
* **Composer** (управление зависимостями)
* **Guzzle** (HTTP-клиент для PHP)
* **OpenTripMap API** (публичный API достопримечательностей)
* **HTML5 / CSS3 / JavaScript (Vanilla, fetch)**

---

## 📁 Структура проекта

```text
lab4/
 ├── Dockerfile              # PHP с расширениями и Composer
 ├── docker-compose.yml
 ├── nginx/
 │    └── default.conf
 └── www/
      ├── composer.json
      ├── composer.lock
      ├── vendor/            # создаётся автоматически (не коммитится)
      ├── ApiClient.php      # класс для работы с внешним API
      ├── UserInfo.php       # класс с информацией о пользователе
      ├── api_refresh.php    # AJAX-эндпоинт для обновления API
      ├── index.php
      ├── form.html
      ├── process.php
      ├── view.php
      ├── style.css
      ├── script.js
      ├── data.txt
      └── api_cache.json     # кеш API-ответов (не коммитится)