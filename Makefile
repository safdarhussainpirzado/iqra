.PHONY: build up down restart migrate seed fresh cache optimize test lint queue scheduler logs shell backup restore clean

# Build and start the environment
build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose restart

# Database operations
migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

# Application optimization
cache:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

optimize:
	docker-compose exec app php artisan optimize

# Testing and Quality
test:
	docker-compose exec app php artisan test

lint:
	docker-compose exec app php -l $(find app -name "*.php")

# Worker management
queue:
	docker-compose restart queue

scheduler:
	docker-compose restart scheduler

logs:
	docker-compose logs -f

shell:
	docker-compose exec app sh

# Maintenance
backup:
	docker-compose exec mysql mysqldump -u root -p root iqra > backup.sql

restore:
	docker-compose exec mysql mysql -u root -p root iqra < backup.sql

clean:
	docker-compose down -v
	rm -rf storage/framework/cache/* storage/framework/sessions/*
