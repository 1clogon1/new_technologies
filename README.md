Запуск:
1. Скачать Docker.

2. Клонируем репозиторий:
	1) ssh - git@github.com:1clogon1/new_technologies.git; 
	2) https - https://github.com/1clogon1/new_technologies.git; 
	3) Скачать архив и распаковать его у себя.

3. Переходим в папку Laravel проекта в терминале(если не в ней находитесь): 
	cd .\new_technologies\

4. Запускаем:           
	composer install

5. Копируем env:          
	 cp .env.example .env

6. Добавляем данные для базы PostgreSQL и Redis:
	DB_CONNECTION=pgsql
	DB_HOST=db
	DB_PORT=5433
	DB_DATABASE=new_technologies
	DB_USERNAME=user
	DB_PASSWORD=password

	REDIS_HOST=redis
	REDIS_PORT=6379

7. Генерируем APP_KEY:           
	docker-compose exec app php artisan key:generate

8. Запускаем сборку и запуск контейнеров:          
 docker-compose up -d

9. Запускаем миграцию таблиц:
	docker exec -it new_technologies-php php artisan migrate:reset 

10. Запуск крона (для ежедневной подгрузки данных):
	1) Узнаем путь расположения проекта и копируем его: 
		pwd (для получения расположения проекта);
	2) Заходим в крон:
		crontab -e;
	3) Добавляем туда следующую строку, с вашим скопированным путем:
0 0 * * * cd /Users/../../../../new_technologies && php artisan fetch:appdata >> /dev/null 2>&1 (/Users/../../../../new_technologies - это пример, который вам нужно будет заменить на свой);
	3.1) Сохранение введенной строки
		Если редактор VI то нажимаем Esc и вводим потом :wq и нажимаем Enter, чтобы выйти;
		Если nano, то продумаем Ctrl + O и нажимаем Enter, после прижимаем Ctrl + X чтобы выйти.
	3.2) Чтобы проверить что крон активен введите:
		 ps aux | grep cron (После чего вы получите активный список крон записей).

Запросы:
1. Get запрос /appTopCategory - запрос для выдачи топа по категориям;
Входной параметр date, который имеет следующие ограничения:
	1) Поле не должно быть пустым;
	2) Поле должно быть в формате Y-m-d (2025-02-13);
	3) Дата не должна превышать сегодняшнюю.
При успешном запросе вы получите:
{
    "status_code": 200,
    "message": "ok",
    "data": {
        "23": 4
    }
}

2. Get запрос /addTopCategory - запрос для тестовой системы, чтобы не ждать 00:00;
Входной параметр нету, при успешном выполнение вы получите код 200 и сообщение - «success": "Data saved successfully".

	
