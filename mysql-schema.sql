
-- TAGNOSTIC DB SCHEMA V3

DROP TABLE IF EXISTS `Accounts`;
CREATE TABLE `Accounts` (
  `account_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password_sha1` varchar(64) NOT NULL,
  firstName VARCHAR(40),
  lastName VARCHAR(40),
  email VARCHAR(80) UNIQUE,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Insert some dummy accounts
LOCK TABLES `Accounts` WRITE;
INSERT INTO `Accounts` VALUES (1,'JohnCitizen','5BAA61E4C9B93F3F0682250B6CF8331B7EE68FD8', 'John', 'Citizen', 'j@s.com');
INSERT INTO `Accounts` VALUES (2,'Bob','9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'Bob', 'Smith', 'b@s.com');
UNLOCK TABLES;

DROP TABLE IF EXISTS `Sessions`;
CREATE TABLE `Sessions` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `session_key_sha1` varchar(64) NOT NULL,
  `last_session_renewal` datetime NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4;

LOCK TABLES `Sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `Sessions` VALUES (1,1,'5BAA61E4C9B93F3F0682250B6CF8331B7EE68FD8','2018-08-28 22:35:23');
INSERT INTO `Sessions` VALUES (2,2,'5a535bf5506603509e5ad04247d2bc211311aa37','2018-09-12 05:15:39');
INSERT INTO `Sessions` VALUES (3,2,'a73c79e948c60cb93bd7ad07cd0cd0d3b89c0706','2018-09-12 05:15:39');

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--Basically the text blobs that people add. 
--This is a simple design for now.
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `item_id` VARCHAR(36),
  `account_id` int(10) unsigned NOT NULL,
  `item_text` text NOT NULL,
  `time_posted` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `time_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`),
  KEY `item_ibfk_1` (`account_id`),
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;


LOCK TABLES `items` WRITE;
INSERT INTO `items` (item_id, account_id, item_text) VALUES ("1", 1, 'Just a demo task, Rob');
INSERT INTO `items` (item_id, account_id, item_text) VALUES ("2", 2, 'Just a demo task, Dan');
INSERT INTO `items` (item_id, account_id, item_text) VALUES ("3", 2, 'Just a demo task, Jack');
INSERT INTO `items` (item_id, account_id, item_text) VALUES ("4", 1, 'Just a demo task, Nazif');
INSERT INTO `items` (item_id, account_id, item_text) VALUES ("5", 1, 'Just a demo task, Ojasvi');
UNLOCK TABLES;


-- e.g. "Person", or "Date".
-- I think NOT "deadline" etc, that's too specific for this table.
-- That could go in Tags.description
--editableField:
--e.g. if “person” type, should have a text value. Haven’t entirely thought this one through.
--iconID: the little image, e.g. from fontawesome
DROP TABLE IF EXISTS `TagTypes`;
CREATE TABLE TagTypes (
id INTEGER unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50),
iconID VARCHAR(50)
);

-- Current tag types: date, person, location, other
insert into TagTypes (name, iconID) values ("date", "fas fa-calendar-alt");
insert into TagTypes (name, iconID) values ("time", "far fa-clock");
insert into TagTypes (name, iconID) values ("person", "fas fa-user");
insert into TagTypes (name, iconID) values ("location", "fas fa-map-marker-alt");
insert into TagTypes (name, iconID) values ("other", "");
insert into TagTypes (name, iconID) values ("texttype", "fas fa-file");
--insert into TagTypes (name, iconID) values ("texttype", "fas fa-file");

--uuid value: for tagging other users etc?
--description might be e.g. "deadline"
DROP TABLE IF EXISTS `Tags`;
CREATE TABLE Tags (
id VARCHAR(36) PRIMARY KEY,
tagTypeID INTEGER references TagTypes(id),
textValue VARCHAR(100),
dateTimeValue DATETIME,
numericValue1 FLOAT,
numericValue2 FLOAT,
numericValue3 FLOAT,
uuidValue VARCHAR(36),
timeAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
timeModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
addedBy VARCHAR(36) references Accounts(account_id),
description VARCHAR(100)
);

--Dummy tag
--insert into Tags(id, tagTypeID, textValue) values ("5a9b34c6-72e7-46b8-a388-6b1d61f58cd3", 9, "Nazif");
--insert into Tags(id, tagTypeID, textValue) values ("5a9b34c6-72e7-46b8-a388-6b1d61f58cd4", 10, "UNSW");

-- Which items have which tags
DROP TABLE IF EXISTS `ItemTags`;
CREATE TABLE ItemTags (
itemID VARCHAR(36) references Items(item_id),
tagID VARCHAR(36) references Tags(id),
beginOffset INTEGER,
endOffset INTEGER,
primary key (itemID, tagID)
);

--insert into ItemTags(itemID, tagID) values (144, "5a9b34c6-72e7-46b8-a388-6b1d61f58cd3");
--insert into ItemTags(itemID, tagID) values (145, "5a9b34c6-72e7-46b8-a388-6b1d61f58cd4");

-- Which tags are associated with which other tags for which user
-- not for MVP
DROP TABLE IF EXISTS `AssociatedTags`;
CREATE TABLE AssociatedTags (
tagID VARCHAR(36) references Tags(id),
associatedTagID VARCHAR(36) references Tags(id),
userID VARCHAR(36) references Accounts(account_id),
probability REAL,
primary key (tagID, associatedTagID, userID)
);

-- Rudimentary "autocorrect" for tags
-- e.g. ability to apply tag "UNSW" if "uni" is mentioned 
DROP TABLE IF EXISTS `AssociatedNames`;
CREATE TABLE AssociatedNames (
tagID VARCHAR(36) references Tags(id),
associatedName VARCHAR(100),
userID VARCHAR(36) references Accounts(account_id),
probability REAL,
primary key (tagID, associatedName, userID)
);








