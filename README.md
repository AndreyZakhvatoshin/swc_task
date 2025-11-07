# SWC Task Backend

## Описание
Это backend часть приложения SWC Task - системы управления задачами с поддержкой проектов. Приложение реализовано на Laravel с использованием Sanctum для аутентификации API.

## Структура API

### Аутентификация

#### POST /api/register
Регистрация нового пользователя.

**Параметры:**
- `name` (string) - имя пользователя
- `email` (string) - email пользователя
- `password` (string) - пароль
- `password_confirmation` (string) - подтверждение пароля

**Ответ:**
```json
{
  "token": "токен_аутентификации"
}
```

#### POST /api/login
Вход пользователя в систему.

**Параметры:**
- `email` (string) - email пользователя
- `password` (string) - пароль

**Ответ:**
```json
{
  "token": "токен_аутентификации"
}
```

#### GET /api/user
Получение информации о текущем пользователе (требует аутентификации).

**Ответ:**
```json
{
  "id": 1,
  "name": "Имя пользователя",
  "email": "email@example.com"
}
```

### Проекты и задачи

#### GET /api/projects/{project}/tasks
Получение списка задач проекта (требует аутентификации).

**Параметры фильтрации (опционально):**
- `title` - фильтрация по заголовку задачи
- `status` - фильтрация по статусу задачи

**Ответ:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Заголовок задачи",
      "description": "Описание задачи",
      "status": "planned",
      "user_id": 1
    }
  ]
}
```

#### POST /api/projects/{project}/tasks
Добавление новой задачи в проект (требует аутентификации).

**Параметры:**
- `title` (string) - заголовок задачи
- `description` (string) - описание задачи
- `status` (string) - статус задачи (planned, in_progress, done)
- `user_id` (int) - ID пользователя, которому назначена задача

**Ответ:**
```json
{
  "data": {
    "id": 1,
    "title": "Заголовок задачи",
    "description": "Описание задачи",
    "status": "planned",
    "user_id": 1
  }
}
```

### Задачи

#### GET /api/tasks/{task}
Получение информации о задаче (требует аутентификации).

**Ответ:**
```json
{
  "data": {
    "id": 1,
    "title": "Заголовок задачи",
    "description": "Описание задачи",
    "status": "planned",
    "user_id": 1
  }
}
```

#### PUT /api/tasks/{task}
Обновление задачи (требует аутентификации).

**Параметры:**
- `title` (string) - заголовок задачи
- `description` (string) - описание задачи
- `status` (string) - статус задачи (planned, in_progress, done)

**Ответ:**
```json
{
  "data": {
    "id": 1,
    "title": "Обновленный заголовок задачи",
    "description": "Обновленное описание задачи",
    "status": "in_progress",
    "user_id": 1
  }
}
```

#### DELETE /api/tasks/{task}
Удаление задачи (требует аутентификации).

**Ответ:**
Статус 204 No Content

## Тестирование

Проект включает полный набор feature тестов для всех API-маршрутов:

### AuthTest
- `testRegisterUserSuccessfully()` - тестирование успешной регистрации пользователя
- `testRegisterUserWithInvalidData()` - тестирование регистрации с невалидными данными
- `testLoginUserSuccessfully()` - тестирование успешного входа пользователя
- `testLoginUserWithInvalidCredentials()` - тестирование входа с неверными учетными данными
- `testGetAuthenticatedUser()` - тестирование получения информации об аутентифицированном пользователе
- `testGetAuthenticatedUserWithoutToken()` - тестирование получения информации о пользователе без токена

### ProjectTaskTest
- `testGetProjectTasksSuccessfully()` - тестирование получения задач проекта
- `testGetProjectTasksWithFilters()` - тестирование получения задач с фильтрацией
- `testGetProjectTasksWithoutAuthentication()` - тестирование получения задач без аутентификации
- `testAddTaskToProjectSuccessfully()` - тестирование добавления задачи в проект
- `testAddTaskToProjectWithInvalidData()` - тестирование добавления задачи с невалидными данными
- `testAddTaskToProjectWithoutAuthentication()` - тестирование добавления задачи без аутентификации

### TaskTest
- `testShowTaskSuccessfully()` - тестирование просмотра задачи
- `testShowTaskWithoutAuthentication()` - тестирование просмотра задачи без аутентификации
- `testUpdateTaskSuccessfully()` - тестирование обновления задачи
- `testUpdateTaskWithInvalidData()` - тестирование обновления задачи с невалидными данными
- `testUpdateTaskWithoutAuthentication()` - тестирование обновления задачи без аутентификации
- `testDeleteTaskSuccessfully()` - тестирование удаления задачи
- `testDeleteTaskWithoutAuthentication()` - тестирование удаления задачи без аутентификации

## Статусы задач
- `planned` - запланирована
- `in_progress` - в работе
- `done` - завершена

## Запуск проекта

1. Установите зависимости:
```bash
composer install
```

2. Настройте .env файл

3. Выполните миграции:
```bash
php artisan migrate
```

4. Запустите приложение:
```bash
php artisan serve
```

## Запуск тестов

```bash
php artisan test
```