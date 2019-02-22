CREATE TABLE _users 
(
    _id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    _firstname VARCHAR(30) NOT NULL,
    _lastname VARCHAR(30) NOT NULL,
    _username VARCHAR(30) NOT NULL,
    _email VARCHAR(50) NOT NULL,
    _phone VARCHAR(15),
    _dob VARCHAR(10),
    _password VARCHAR(255) NOT NULL,
    _image_name VARCHAR(255),
    _signup_date DATETIME,
    _last_modified_date DATETIME,
    _signed_in TINYINT(1) NOT NULL DEFAULT 0,
    _account_active TINYINT(1) NOT NULL DEFAULT 0,
    _hash VARCHAR(255) NOT NULL
);