# Swoole MySQL Lock

🔒 Experimenting MySQL locks for concurrent Swoole processes.

```sql
CREATE TABLE IF NOT EXISTS foo (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bar VARCHAR(255) NOT NULL,
    baz INT NOT NULL DEFAULT 0,
    ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO foo (bar) VALUES ('alice'), ('bob'), ('clair'), ('dean'), ('eva'), ('felip'), ('gaby');
```

```shell
Starting worker 0
Starting worker 1
Worker #0 => alice, bob
Worker #1 => clair, dean
Worker #0 => eva, felip
Worker #1 => gaby
```