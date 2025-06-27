#!/bin/bash
echo "Stops, rebuilds, and restarts everything — useful when you change Dockerfiles or configs."
docker compose down
docker compose up -d --build
