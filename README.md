## Introduction

FestDispatcher permet de gérer facilement des bénévoles lors d'un festival.

## Getting Started

Make sur your .env is configured then run :

```bash
composer install
yarn install
yarn encore dev
bin/console doctrine:database:create
bin/console doctrine:schema:update
bin/console doctrine:fixtures:load
```

## One Line Update Command

Do the stuff above

```bash
bin/console app:update
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