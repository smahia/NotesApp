## Important

SQLite database does not support foreign keys. When updating an entity that has a foreign key, it will give an error. In that case, delete the migrations files and the database file and run the command for generating the migrations again.

## Populate database:

`php bin/console doctrine:fixtures:load`

By default the load command purges the database, removing all data from every table. To append your fixtures' data add the `--append` option. More information at: https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html

SQLite Viewer: https://inloop.github.io/sqlite-viewer/