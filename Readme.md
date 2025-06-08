# BELIVEN GAME
by Emanuele Fantin

### Installazione

##### 1) Scaricare il repository in locale
```
git clone https://github.com/emanuelefantin/belivengame.git

cd belivengame
```

##### 2) Rinominare il file .env.example
Rinominare il file .env.example in .env


##### 3) Da terminale eseguire i seguenti comandi:

```
composer install --ignore-platform-reqs

sail up -d

sail artisan key:generate

sail artisan migrate:fresh

sail artisan reverb:start
```

3) Ora puoi aprire il browser a questo indirizzo e dirigere la tua Software House per conquistare il mondo! (http://localhost/)