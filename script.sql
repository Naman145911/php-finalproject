CREATE TABLE admin (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(50),
image blob
);

CREATE TABLE content (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(100) NOT NULL,
body TEXT NOT NULL,
author VARCHAR(30) NOT NULL
);


INSERT INTO content (title, body, author) VALUES
('Getting Started with Blogging', 'Are you interested in starting your own blog? This post will guide you through the basics of setting up your blog, choosing a niche, and creating compelling content.', 'Johnny');

INSERT INTO content (title, body, author) VALUES
('The Importance of Regular Exercise', 'Maintaining an active lifestyle has numerous benefits for both physical and mental health. Learn about the significance of regular exercise and how it can improve your overall well-being.', 'Jenifer');

INSERT INTO content (title, body, author) VALUES
('Exploring New Horizons', 'Embark on a journey of exploration and self-discovery. This post shares the experiences of traveling to distant lands, embracing different cultures, and broadening your perspective.', 'Alexa');
