CREATE TABLE test(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    value_string TEXT,
    value_integer INTEGER,
    value_float REAL
);
INSERT INTO test(id, value_string, value_integer, value_float) VALUES (1, '1.2345', 1, 1.2345);