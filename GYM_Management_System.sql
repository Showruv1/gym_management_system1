/*DATABASE*/

DROP DATABASE IF EXISTS gym_management;
CREATE DATABASE gym_management;
USE gym_management;

-- Members Table
CREATE TABLE members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male','Female','Other'),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL UNIQUE
);

-- Trainers Table
CREATE TABLE trainers (
    trainer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) UNIQUE,
    specialization VARCHAR(100),
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Memberships Table
CREATE TABLE memberships (
    membership_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    duration INT NOT NULL, -- in months
    price DECIMAL(10,2) NOT NULL
);

-- Payments Table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

-- Access Cards Table
CREATE TABLE access_cards (
    access_card_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNIQUE,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    FOREIGN KEY (member_id) REFERENCES members(member_id)
);

-- Trainer Assignments Table
CREATE TABLE trainer_assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT,
    trainer_id INT,
    FOREIGN KEY (member_id) REFERENCES members(member_id),
    FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id)
);

-- Attendance Table
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT,
    access_card_id INT,
    date DATE NOT NULL,
    check_in_time TIME,
    check_out_time TIME,
    FOREIGN KEY (member_id) REFERENCES members(member_id),
    FOREIGN KEY (access_card_id) REFERENCES access_cards(access_card_id)
);

-- Insert Memberships
INSERT INTO memberships (name, duration, price) VALUES
('Monthly Basic', 1, 2000.00),
('Quarterly Standard', 3, 5000.00),
('Yearly Premium', 12, 15000.00);

-- Insert Trainers
INSERT INTO trainers (name, phone, specialization, email) VALUES
('Ashraful Islam', '01710000001', 'Fitness', 'Siam.trainer@gym.com'),
('Robiul Awal Showruv', '01710000002', 'Yoga', 'Robiul.trainer@gym.com'),
('Jahid Hossain Haolader', '01710000003', 'Bodybuilding', 'Jahid.trainer@gym.com');

-- Insert Members
INSERT INTO members (name, age, gender, email, phone) VALUES
('Md Noman', 25, 'Male', 'noman@gym.com', '01820000001'),
('Faysal Ahmed', 22, 'Male', 'faysal@gym.com', '01820000002'),
('Imran Ali', 30, 'Male', 'imran@gym.com', '01820000003');

-- Insert Access Cards
INSERT INTO access_cards (member_id, status) VALUES
(1, 'Active'),
(2, 'Active'),
(3, 'Inactive');

-- Insert Payments
INSERT INTO payments (member_id, amount, payment_date) VALUES
(1, 2000.00, '2025-08-01'),
(2, 5000.00, '2025-07-15'),
(3, 15000.00, '2025-01-01');

-- Insert Trainer Assignments
INSERT INTO trainer_assignments (member_id, trainer_id) VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insert Attendance
INSERT INTO attendance (member_id, access_card_id, date, check_in_time, check_out_time) VALUES
(1, 1, '2025-08-20', '09:00:00', '10:30:00'),
(2, 2, '2025-08-20', '11:00:00', '12:15:00');






-- Function to auto generate Access Card when new Member is added
DELIMITER $$

CREATE FUNCTION generate_access_card(mid INT) 
RETURNS VARCHAR(50)
DETERMINISTIC
BEGIN
    DECLARE card_no VARCHAR(50);

    -- card number
    SET card_no = CONCAT('CARD-', mid, '-', UNIX_TIMESTAMP());

    -- access_cards 
    INSERT INTO access_cards (member_id, status)
    VALUES (mid, 'Active');

    RETURN card_no;
END$$

DELIMITER ;
