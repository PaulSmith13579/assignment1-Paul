create table IF NOT EXISTS orderDetails
(
    orderDetails_id INTEGER NOT NULL
primary key autoincrement,
orderCode TEXT not null,
customerID INTEGER not null,
productCode TEXT not null,
orderDate DATETIME,
quantity INTEGER,
status TEXT default 'OPEN' not null
);