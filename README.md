## Introduction

[![Build Status](https://travis-ci.org/deroguerre/fest_dispatcher.svg?branch=dev)](https://travis-ci.org/deroguerre/fest_dispatcher)

FestDispatcher permet de gérer facilement des bénévoles lors d'un festival.

## Getting Started

```bash
composer install  
bin/console doctrine:database:create  
bin/console doctrine:schema:validate  
bin/console doctrine:schema:update  
npm install
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

## Storm shortcuts

show intention = ALT + ENTER  
reformat code =  CRTL + ALT + L  
move line up/down =  MAJ + ALT + UP or DOWN