-- log in info
-- 		username: ora_kn2001@stu
--  	password: a97703045

-- WE DO NOT HAVE ON UPDATE CASCADE - ask TA what to do
-- TODO: implement on update cascade

-- anything that references something else need to get dropped first
DROP TABLE Recruiter; 
DROP TABLE WorkExperience;
DROP TABLE HasHashtag;
DROP TABLE HiringCompany;
DROP TABLE ApplyTo;
DROP TABLE ReceivedBy;
DROP TABLE JobPost;
DROP TABLE Contains;
DROP TABLE Messages;


DROP TABLE Company;
DROP TABLE Municipality;
DROP TABLE District;
DROP TABLE Hashtag;
DROP TABLE JobDesc;
DROP TABLE userTable;



CREATE TABLE userTable(
	uname CHAR(40),
	age INT,
	userId INT, -- change from uid since that was a keyword and change all instaces
    iid INT,
    PRIMARY KEY (userId),
    UNIQUE (iid)
);

CREATE TABLE Recruiter(
	userId INT,
	passEndDate DATE, 
	PRIMARY KEY(userId),
	FOREIGN KEY(userId) REFERENCES userTable
		ON DELETE CASCADE
        -- ON UPDATE CASCADE
);


CREATE TABLE Messages( -- change from Messages since that was a keyword and changed all instaces 
	mid INT,
	mbody CHAR(2000),
	messageDate DATE,
	userId INT NOT NULL,
	PRIMARY KEY(mid),
	FOREIGN KEY(userId) REFERENCES userTable
		ON DELETE CASCADE
		-- ON UPDATE CASCADE (need to implement cascade still)
);

CREATE TABLE Company(
	cname CHAR(200),
	postalCode CHAR(20),
	PRIMARY KEY(cname)
);

CREATE TABLE WorkExperience(
	etitle CHAR(200),
    eId INT,
	userId INT,
	startDate DATE,
	endDate DATE,
    cname CHAR(200),
	PRIMARY KEY(userId, eId),
	FOREIGN KEY(userId) REFERENCES userTable
		ON DELETE CASCADE,
		-- ON UPDATE CASCADE
    FOREIGN KEY(cname) REFERENCES Company
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);

CREATE TABLE Municipality(
	postalCode CHAR(20),
	city CHAR(60),
	PRIMARY KEY(postalCode)
);

CREATE TABLE District(
	postalCode CHAR(20),
	province CHAR(60),
	PRIMARY KEY(postalCode)
);

-- CREATE TABLE Hashtag(
-- 	hname CHAR(40),
-- 	PRIMARY KEY(hname)
-- );

CREATE TABLE Hashtag(
	hashtagID INT, -- added a new field, oracle_db not very good at recognizing chars as foreign keys so it couldn't find the hashtags instace
	-- also change FK in containsHashtag to be the hashtagID
	hname CHAR(40),
	PRIMARY KEY(hashtagID)
);

CREATE TABLE JobPost(
	pid INT,
	jobTitle CHAR(60),
	pbody CHAR(400),
	userId INT,
	PRIMARY KEY(pid),
	FOREIGN KEY(userId) REFERENCES userTable
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);

CREATE TABLE JobDesc(
	jobTitle CHAR(60),
	baseSalary INT,
	PRIMARY KEY(jobTitle)
);

CREATE TABLE Contains(
	userId INT,
	mid INT,
	PRIMARY KEY (userId, mid),
	FOREIGN KEY (userId) REFERENCES userTable
		ON DELETE CASCADE,
		-- ON UPDATE CASCADE,
	FOREIGN KEY (mid) REFERENCES Messages
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);


CREATE TABLE HasHashtag(
	pid INT,
	hashtagID INT, 
	PRIMARY KEY (pid, hashtagID),
	FOREIGN KEY (pid) REFERENCES JobPost
		ON DELETE CASCADE
		-- ON UPDATE CASCADE,
		,
	FOREIGN KEY (hashtagID) REFERENCES Hashtag
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);

