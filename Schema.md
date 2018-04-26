CREATE TABLE movie(
 id int not null primary key auto_increment,
 title varchar(60),
 summary varchar(511),
 releaseYear year,
 director varchar(60),
 actors varchar(255),
 genre ENUM('Action','Comedy','Drama','Horror','Adult','SciFi','Western','uncategorized') DEFAULT 'uncategorized',
 MPAA ENUM('G','PG','PG-13','R','NC-17','not rated')DEFAULT 'not rated'
)

CREATE TABLE moviefinance(
 id int not null primary key auto_increment,
 title varchar(60),
 movieid int,
 budgetInMil float,
 boxOfficeInMil float
);


CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	loginID varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	firstName varchar(128) NOT NULL,
	lastName varchar(128) NOT NULL,
  userAccess ENUM('User','Admin') DEFAULT 'Admin'
);
