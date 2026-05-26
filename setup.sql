CREATE DATABASE IF NOT EXISTS wpoets_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wpoets_test;

CREATE TABLE IF NOT EXISTS slides (
  id INT AUTO_INCREMENT PRIMARY KEY,
  topic VARCHAR(100) NOT NULL,
  title VARCHAR(255) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
);

INSERT INTO slides (topic, title, image_path, sort_order) VALUES
('Learning', 'Usability enhancement and Training for Transaction Portal for Customers', 'files/images/DL-Learning-1.jpg', 1),
('Learning', 'Research with empathy', 'files/images/DL-learning.svg', 2),
('Learning', 'Share knowledge', 'files/images/DL-Technology.jpg', 3),
('Technology', 'Build resilient systems', 'files/images/DL-Technology.jpg', 1),
('Technology', 'Use modern tools', 'files/images/DL-learning.svg', 2),
('Technology', 'Automate releases', 'files/images/DL-communication.svg', 3),
('Communication', 'Speak clearly', 'files/images/DL-Communication.jpg', 1),
('Communication', 'Visual storytelling', 'files/images/DL-communication.svg', 2),
('Communication', 'Audience-first work', 'files/images/DL-Technology.jpg', 3);