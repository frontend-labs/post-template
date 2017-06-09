#! /bin/bash
unset ENV_USER
unset ENV_GID
unset ENV_UID

export ENV_USER=$(whoami)
export ENV_GID=$(id -g)
export ENV_UID=$(id -u)

docker-compose -f ./docker/compose/build.yml build $1
