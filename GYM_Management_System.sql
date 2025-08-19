
-- Gym Management System SQL Script

-- Drop tables if they already exist (for reset)
DROP TABLE IF EXISTS Attendance;
DROP TABLE IF EXISTS Access_Card;
DROP TABLE IF EXISTS Trainer_Assignment;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS Memberships;
DROP TABLE IF EXISTS Trainers;
DROP TABLE IF EXISTS Members;

-- Members Table
CREATE TABLE Members (
    Member_id INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Age INT,
    Gender VARCHAR(10),
    Email VARCHAR(100) UNIQUE,
    Phone VARCHAR(20),
    Membership_id INT,
    Access_card_id INT
);

-- Trainers Table
CREATE TABLE Trainers (
    Trainer_id INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Phone VARCHAR(20),
    Specialization VARCHAR(100),
    Email VARCHAR(100) UNIQUE
);

-- Memberships Table
CREATE TABLE Memberships (
    Membership_id INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(50) NOT NULL,
    Duration INT NOT NULL, -- duration in months
    Price DECIMAL(10,2) NOT NULL
);

-- Payments Table
CREATE TABLE Payments (
    Payment_id INT PRIMARY KEY AUTO_INCREMENT,
    Member_id INT NOT NULL,
    Amount DECIMAL(10,2) NOT NULL,
    Payment_date DATE NOT NULL,
    FOREIGN KEY (Member_id) REFERENCES Members(Member_id)
);

-- Trainer Assignment Table
CREATE TABLE Trainer_Assignment (
    Assignment_id INT PRIMARY KEY AUTO_INCREMENT,
    Member_id INT NOT NULL,
    Trainer_id INT NOT NULL,
    FOREIGN KEY (Member_id) REFERENCES Members(Member_id),
    FOREIGN KEY (Trainer_id) REFERENCES Trainers(Trainer_id)
);

-- Access Card Table
CREATE TABLE Access_Card (
    Access_card_id INT PRIMARY KEY AUTO_INCREMENT,
    Member_id INT NOT NULL,
    Status VARCHAR(20) NOT NULL,
    FOREIGN KEY (Member_id) REFERENCES Members(Member_id)
);

-- Attendance Table
CREATE TABLE Attendance (
    Attendance_id INT PRIMARY KEY AUTO_INCREMENT,
    Member_id INT NOT NULL,
    Access_card_id INT NOT NULL,
    Date DATE NOT NULL,
    Check_in_time TIME,
    Check_out_time TIME,
    FOREIGN KEY (Member_id) REFERENCES Members(Member_id),
    FOREIGN KEY (Access_card_id) REFERENCES Access_Card(Access_card_id)
);
