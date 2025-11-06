# Makefile для DaData Test Task

# Переменные
COMPOSE_FILE=compose.yml
DOCKER_COMPOSE=docker compose -f $(COMPOSE_FILE)
PHP_CONTAINER=php
DB_CONTAINER=db
NGINX_CONTAINER=nginx

# Основные цели
.PHONY: help install up down logs build rebuild shell artisan composer npm test clean migrate seed scribe

# Показать справку
help:
	@echo "DaData Test Task - Makefile"
	@echo ""
	@echo "Доступные команды:"
	@echo "  help       - Показать это сообщение"
	@echo "  install    - Установить зависимости (Composer)"
	@echo "  up         - Запустить контейнеры Docker"
	@echo "  down       - Остановить контейнеры Docker"
	@echo "  logs       - Показать логи контейнеров"
	@echo "  shell      - Войти в контейнер PHP"
	@echo "  artisan    - Выполнить команду Artisan (например, make artisan COMMAND='migrate')"
	@echo "  composer   - Выполнить команду Composer (например, make composer COMMAND='install')"
	@echo "  test       - Запустить тесты"
	@echo "  migrate    - Выполнить миграции"
	@echo "  seed       - Заполнить базу данных начальными данными"
	@echo "  scribe     - Сгенерировать документацию API с помощью Scribe"
	@echo "  clean      - Очистить проект"
	@echo ""
	@echo "Примеры:"  
	@echo "  make up"
	@echo "  make install"
	@echo "  make artisan COMMAND='migrate:fresh --seed'"
	@echo "  make composer COMMAND='dump-autoload'"
	@echo "  make scribe"

# Установка зависимостей
install: 
	@echo "=== Установка зависимостей ==="
	@echo "Установка PHP зависимостей (Composer)..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) composer install
	@echo "Установка Node.js зависимостей..."  
	cd backend && npm install
	@echo "Сборка ассетов..."
	cd backend && npm run build
	@echo "Завершено!"

# Запуск контейнеров
up:
	@echo "=== Запуск контейнеров ==="
	$(DOCKER_COMPOSE) up -d
	@echo ""
	@echo "Контейнеры запущены!"
	@echo "Приложение доступно по адресу http://localhost:8000"
	@echo ""

# Остановка контейнеров
down:
	@echo "=== Остановка контейнеров ==="
	$(DOCKER_COMPOSE) down
	@echo "Контейнеры остановлены"

# Показать логи
logs:
	@echo "=== Логи контейнеров ==="
	$(DOCKER_COMPOSE) logs -f

# Сборка проекта
build: up
	@echo "=== Сборка проекта ==="
	@echo "Ожидание запуска БД..."
	until $(DOCKER_COMPOSE) exec $(DB_CONTAINER) pg_isready > /dev/null 2>&1; do sleep 1; done
	@echo "Установка зависимостей..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) composer install
	@echo "Генерация ключа приложения..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan key:generate
	@echo "Выполнение миграций..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan migrate --force
	@echo "Сборка ассетов..."
	cd backend && npm run build

# Пересборка
rebuild:
	@echo "=== Пересборка контейнеров ==="
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) build --no-cache
	$(DOCKER_COMPOSE) up -d

# Выполнение команды Composer
composer: 
	@if [ -z "${COMMAND}" ]; then \
		echo "Ошибка: не указана команда"; \
		echo "Использование: make composer COMMAND='command args'"; \
		echo "Пример: make composer COMMAND='install'"; \
	else \
		$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) composer $(COMMAND); \
	fi

# Запуск тестов
test:
	@echo "=== Запуск тестов ==="
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan test

# Выполнение миграций
migrate:
	@echo "=== Выполнение миграций ==="
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan migrate

# Заполнение данными
seed:
	@echo "=== Заполнение базы данных начальными данными ==="
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan db:seed

# Очистка проекта
clean:
	@echo "=== Очистка проекта ==="
	@echo "Очистка кэша и временных файлов..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan cache:clear
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan config:clear
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan route:clear
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) php artisan view:clear
	@echo "Удаление временных файлов..."
	$(DOCKER_COMPOSE) run --rm $(PHP_CONTAINER) rm -rf bootstrap/cache/ *.log
	@echo "Проект очищен"