# Calendar
ProjetWeb / Calendar

1. Mettre à jour le DATABASE_URL dans le fichier .env
2. Créer la base de données à l'aide de `php bin/console doctrine:create:database`
3. Charger la fixture pour créer l'utilisateur admin `php bin/console doctrine:fixtures:load --append`
4. Charger la base de donnée avec `php bin/console doctrine:migrations:migrate`
5. Installer composer préalablement telechargé avec `composer install`