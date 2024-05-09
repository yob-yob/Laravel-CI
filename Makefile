.PHONY: build-8.3 build-8.2 build-8.1

build-8.3:
	@echo "Building PHP 8.3 image..."
	docker build -t sean1999/base-php-ci:8.3 \
	  --build-arg BASE_PHP_IMAGE=php:8.3  \
	  --platform=linux/amd64 \
	  --no-cache Images/php/Base  2>&1 | tee Logs/8.3-build.log
	docker push sean1999/base-php-ci:8.3

build-8.2:
	@echo "Building PHP 8.2 image..."
	docker build -t sean1999/base-php-ci:8.2 \
	  --build-arg BASE_PHP_IMAGE=php:8.2  \
	  --platform=linux/amd64 \
	  --no-cache Images/php/Base  2>&1 | tee Logs/8.2-build.log
	docker push sean1999/base-php-ci:8.2

build-8.1:
	@echo "Building PHP 8.1 image..."
	docker build -t sean1999/base-php-ci:8.1 \
	  --build-arg BASE_PHP_IMAGE=php:8.1  \
	  --platform=linux/amd64 \
	  --no-cache Images/php/Base  2>&1 | tee Logs/8.1-build.log
	docker push sean1999/base-php-ci:8.1
