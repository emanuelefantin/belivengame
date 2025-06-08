# BELIVEN GAME
by Emanuele Fantin

### Installazione

Per velocizzare il processo di testing è già stato eseguito il build degli assets.

##### 1) Scarica il repository in locale
```
git clone https://github.com/emanuelefantin/belivengame.git

cd belivengame
```

##### 2) Rinomina il file .env.example
Rinominare il file .env.example in .env

```
mv .env.example .env
```

##### 3) Esegui i seguenti comandi

```
composer install --ignore-platform-reqs

sail up -d

sail artisan key:generate

sail artisan migrate:fresh

sail artisan reverb:start
```

##### 4) Gioca!
Ora puoi aprire il browser a questo indirizzo e dirigere la tua Software House per conquistare il mondo! (http://localhost/)