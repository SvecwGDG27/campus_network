CREATE DATABASE campus_network1;

USE campus_network1;
CREATE TABLE quiz_scores (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each score entry
    user_id INT NOT NULL,              -- References the id of the user
    score INT NOT NULL,                -- The score obtained by the user
    date DATE NOT NULL,                -- The date when the quiz was taken
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, -- Added column for storing the user's name
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE  placement_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    rounds INT NOT NULL,
    round_types VARCHAR(255) NOT NULL
);




-- FAQ Table
CREATE TABLE faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE faq_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faq_id INT NOT NULL,
    answer TEXT NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faq_id) REFERENCES faq(id)
);

CREATE TABLE interview_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    round_types VARCHAR(255) NOT NULL,
    questions TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE  feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    message TEXT NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_email) REFERENCES users(email) ON DELETE CASCADE
);

CREATE TABLE  mock_questions (
    id INT PRIMARY KEY,
    question TEXT NOT NULL,
    option1 TEXT NOT NULL,
    option2 TEXT NOT NULL,
    option3 TEXT NOT NULL,
    option4 TEXT NOT NULL,
    answer TEXT NOT NULL
);