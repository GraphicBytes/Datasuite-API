docker-compose down
docker-compose --env-file local.env up -d --build
docker image prune -f