CREATE TABLE HiringCompany(
	pid INT,
	cname CHAR(200),
	PRIMARY KEY (pid, cname),
	FOREIGN KEY (pid) REFERENCES JobPost
		ON DELETE CASCADE
		,
	FOREIGN KEY (cname) REFERENCES Company
		ON DELETE CASCADE
);

CREATE TABLE ApplyTo( --changed from Apply since that was keyword
	userId INT,
	pid INT,
	PRIMARY KEY (userId, pid),
	FOREIGN KEY (userId) REFERENCES userTable
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
		,
	FOREIGN KEY (pid) REFERENCES JobPost
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);

CREATE TABLE ReceivedBy(
	mid INT,
	userId INT,
	PRIMARY KEY (mid, userId),
	FOREIGN KEY (mid) REFERENCES Messages
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
		,
	FOREIGN KEY (userId) REFERENCES userTable
		ON DELETE CASCADE
		-- ON UPDATE CASCADE
);


-- STARTING INSERTION

-- User
INSERT INTO
   userTable 
VALUES
   (
      'Colton Quan', 20, 1, 1
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Kevin Nguyen', 21, 2, 2
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Amy Yung', 22, 3, 3
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'John Doe', 23, 4, 4
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Aubrey Graham', 24, 5, 5
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Bruce Wayne', 25, 6, 6
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Eveline Saiful', 26, 7, 7
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Sebastian Vettel', 27, 8, 8
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Lebron James', 28, 9, 9
   )
;

INSERT INTO
   userTable 
VALUES
   (
      'Christian Pulisic', 29, 10, 10
   )
;

-- Recruiter
INSERT INTO
   Recruiter 
VALUES
   (
      6, TO_DATE('April 30, 2022', 'MONTH DD, YYYY')
   )
;

INSERT INTO
   Recruiter 
VALUES
   (
      7, TO_DATE('April 30, 2022', 'MONTH DD, YYYY') 
   )
;

INSERT INTO
   Recruiter 
VALUES
   (
      8, TO_DATE('April 30, 2022', 'MONTH DD, YYYY') 
   )
;

INSERT INTO
   Recruiter 
VALUES
   (
      9, TO_DATE('April 30, 2022', 'MONTH DD, YYYY') 
   )
;

INSERT INTO
   Recruiter 
VALUES
   (
      10, TO_DATE('April 30, 2022', 'MONTH DD, YYYY') 
   )
;

-- Message
INSERT INTO
   Messages 
VALUES
   (
      001, 'LMAO hilarous', TO_DATE('October 5, 2021', 'MONTH DD, YYYY'), 4
   )
;

INSERT INTO
   Messages 
VALUES
   (
      041, 'Please give me a job, I''m begging you.', TO_DATE('October 21, 2021', 'MONTH DD, YYYY'), 3
   )
;

INSERT INTO
   Messages 
VALUES
   (
      021, 'Please connect wtih me, I need some validation.', TO_DATE('September 10, 2021', 'MONTH DD, YYYY'), 2
   )
;

INSERT INTO
   Messages 
VALUES
   (
      024, 'Temp_x is the best way to name your variables.', TO_DATE('October 23, 2021', 'MONTH DD, YYYY'), 2
   )
;

INSERT INTO
   Messages 
VALUES
   (
      049, 'Trust me, I worked at Amazon.', TO_DATE('October 23, 2021', 'MONTH DD, YYYY'), 1
   )
;

-- Company
INSERT INTO
   Company 
VALUES
   (
      'Railtown AI', 'V6C 3E8'
   )
;

INSERT INTO
   Company 
VALUES
   (
      'IBM', 'V5G 4X3'
   )
;

INSERT INTO
   Company 
VALUES
   (
      'Amazon', 'V6B 0M3'
   )
;

INSERT INTO
   Company 
