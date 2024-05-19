# for develop
dcup-build:
	docker-compose build

dcup-dev:
	docker-compose up -d

dc-clear:
	docker-compose down
	docker rmi -f $(docker images | grep luxuler)

dc-nuclear:
	- docker stop $$(docker ps -a -q)
	- docker kill $$(docker ps -q)
	- docker rm $$(docker ps -a -q)
	- docker rmi $$(docker images -q)
	- docker system prune --all --force --volumes