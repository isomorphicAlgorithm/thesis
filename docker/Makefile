# Makefile for Docker shortcuts

.PHONY: up down restart clean

up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d --build

clean:
	docker compose down -v --remove-orphans

