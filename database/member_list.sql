CREATE TABLE member (
  member_id int(10) NOT NULL PRIMARY KEY,
  member_name char(20),
  member_gender char(1),
  member_BD date,
  member_date date
);

--DROP TABLE member
--Describe member
--SELECT * FROM member;

INSERT INTO member (member_id, member_name, member_gender, member_BD, member_date) VALUES
(1, 'Kara', 'F', '2000-03-04', '2015-08-17'),
(2, 'Connor', 'M', '1999-08-24', '2015-11-24'),
(3, 'Marceline', 'F', '2001-11-11', '2018-08-13'),
(4, 'Finn', 'M', '1996-06-06', '2019-12-26'),
(5, 'Violet', 'F', '1989-05-30', '2017-03-29'),
(6, 'Jafar', 'M', '1994-02-14', '2017-09-01'),
(7, 'Rika', 'F', '2001-02-22', '2014-09-27'),
(8, 'Sinbad', 'M', '1998-02-05', '2014-10-20'),
(9, 'Marcy', 'F', '2001-04-18', '2020-03-30'),
(10, 'Simon', 'M', '1998-12-13', '2014-06-07'),
(11, 'Harry', 'M', '1980-02-24', '2012-03-16'),
(12, 'Hermione', 'F', '1982-03-16', '2011-08-24'),
(13, 'Ron', 'M', '1983-04-17', '2011-05-23'),
(14, 'Ginny', 'F', '1986-12-04', '2014-07-26'),
(15, 'Sirius', 'M', '2002-09-30', '2020-08-08'),
(16, 'Athena', 'F', '1985-03-22', '2016-05-27'),
(17, 'Draco', 'M', '1986-10-24', '2010-09-23'),
(18, 'Hera', 'F', '2003-07-11', '2017-08-29'),
(19, 'Lucas', 'M', '1980-11-19', '2018-01-12'),
(20, 'Aphrodite', 'F', '1983-05-26', '2014-06-03');



