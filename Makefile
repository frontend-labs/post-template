.DEFAULT_GOAL := help

setup: ## Build and Install
	sh ./docker/scripts/setup.sh

build: ## Build images
	sh ./docker/scripts/build.sh

install: ## Install dependencies
	sh ./docker/scripts/supply.sh

watch: ## Watch
	docker-compose run app yarn run watch

browser: ## Load page in a Browser
	docker-compose run app yarn run browser

up: ## Up services
	docker-compose up -d

uplog: ## Up services with logs
	docker-compose up

down: ## Stop and remove services
	docker-compose down

reload: ## Reload services
	docker-compose restart

list: ## List of current active services
	docker-compose ps

ssh: ## Connect to container
	docker-compose exec -it $(CONTAINER) sh

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'
