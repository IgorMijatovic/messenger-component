composer install

symfony serve

php bin/console messanger:consume

php bin/console messenger:failed:show

php bin/console messenger:failed:retry

php bin/console debug:messenger
