up:
	docker compose up --build -d

test:
	docker compose exec app php artisan test

down:
	docker compose down
