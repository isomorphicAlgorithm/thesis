#!/bin/bash
echo "Stopping and removing containers, but keeps volumes (so DB data stays)...."
docker compose down
