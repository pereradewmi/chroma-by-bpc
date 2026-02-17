-- SQL Queries for CROMA School Management System Database Tables
-- Execute these queries in your database to create the required tables

-- 1. Create Students Table
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    mobile_number VARCHAR(20) NOT NULL,
    age INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- 2. Create Teachers Table
CREATE TABLE teachers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    mobile_number VARCHAR(20) NOT NULL,
    subject_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- 3. Create Class Rooms Table
CREATE TABLE class_rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL,
    teacher_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
);

-- 4. Create Sessions Table
CREATE TABLE sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_name VARCHAR(255) NOT NULL,
    teacher_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
);

-- Add indexes for better performance
CREATE INDEX idx_students_name ON students(firstname, lastname);
CREATE INDEX idx_teachers_name ON teachers(firstname, lastname);
CREATE INDEX idx_teachers_subject ON teachers(subject_name);
CREATE INDEX idx_class_rooms_teacher ON class_rooms(teacher_id);
CREATE INDEX idx_sessions_teacher ON sessions(teacher_id);

-- Sample Data (Optional - for testing purposes)
-- Insert sample teachers
INSERT INTO teachers (firstname, lastname, address, mobile_number, subject_name, created_at, updated_at) VALUES
('John', 'Doe', '123 Main St, Cityville', '+1-234-567-8901', 'Mathematics', NOW(), NOW()),
('Jane', 'Smith', '456 Oak Ave, Townsville', '+1-234-567-8902', 'English', NOW(), NOW()),
('Michael', 'Johnson', '789 Pine Rd, Villageton', '+1-234-567-8903', 'Science', NOW(), NOW());

-- Insert sample students
INSERT INTO students (firstname, lastname, address, mobile_number, age, created_at, updated_at) VALUES
('Alice', 'Wilson', '321 Elm St, Cityville', '+1-234-567-8911', 16, NOW(), NOW()),
('Bob', 'Brown', '654 Maple Ave, Townsville', '+1-234-567-8912', 17, NOW(), NOW()),
('Charlie', 'Davis', '987 Cedar Rd, Villageton', '+1-234-567-8913', 15, NOW(), NOW());

-- Insert sample classes (using teacher IDs from above)
INSERT INTO class_rooms (class_name, teacher_id, created_at, updated_at) VALUES
('Grade 10 Mathematics', 1, NOW(), NOW()),
('Grade 11 English Literature', 2, NOW(), NOW()),
('Grade 9 General Science', 3, NOW(), NOW());

-- Insert sample sessions (using teacher IDs from above)
INSERT INTO sessions (session_name, teacher_id, created_at, updated_at) VALUES
('Morning Math Session', 1, NOW(), NOW()),
('Afternoon English Session', 2, NOW(), NOW()),
('Lab Science Session', 3, NOW(), NOW());