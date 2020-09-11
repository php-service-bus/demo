help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Run: make <target> where <target> is one of the following'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

reset: ## Reset all data
	@docker-compose -f docker-compose.yml down --volumes

start: ## Run supporting application containers
	@docker-compose -f docker-compose.yml up --build -d

consumer-logs: ## Display consumer logs
	@docker logs service-bus-demo-consumer -f

consumer-restart: ## Restart consumer
	@docker restart service-bus-demo-consumer
	@docker logs service-bus-demo-consumer -f

migrations-make: ## Make migrations file
	tools/migrations/make

migrations-up: ## Execute migrations
	@docker exec -it service-bus-demo-consumer /var/www/tools/migrations/migrate up

migrations-down: ## Rollback migrations
	@docker exec -it service-bus-demo-consumer /var/www/tools/migrations/migrate down

new-customer: ## Add new customer
	@docker exec -it service-bus-demo-consumer /var/www/tools/customer/registration

new-driver: ## Add new driver
	@docker exec -it service-bus-demo-consumer /var/www/tools/driver/registration

driver-document: ## Add document to driver
	@docker exec -it service-bus-demo-consumer /var/www/tools/driver/document/add

driver-vehicle: ## Add vehicle to driver
	@docker exec -it service-bus-demo-consumer /var/www/tools/driver/vehicle/add

.DEFAULT_GOAL := help
