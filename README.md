# Todo Application

## Установка

### Быстрый старт

```bash
# Клонируйте репозиторий
git clone <repository-url>
cd todo.test

make setup
```

Этот команда выполнит:
- Сборку Docker контейнеров
- Установку зависимостей backend и frontend
- Публикацию конфигураций Laravel пакетов
- Запуск миграций базы данных

### Ручная установка

Если у вас нет Make, выполните команды вручную:

```bash
# 1. Соберите контейнеры
docker-compose up -d --build

# 2. Установите зависимости backend
docker-compose exec -w /var/www/backend app composer install
docker-compose exec -w /var/www/backend app php artisan key:generate

# 3. Установите зависимости frontend
docker-compose exec -w /var/www/frontend app npm install

# 4. Публикация конфигураций
docker-compose exec -w /var/www/backend app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
docker-compose exec -w /var/www/backend app php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# 5. Запустите миграции
docker-compose exec -w /var/www/backend app php artisan migrate

```

## Использование

### Доступные команды Make

#### Docker команды
```bash
make build      # Собрать контейнеры
make up         # Запустить контейнеры
make down       # Остановить контейнеры
make restart    # Перезапустить контейнеры
make logs       # Просмотр логов
make shell      # Доступ к shell PHP контейнера
```

#### Backend команды
```bash
make install           # Установить зависимости backend
make migrate           # Запустить миграции
make queue             # Запустить worker очереди
make clear             # Очистить все кэши
```

#### Frontend команды
```bash
make install-frontend  # Установить зависимости frontend
make dev-frontend      # Запустить dev-сервер frontend
make build-frontend    # Собрать frontend для production
```

### Доступ к приложению

После запуска контейнеров:

- **Backend API**: http://localhost:8080
- **Frontend Dev Server**: Запустите `make dev-frontend` (обычно на порту 5173)
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

## Структура проекта

```
todo.test/
├── backend/           # Laravel приложение
│   ├── app/          # Код приложения
│   ├── config/       # Конфигурации
│   ├── database/     # Миграции и сидеры
│   ├── routes/       # Маршруты API
│   └── tests/        # Тесты
├── frontend/         # Vue.js приложение
│   ├── src/         # Исходный код
│   │   ├── api/     # API клиент
│   │   ├── components/  # Vue компоненты
│   │   ├── stores/  # Pinia stores
│   │   └── views/   # Страницы
│   └── public/      # Статические файлы
├── docker/          # Docker конфигурации
│   ├── app/        # PHP Dockerfile и конфиги
│   └── nginx/      # Nginx конфигурация
├── redis/          # Redis конфигурация
├── docker-compose.yml  # Docker Compose конфигурация
└── Makefile        # Команды для управления проектом
```

## API Endpoints

### Аутентификация
- `POST /api/register` - Регистрация пользователя
- `POST /api/login` - Вход в систему
- `POST /api/logout` - Выход (требует аутентификации)

### Задачи
- `GET /api/tasks` - Получить список задач
- `POST /api/tasks` - Создать задачу
- `GET /api/tasks/{id}` - Получить задачу
- `PUT /api/tasks/{id}` - Обновить задачу
- `DELETE /api/tasks/{id}` - Удалить задачу
- `PATCH /api/tasks/{id}/toggle` - Переключить статус выполнения

## Переменные окружения

Основные переменные окружения настраиваются в `docker-compose.yml`:

- `POSTGRES_DB`: todo_test_db
- `POSTGRES_USER`: postgres
- `POSTGRES_PASSWORD`: 174471

