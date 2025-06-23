# Тестовое задание: API для каталога продуктов на Laravel

Это реализация тестового задания для PHP-разработчика, представляющая собой RESTful API для управления продуктами и категориями. Проект построен на фреймворке **Laravel 10** и полностью контейнеризирован с помощью **Docker**.<br>

# 📋 Техническое задание
1.Необходимо реализовать CRUD для продуктов на Laravel.

2.В качестве базы данных использовать PostgreSQL.

3.Для авторизации использовать JWT-аутентификацию.

4.Модель Category должна содержать следующие поля: title, description, image.

5.Модель Product должна содержать поля: title, price, image, description.

6.В качестве поискового движка использовать Elasticsearch — продукты должны индексироваться в нём.

7.Поиск продуктов должен выполняться через Elasticsearch.

8.Приложение должно быть запущено через Docker Compose.


## 🧰 Ключевые технологии

- **Бэкенд**: PHP 8.1, Laravel 10
- **База данных**: PostgreSQL 13
- **Поисковый движок**: Elasticsearch 7.17
- **Аутентификация**: JWT (`tymon/jwt-auth`)
- **Инфраструктура**: Docker, Docker Compose

## 🏗️ Архитектура проекта

Проект использует многослойную архитектуру:

- **Controllers** – приём HTTP-запросов и возврат ответов
- **Services** – бизнес-логика приложения
- **Repositories** – абстракция над Eloquent и Elasticsearch
- **DTOs** – строго типизированные передачи данных (с помощью `spatie/laravel-data`)
- **API Resources** – форматирование ответов API

---

## 📦 Требования к окружению

- Docker
- Docker Compose

---

## 🚀 Установка и запуск

> Рекомендуется запускать проект в **WSL 2** (на Windows) для максимальной производительности.

### 1. Клонирование репозитория

```bash
git clone <URL-вашего-репозитория>
cd <название-папки-проекта>
```
### 2. Настройка переменных окружения
Создайте `.env` в корне проекта (для Docker Compose):
```bash
cp .env.example .env
 ```
Создайте .env в папке src/ (для Laravel):
```bash
cp src/.env.example src/.env
```
### 3.Сборка и запуск контейнеров
   ```bash
   docker-compose up -d --build
```
### 4. Установка PHP-зависимостей
```bash
   docker-compose exec app composer install
```
### 5. Настройка Laravel
# Генерация ключа приложения
   ```bash
docker-compose exec app php artisan key:generate
```
# Генерация JWT-секрета
```bash
docker-compose exec app php artisan jwt:secret
```
### 6. Настройка базы данных
   ```bash
   docker-compose exec app php artisan migrate:fresh --seed
   ```
Создаются таблицы и заполняются тестовыми данными (10 категорий и 1000 продуктов).

### 7. Настройка Elasticsearch
   Необходимо один раз вручную создать индексы.
Создание индекса для продуктов:
```bash
curl -X PUT "http://localhost:9200/products_index"
```
Создание индекса для категорий:

```bash 
curl -X PUT "http://localhost:9200/categories_index"
```
### 8. Индексация данных в Elasticsearch
   ```bash
   docker-compose exec app php artisan search:reindex
   ```
   После этого проект доступен по адресу:
   🔗 http://localhost:8000/api/

## 📚 Документация API

Все защищённые маршруты требуют Bearer Token в заголовке:

```http
Authorization: Bearer <your_jwt_token>
```
🔐 3.1 Аутентификация<br>
✅ Регистрация<br>
URL: POST /auth/register<br>
Авторизация: ❌

Тело запроса:

```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "password123"
}
```
Успешный ответ (201 Created):

```json
{
  "access_token": "eyJ...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "created_at": "...",
    "updated_at": "..."
  }
}
```
🔐 Вход
URL: POST /auth/login
Авторизация: ❌

Тело запроса:
```json
{
"email": "john.doe@example.com",
"password": "password123"
}
```
Ответ: такой же, как при регистрации.

🚪 Выход<br>
URL: POST /auth/logout <br>
Авторизация: ✅

Ответ:
```json
{
"message": "Successfully logged out"
}
```
📦 3.2 Продукты
Все маршруты требуют аутентификации.

📄 Получение списка продуктов
URL: GET /products
Параметры:

page — номер страницы

search — строка поиска (через Elasticsearch)

Пример:

```http
GET /api/products?search=Laptop
```
Ответ:
```json
{
"data": [
    {
        "id": 1,
        "title": "Super Laptop Pro",
        "price": "2500.50",
        "description": "...",
        "image": "...",
        "category": {
        "id": 5,
        "title": "Electronics",
        "description": "...",
        "image": "..."
    }
    }
],
    "links": { ... },
    "meta": { ... }
}
```
➕ Создание продукта<br>
URL: POST /products

Тело запроса:
```json
{
"title": "New Gadget",
"price": 199.99,
"description": "A very useful gadget.",
"image": "http://example.com/image.png",
"category_id": 1
}
```
Ответ (201 Created): данные нового продукта

🔍 Получение одного продукта<br>

URL: GET /products/{id}

✏️ Обновление продукта<br>
URL: PUT /products/{id} или PATCH /products/{id}

Тело запроса:
```json
{
"title": "Updated Gadget Name",
"price": 249.99
}
```
❌ Удаление продукта<br>
URL: DELETE /products/{id}<br>
Ответ: 204 No Content

🗂️ 3.3 Категории
Все маршруты требуют аутентификации.

📄 Получение списка категорий<br>
URL: GET /categories<br>

Параметры:

search — необязательная строка поиска (если реализовано)

📌 Остальные действия с категориями (создание, обновление, удаление) реализуются аналогично продуктам.
