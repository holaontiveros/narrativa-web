ENV ?= production
SITE ?= lanarrativa.mx

deploy:
	@ansible-playbook deploy.yml -e "site=$(SITE) env=$(ENV)"

migrate-shared:
	@ansible-playbook migrate-shared.yml -e "site=$(SITE) env=$(ENV)"
