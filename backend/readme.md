## Important

SQLite database does not support foreign keys. When updating an entity that has a foreign key, it will give an error. In that case, delete the migrations files and the database file and run the command for generating the migrations again.