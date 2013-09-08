Database setup instructions
===========================
MySQL or MariaDB (theoretically) database is required.

If you don't have database created, connect to mysql server using
```bash
mysql -u root -p
```
and then executing
```sql
Create User user Identified By 'password';
Create Database arduinows;
Grant All On arduinows.* To user;
Flush Privileges;
```
Login to database server and execute the statements:

```bash
$ mysql -h localhost -D arduinows -u user -p < arduinows.sql
Enter password:
$
```

Once you have set up the database, copy `api/db.config.example.php` to `api/db.config.php` and change the database settings.