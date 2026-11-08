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

create database STEEL_Turtle;
use STEEL_Turtle;

3. Create tables




- CREATE TABLE `STEEL_Turtle`.`UserBoards`
(
`ID` INT NOT NULL AUTO_INCREMENT ,
`boardName` VARCHAR(50) NOT NULL DEFAULT '' ,
`Login` VARCHAR(50) NOT NULL DEFAULT '' ,
`Password` VARCHAR(50) NOT NULL DEFAULT '' ,
PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

- CREATE TABLE `STEEL_Turtle`.`Motors`
(
`ID` INT NOT NULL AUTO_INCREMENT ,
`MotorName` VARCHAR(50) NOT NULL DEFAULT '' ,
`Speed` INT NOT NULL ,
`Direction` INT NOT NULL ,
`boardID` INT NOT NULL DEFAULT '0' ,
PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

- CREATE TABLE `STEEL_Turtle`.`Servos`
(
`ID` INT NOT NULL AUTO_INCREMENT ,
`ServoName` VARCHAR(50) NOT NULL DEFAULT '' ,
`Angle` INT NOT NULL ,
`boardID` INT NOT NULL DEFAULT '0' ,
PRIMARY KEY (`ID`)
) ENGINE = InnoDB;





  3. Populate working data rows
USE STEEL_Turtle;
insert into UserBoards (boardName,Login,Password) VALUES
('STEEL_Turtle_Robot','STEEL_Turtle','12345678');

insert into Colors (Name,UserID) VALUES ('Blue',1);
insert into Colors (Name,UserID) VALUES ('White',1);




Test with:
select * from UserBoards;
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
