-- Filename: process_eoi.sql
-- Author: Do Vu Khanh Quynh
-- Description: Schema definition and sample records for the EOI table.

CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    dob VARCHAR(15) NOT NULL,
    gender VARCHAR(25) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    state VARCHAR(10) NOT NULL,
    postcode VARCHAR(4) NOT NULL,
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    primary_skill VARCHAR(50) NOT NULL,
    additional_skills VARCHAR(255),
    other_skills TEXT,
    documents VARCHAR(255),
    status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO eoi (
    job_ref, first_name, last_name, dob, gender, email, phone,
    state, postcode, street_address, suburb, primary_skill,
    additional_skills, other_skills, documents, status
) VALUES
('VF001', 'Minh', 'Nguyen', '15/05/2003', 'male', 'minh.nguyen@example.com', '0412345678',
 'VIC', '3000', '120 Spencer Street', 'Melbourne', 'HTML/CSS',
 'HTML/CSS, UI/UX Design', 'None', 'None', 'New'),
('VF003', 'Lan', 'Tran', '22/08/2002', 'female', 'lan.tran@example.com', '0498765432',
 'NSW', '2000', '45 George Street', 'Sydney', 'Data Analysis',
 'Data Analysis, Project Management', 'Python, SQL', 'None', 'Current');