For https://bugs.php.net/bug.php?id=79872

There's an issue with PHP's pdo_mysql driver where commits are rolled back.
Exists on 7.0, 7.1, 7.2 and 7.4. Works fine on 5.6. Earliest version introduced
in is 7.0.23.


```
$ docker-compose up --build

...

db_1           | 2020-07-18 12:21:25 0 [Note] mysqld: ready for connections.
db_1           | Version: '10.5.4-MariaDB-1:10.5.4+maria~focal'  socket: '/run/mysqld/mysqld.sock'  port: 3306  mariadb.org binary distribution
cli-failing    | Test case c bool(false)
cli-passing    | Test case c bool(true)
```



# PHP diffs between versions

Just one commit related specifically to `ext/pdo` and `ext/pdo_mysql`

```
* 51cdd3dc50  Fix check of mysql_commit() return value YaoGuai (3 years ago)
  
  diff --git a/ext/pdo_mysql/mysql_driver.c b/ext/pdo_mysql/mysql_driver.c
  index 1bf4eb039a..d5052479ef 100644
  --- a/ext/pdo_mysql/mysql_driver.c
  +++ b/ext/pdo_mysql/mysql_driver.c
  @@ -327,7 +327,7 @@ static int mysql_handle_commit(pdo_dbh_t *dbh)
        PDO_DBG_ENTER("mysql_handle_commit");
        PDO_DBG_INF_FMT("dbh=%p", dbh);
   #if MYSQL_VERSION_ID >= 40100 || defined(PDO_USE_MYSQLND)
  -     PDO_DBG_RETURN(0 <= mysql_commit(((pdo_mysql_db_handle *)dbh->driver_data)->server));
  +     PDO_DBG_RETURN(0 == mysql_commit(((pdo_mysql_db_handle *)dbh->driver_data)->server));
   #else
        PDO_DBG_RETURN(0 <= mysql_handle_doer(dbh, ZEND_STRL("COMMIT")));
   #endif
```
