.PHONY: build-base-php-image build-laravel-ci-image

php ?= 8.1
node ?= 22

build-all:
	$(MAKE) php=8.1 build-base-php-image
	$(MAKE) php=8.2 build-base-php-image
	$(MAKE) php=8.3 build-base-php-image
	$(MAKE) php=8.1 build-laravel-ci-image
	$(MAKE) php=8.2 build-laravel-ci-image
	$(MAKE) php=8.3 build-laravel-ci-image

build-base-php-image:
	@echo "Building sean1999/base-php-ci:$(php) image."
	docker build -t sean1999/base-php-ci:$(php) \
	  --build-arg BASE_PHP_IMAGE=php:$(php)  \
	  --platform=linux/amd64 \
	  Images/php/Base  2>&1 | tee Logs/$(php)-build.log
	@echo "Pushing sean1999/base-php-ci:$(php) image to docker hub."
	docker push sean1999/base-php-ci:$(php)

build-laravel-ci-image:
	@echo "Building sean1999/laravel-ci:$(php) image."
		docker build -t sean1999/laravel-ci:$(php) \
		--build-arg BASE_PHP_IMAGE=sean1999/base-php-ci:$(php)  \
		--build-arg NODE_VERSION=$(node) \
		--platform=linux/amd64 \
		Images/php  2>&1 | tee Logs/$(php)-laravel-build.log
	@echo "Pushing sean1999/laravel-ci:$(php) image to docker hub."
	docker push sean1999/laravel-ci:$(php)