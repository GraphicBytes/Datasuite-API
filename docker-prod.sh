docker-compose --env-file .env  down
docker-compose --env-file .env up -d --build
docker image prune -f