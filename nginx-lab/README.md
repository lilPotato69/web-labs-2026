# Лабораторная работа №1: Nginx + Docker

## 👩‍💻 Автор
ФИО: Прошкин Глеб Сергеевич
Группа: 2ПМ-3

---

## 📌 Описание задания
Создать веб-сервер в Docker с использованием Nginx и подключить HTML-страницу.  
Результат доступен по адресу [http://localhost:3000](http://localhost:3000).

---

## ⚙️ Как запустить проект

1. Клонировать репозиторий:
   ```bash
   git clone https://github.com/lilPotato69/web-labs-2026
   cd nginx-lab
Запустить контейнеры:
```bash
docker-compose up -d --build
```
Открыть в браузере:
```http://localhost:8080```
📂 Содержимое проекта  

```docker-compose.yml``` — описание сервиса Nginx

```code/index.html``` — главная HTML-страница

```screenshots/``` — все скриншоты

📸 Скриншоты работы

<img width="583" height="243" alt="--version" src="https://github.com/user-attachments/assets/d99f4cb7-7f9d-4872-858f-6e8e432b44b9" />
<img width="2560" height="1536" alt="nginx2" src="https://github.com/user-attachments/assets/66947fae-0f9c-4a8b-b20f-3798423e0da9" />
<img width="1464" height="249" alt="nginx1" src="https://github.com/user-attachments/assets/33673477-650c-483e-906d-03bba04ad77d" />
<img width="2560" height="1530" alt="html" src="https://github.com/user-attachments/assets/21c24c8a-0a82-4bc6-a5a5-fc3037399fe8" />
<img width="2560" height="1537" alt="experiment" src="https://github.com/user-attachments/assets/37a70fd7-76cd-4d58-b648-a2ee4d2abf3c" />
<img width="2560" height="1533" alt="about" src="https://github.com/user-attachments/assets/a017b970-4d68-40c7-8163-7eb5e701a48e" />
<img width="2560" height="1536" alt="port change" src="https://github.com/user-attachments/assets/ac79c675-cdc2-4fcb-8f4d-41ec0758ebbc" />

✅ Результат
Сервер в Docker успешно запущен, Nginx отдаёт мою HTML-страницу.
