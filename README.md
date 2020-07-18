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

