#!/bin/bash

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose не установлен. Установите его перед запуском."
    exit 1
fi

if [ ! -f "backend/.env" ]; then
    if [ -f "backend/.env.example" ]; then
        echo "⚠️ Файл .env не найден. Создаём из .env.example..."
        cp backend/.env.example backend/.env

        sed -i 's/DB_HOST=localhost/DB_HOST=db/' backend/.env
        sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' backend/.env
        echo "✅ Настройки DB_HOST обновлены для Docker"
    else
        echo "❌ Ошибка: Не найден файл .env.example. Пожалуйста, создайте его в директории backend/"
        exit 1
    fi
else
    echo "✅ Файл .env уже существует"

    if grep -q "DB_HOST=localhost" backend/.env || grep -q "DB_HOST=127.0.0.1" backend/.env; then
        echo "⚠️ Обнаружена неправильная настройка DB_HOST. Исправляем..."
        sed -i 's/DB_HOST=localhost/DB_HOST=db/' backend/.env
        sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' backend/.env
        echo "✅ DB_HOST обновлен на db"
    fi
fi

echo "🚀 Запуск контейнеров..."
docker-compose up -d || {
    echo "❌ Ошибка при запуске контейнеров"
    exit 1
}

echo "⏳ Проверка готовности PostgreSQL..."
while ! docker-compose exec -T db pg_isready -U laravel -d laravel_db; do
    sleep 1
done
echo "✅ PostgreSQL готов к работе"

echo "📦 Устанавливаем зависимости Composer..."
if ! docker-compose exec -T php composer install; then
    echo "❌ Ошибка при установке зависимостей Composer"
    docker-compose down 2>/dev/null
    exit 1
fi
echo "✅ Зависимости Composer установлены"

# Выполняем миграции
echo "🔄 Выполняем миграции базы данных..."
if ! docker-compose exec -T php php artisan migrate --force; then
    echo "❌ Ошибка при выполнении миграций"
    docker-compose down 2>/dev/null
    exit 1
fi
echo "✅ Миграции выполнены успешно"

echo "📋 Статус контейнеров:"
docker-compose ps