VALUES
   (
      'Visier', 'V6B 1C1'
   )
;

INSERT INTO
   Company 
VALUES
   (
      'Semios', 'V5T 4T5'
   )
;

-- WorkExperience
INSERT INTO
   WorkExperience 
VALUES
   (
      'Frontend Developer', 1, 1, TO_DATE('January 1, 2021', 'MONTH DD, YYYY'), TO_DATE('April 30, 2021', 'MONTH DD, YYYY'), 'Railtown AI'
   )
;

INSERT INTO
   WorkExperience 
VALUES
   (
      'Backend Developer', 2, 2, TO_DATE('February 1, 2021', 'MONTH DD, YYYY'), TO_DATE('May 30, 2021', 'MONTH DD, YYYY'), 'IBM'
   )
;

INSERT INTO
   WorkExperience 
VALUES
   (
      'Fullstack Developer', 3, 3, TO_DATE('March 1, 2021', 'MONTH DD, YYYY'), TO_DATE('June 30, 2021', 'MONTH DD, YYYY'), 'Amazon'
   )
;

INSERT INTO
   WorkExperience 
VALUES
   (
      'UI/UX Designer', 4, 4, TO_DATE('April 1, 2021', 'MONTH DD, YYYY'), TO_DATE('July 30, 2021', 'MONTH DD, YYYY'), 'Visier'
   )
;

INSERT INTO
   WorkExperience 
VALUES
   (
      'QA Developer', 5, 5, TO_DATE('May 1, 2021', 'MONTH DD, YYYY'), TO_DATE('Aug 30, 2021', 'MONTH DD, YYYY'), 'Semios'
   )
;

INSERT INTO
   WorkExperience 
VALUES
   (
      'SRE', 6, 5, TO_DATE('August 1, 2021', 'MONTH DD, YYYY'), TO_DATE('November 30, 2021', 'MONTH DD, YYYY'), 'IBM'
   )
;

-- Municipality
INSERT INTO
   Municipality 
VALUES
   (
      'V6C 3E8', 'Vancouver'
   )
;

INSERT INTO
   Municipality 
VALUES
   (
      'V5G 4X3', 'Burnaby'
   )
;

INSERT INTO
   Municipality 
VALUES
   (
      'V6B 0M3', 'Vancouver'
   )
;

INSERT INTO
   Municipality 
VALUES
   (
      'V6B 1C1', 'Vancouver'
   )
;

INSERT INTO
   Municipality 
VALUES
   (
      'V5T 4T5', 'Vancouver'
   )
;

-- District
INSERT INTO
   District 
VALUES
   (
      'V6C 3E8', 'BC'
   )
;

INSERT INTO
   District 
VALUES
   (
      'V5G 4X3', 'BC'
   )
;

INSERT INTO
   District 
VALUES
   (
      'P1L 2R3', 'ON'
   )
;

INSERT INTO
   District 
VALUES
   (
      'T7V 8J4', 'AB'
   )
;

INSERT INTO
   District 
VALUES
   (
      'E7H 5P5', 'NB'
   )
;

-- Hashtag
INSERT INTO
   Hashtag 
VALUES
   (
      1, '#WashedKing'
   )
;

INSERT INTO
   Hashtag 
VALUES
   (
      2, '#DataScience'
   )
;

INSERT INTO
   Hashtag 
VALUES
   (
      3, '#motivation'
   )
;

INSERT INTO
   Hashtag 
VALUES
   (
      4, '#ImHired'
   )
;

INSERT INTO
   Hashtag 
VALUES
   (
      5, '#ApplyNow'
   )
;

INSERT INTO
   Hashtag 
VALUES
   (
      6, '#ExperiencedNewGrad'
   )
;

-- JobPost
INSERT INTO
   JobPost 
