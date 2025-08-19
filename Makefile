.PHONY: clean composer build start stop

clean:
	@rm -rf vendor composer.lock
	@echo "✅ Cleaned vendor and composer.lock"

composer:
	@composer install
	@echo "✅ Installed Composer dependencies"

env:
	@php env.php
	@echo "✅ ENV Setup"

build: clean composer env
	@docker compose up --build -d
	@echo "🚀 Build completed and containers are running. Access the application on https://localhost:8080"

start:
	@docker compose start
	@echo "▶️ Containers started"

stop:
	@docker compose stop
	@echo "⏹️ Containers stopped"