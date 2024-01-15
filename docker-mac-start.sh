#!/bin/zsh

mutagen project terminate
docker-compose --env-file=.env.local up -d --build
mutagen project start