VALUES
   (
      1, 'Data Analyst', 'HIRING! URGENT! But we will ghost you.', 6
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      2, 'Data Scientist', 'Looking for Data Scientist Intern for Summer 2022 Term.', 7
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      3, 'Social Media Intern', 'Hiring Social Media Intern, no experience needed.', 8
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      4, 'Backend Developer', 'Are you looking for a career change in tech? Apply to this position now! No experience required.', 9
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      5, 'UI/UX Designer', 'We are hiring for a UI/UX designer position. If you are a creative person who loves sharing your artistic vision, consider dropping your resume below.', 10
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      6, 'SRE', 'New grad role. Applicants must have 10+ year of experience.', 6
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      7, 'SRE', 'Looking for experienced SRE looking to work in a dynamic startup environment', 6
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      8, 'Data Scientist', 'We totally know what Data Science means and won''t ask you to use Excel all day', 7
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      9, 'ML Engineer', 'Do you want to work with Crypto? Blockchain technology? Machine learning? AI amongst other buzz words? Apply now!', 8
   )
;

INSERT INTO
   JobPost 
VALUES
   (
      10, 'SRE', 'Exciting opportunity to work at IBM''s new branch in Vancouver!', 10
   )
;

-- JobDesc
INSERT INTO
   JobDesc 
VALUES
   (
      'Data Analyst', 80000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'Data Scientist', 80000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'Social Media Intern', 10000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'Backend Developer', 80000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'UI/UX Designer', 60000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'SRE', 105000
   )
;

INSERT INTO
   JobDesc 
VALUES
   (
      'ML Engineer', 125000
   )
;

-- Contains 
INSERT INTO
   Contains 
VALUES
   (
      1, 001
   )
;

INSERT INTO
   Contains 
VALUES
   (
      2, 041
   )
;

INSERT INTO
   Contains 
VALUES
   (
      3, 021
   )
;

INSERT INTO
   Contains 
VALUES
   (
      4, 024
   )
;

INSERT INTO
   Contains 
VALUES
   (
      5, 049
   )
;

-- HasHashtag
INSERT INTO
   HasHashtag 
VALUES
   (
      1, 3
   )
;

INSERT INTO
   HasHashtag 
VALUES
   (
      2, 5
   )
;

INSERT INTO
   HasHashtag 
VALUES
   (
      3, 2
   )
;

INSERT INTO
   HasHashtag 
VALUES
   (
      4, 1
   )
;

INSERT INTO
   HasHashtag 
VALUES
   (
      5, 4
   )
;

INSERT INTO
   HasHashtag 
VALUES
   (
      6, 6
   )
;

-- HiringCompany
INSERT INTO
   HiringCompany 
VALUES
   (
      1, 'Railtown AI'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      2, 'IBM'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      3, 'Amazon'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      4, 'Visier'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      5, 'Semios'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      6, 'IBM'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      7, 'Amazon'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      8, 'Railtown AI'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      9, 'IBM'
   )
;

INSERT INTO
   HiringCompany 
VALUES
   (
      10, 'IBM'
   )
;




-- Apply
INSERT INTO
   ApplyTo 
VALUES
   (
      1, 1
   )
;

INSERT INTO
   ApplyTo 
VALUES
   (
      2, 2
   )
;

INSERT INTO
   ApplyTo 
VALUES
   (
      3, 3
   )
;

INSERT INTO
   ApplyTo 
VALUES
   (
      4, 4
   )
;

INSERT INTO
   ApplyTo 
VALUES
   (
      5, 5
   )
;

-- RecievedBy
INSERT INTO
   ReceivedBy 
VALUES
   (
      001, 5
   )
;

INSERT INTO
   ReceivedBy 
VALUES
   (
      041, 4
   )
;

INSERT INTO
   ReceivedBy 
VALUES
   (
      021, 3
   )
;

INSERT INTO
   ReceivedBy 
VALUES
   (
      024, 2
   )
;

INSERT INTO
   ReceivedBy 
VALUES
   (
      049, 1
   )
;

COMMIT;
