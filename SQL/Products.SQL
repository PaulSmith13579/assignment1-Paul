create table IF NOT EXISTS products
(
    id INTEGER
constraint products_pk
primary key autoincrement,
ProductName TEXT,
category TEXT,
quantity INTEGER,
price REAL,
image TEXT,
code TEXT
);