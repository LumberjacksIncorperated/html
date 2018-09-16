
-- TAGNOSTIC DB SCHEMA V2

CREATE TABLE Users (
id VARCHAR(36) PRIMARY KEY,
firstName VARCHAR(40) NOT NULL,
lastName VARCHAR(40),
email VARCHAR(80) UNIQUE NOT NULL
);

--Basically the text blobs that people add. 
--This is a simple design for now.
CREATE TABLE Items (
id VARCHAR(36) PRIMARY KEY,
entryText TEXT,
userID VARCHAR(36) references Users(id),
timePosted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
timeModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- e.g. "Person", or "Date". 
-- I think NOT "deadline" etc, that's too specific for this table.
-- That could go in Tags.description
--editableField:
--e.g. if “person” type, should have a text value. Haven’t entirely thought this one through.
--iconID: the little image, e.g. from fontawesome
CREATE TABLE TagTypes (
id VARCHAR(36) PRIMARY KEY,
editableField VARCHAR(10), 	
iconID VARCHAR(10) 		
);

--uuid value: for tagging other users etc?
--description might be e.g. "deadline"
CREATE TABLE Tags (
id VARCHAR(36) PRIMARY KEY,
tagTypeID VARCHAR(36) references TagTypes(id),
textValue VARCHAR(100),
datetimeValue TIMESTAMP,
numericValue1 FLOAT,
numericValue2 FLOAT,
numericValue3 FLOAT,
uuidValue VARCHAR(36),				
timeAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
timeModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
addedBy VARCHAR(36) references Users(id),  
description VARCHAR(100)		 
);

-- Which items have which tags
CREATE TABLE ItemTags (
itemID VARCHAR(36) references Items(id),
tagID VARCHAR(36) references Tags(id),
beginOffset INTEGER,
endOffset INTEGER,
primary key (itemID, tagID)
);

-- Which tags are associated with which other tags for which user
-- not for MVP
CREATE TABLE AssociatedTags (
tagID VARCHAR(36) references Tags(id),
associatedTagID VARCHAR(36) references Tags(id),
userID VARCHAR(36) references Users(id),
probability REAL,
primary key (tagID, associatedTagID, userID)
);

-- Rudimentary "autocorrect" for tags
-- e.g. ability to apply tag "UNSW" if "uni" is mentioned 
CREATE TABLE AssociatedNames (
tagID VARCHAR(36) references Tags(id),
associatedName VARCHAR(100),
userID VARCHAR(36) references Users(id),
probability REAL,
primary key (tagID, associatedName, userID)
);








