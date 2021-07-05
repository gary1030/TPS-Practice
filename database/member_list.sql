CREATE TABLE member (
  member_id int(10) NOT NULL PRIMARY KEY,
  member_name VARCHAR(10),
  member_gender VARCHAR(1),
  member_BD date,
  member_date date
)

--DROP TABLE member
--Describe member
--SELECT * FROM member;

INSERT INTO member (member_id, member_name, member_gender, member_BD, member_date) VALUES
(1, 'Midti', 'F', '2000-03-04', '2015-08-17'),
(2, 'Iqhli', 'M', '1999-08-24', '2015-11-24'),
(3, 'Kypbn', 'F', '2001-11-11', '2018-08-13'),
(4, 'Konvt', 'M', '1996-06-06', '2019-12-26'),
(5, 'Gagec', 'F', '1989-05-30', '2017-03-29'),
(6, 'Wdasp', 'M', '1994-02-14', '2017-09-01'),
(7, 'Tatsj', 'F', '2001-02-22', '2014-09-27'),
(8, 'Qfyea', 'M', '1998-02-05', '2014-10-20'),
(9, 'Npljo', 'F', '2001-04-18', '2020-03-30'),
(10, 'Qmokg', 'M', '1998-12-13', '2014-06-07'),
(11, 'Ycqwn', 'M', '1980-02-24', '2012-03-16'),
(12, 'Xbnkl', 'F', '1982-03-16', '2011-08-24'),
(13, 'Yntgu', 'M', '1983-04-17', '2011-05-23'),
(14, 'Kqyug', 'F', '1986-12-04', '2014-07-26'),
(15, 'Svcvb', 'M', '2002-09-30', '2020-08-08'),
(16, 'Pzclx', 'F', '1985-03-22', '2016-05-27'),
(17, 'Itweb', 'M', '1986-10-24', '2010-09-23'),
(18, 'Vcxhp', 'F', '2003-07-11', '2017-08-29'),
(19, 'Fefmv', 'M', '1980-11-19', '2018-01-12'),
(20, 'Umbqe', 'F', '1983-05-26', '2014-06-03');


