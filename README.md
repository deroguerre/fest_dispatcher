## Introduction

FestDispatcher permet de gérer facilement des bénévoles lors d'un festival.

## Getting Started

```bash
composer install  
bin/console doctrine:database:create  
bin/console doctrine:schema:validate  
bin/console doctrine:schema:update  
npm install
```

## One Line Update Command

```bash
composer install &&
yarn install &&
bin/console doctrine:schema:drop --force &&
bin/console doctrine:schema:validate &&
bin/console doctrine:schema:update --force &&
bin/console doctrine:fixtures:load
```

## Dev

```bash
# compile assets once
yarn encore dev

# or, recompile assets automatically when files change
yarn encore dev --watch

# on deploy, create a production build
yarn encore production
```
## API
Documentattion : ../api/docs.json
## Storm shortcuts

show intention = ALT + ENTER  
reformat code =  CRTL + ALT + L  
move line up/down =  MAJ + ALT + UP or DOWN