# STEEL_Robot_Turtle_Dashboard
Repo for Digital Ocean LAMP Webserver to see robotic turtle stats online.

Digital Ocean mySQL Database structure:
Tables:
- UserBoards
- Motors
- Servos
- RobotControllers


Option: Connect to MySQL: (mysql -u root -p) (then enter your password)
Note: There are three levels of password you will need to think about. This is the first, which gives you access
to MySQL from the command line.
Here are the steps to create the database, tables, and working data.

1. Create database

create database COP4331;
use COP4331;

3. Create tables


- CREATE TABLE `STEEL_Project`.`UserBoards`
(
`ID` INT NOT NULL AUTO_INCREMENT ,
`boardName` VARCHAR(50) NOT NULL DEFAULT '' ,
`Login` VARCHAR(50) NOT NULL DEFAULT '' ,
`Password` VARCHAR(50) NOT NULL DEFAULT '' ,
PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

- CREATE TABLE `STEEL_Project`.`Motors`
(
`ID` INT NOT NULL AUTO_INCREMENT ,
`MotorName` VARCHAR(50) NOT NULL DEFAULT '' ,
`Speed` VARCHAR(50) NOT NULL DEFAULT '' ,
`Pin1` VARCHAR(50) NOT NULL DEFAULT '' ,
`Pin2` VARCHAR(50) NOT NULL DEFAULT '' ,
`boardID` INT NOT NULL DEFAULT '0' ,
PRIMARY KEY (`ID`)
) ENGINE = InnoDB;



  3. Populate working data rows
USE COP4331;
insert into Users (FirstName,LastName,Login,Password) VALUES
('Aashish','Yadavally','AYadavally','COP4331');
insert into Users (FirstName,LastName,Login,Password) VALUES ('Sam','Hill','SamH','Test');
insert into Users (FirstName,LastName,Login,Password) VALUES
('Aashish','Yadavally','AYadavally','5832a71366768098cceb7095efb774f2');
insert into Users (FirstName,LastName,Login,Password) VALUES
('Sam','Hill','SamH','0cbc6611f5540bd0809a388dc95a615b');
insert into Colors (Name,UserID) VALUES ('Blue',1);
insert into Colors (Name,UserID) VALUES ('White',1);




Test with:
select * from Users;
select * from Colors;
also:
select * from Colors where UserID=1;
select * from Colors where UserID=3;

We will create a user:
Note: Remember, there are three levels of password you will need to think about. This is the second, which
allows the webapp (the API code) to run queries against MySQL.
Use COP4331;
create user 'TheBeast' identified by 'WeLoveCOP4331';
Now we need to grant permissions to the database for that user:
grant all privileges on COP4331.* to 'TheBeast'@'%';
The database is ready to use.
