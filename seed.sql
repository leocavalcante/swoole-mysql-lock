CREATE TABLE IF NOT EXISTS foo (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    bar VARCHAR(255) NOT NULL,
    baz INT NOT NULL DEFAULT 0,
    ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

TRUNCATE foo;
INSERT INTO foo (bar) VALUES ('alice'), ('bob'), ('clair'), ('dean'), ('eva'), ('felip'), ('gaby');
UPDATE foo SET baz = 0;