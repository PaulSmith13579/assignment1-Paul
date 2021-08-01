Create table IF NOT EXISTS  user
(
    User_id INTEGER not null
primary key autoincrement,
username TEXT,
Password TEXT,
Name TEXT,
profilePic TEXT,
accessLevel TEXT
);