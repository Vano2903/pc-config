services: ### start services needed
	docker-compose up --build --remove-orphans -d db
.PHONY: services

up: ### start docker image following docker-compose
	docker-compose up --build --remove-orphans -d php
.PHONY: up

logs: ### attach app's logs from docker-compose
	docker-compose logs -f php
.PHONY: logs

down: ### stop all container created by docker-compose
	docker-compose down --remove-orphans
.PHONY: down

ps:
	docker-compose ps
.PHONY: ps