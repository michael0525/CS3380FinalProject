# CS3380FinalProject - Tigers Movie Database
## Authors: Patrick Kunza (pskggc), Ryan Olson (raozp4), Chase Scanlan (cwswx7), Michael Yizhuo Du (ydypb), Jay Toebbon (ejtn78)
###  Tigers Movie Database is a database of movies and their information. The available information for each movie is as follows: Title, Genre, Rating, Year released, Director, Actors, Summary. 
###  Tigers Movie Database supports guest access and user login access. For guest access, guest users can view all the movie information but cannot edit, add, or delete the information in the movie database. For user access, there are two kinds of user access: Admin (Administrator) and User (normal user). For Admin account, the user can view, edit, add and delete all movie information, including the movie that other users add to the movie database. For User accounts, the user can edit, add and delete the movie records that the user him/herself created before. A normal user cannot edit the movie record that other users created. 

## Schemas:   
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


CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	loginID varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	firstName varchar(128) NOT NULL,
	lastName varchar(128) NOT NULL,
	userAccess ENUM('User','Admin') DEFAULT 'Admin'
);

(The schemas are also in Schema.md)

## Entity Relationship Diagram for Tigers Movie Database:

The ERD for Tigers Movie Database is located in the folder "Supporting Files".

## Explanation of CRUD in Tiger Movie Database:

Create: Admin account and User accounts can add movie records into the database.

Read: Guest and user access can view the movie information in the database.

Update: Admin account and User accounts can edit movie records into the database.

Delete: Admin account and User accounts can delete movie records into the database.

## Video demostration for Tigers Movie Database Web Application:

https://youtu.be/3YoBjfjeKF4



