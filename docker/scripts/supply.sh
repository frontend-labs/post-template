#! /bin/bash

YARN_CACHE_DIR=~/yarn_cache/
ARGS=$@

if [ ! -d $YARN_CACHE_DIR ]; then
  echo "Creating ... ${YARN_CACHE_DIR} folder."
  mkdir $YARN_CACHE_DIR
  chmod -R 775 $YARN_CACHE_DIR
fi

if [ -z "$ARGS" ]; then
  ARGS=dependencies
fi

# supply
docker-compose -f ./docker/compose/supply.yml run --user $(whoami) --rm $ARGS
