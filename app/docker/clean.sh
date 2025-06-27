#!/bin/bash
echo "Fully removes containers, volumes, and networks — useful when things are broken and you want a fresh start. Caution: this deletes DB data."
docker compose down -v --remove-orphans
