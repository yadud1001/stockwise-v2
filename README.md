SETUP PHP:
1. Download and install XAMPP
2. Go to Local Disk then xampp folder
3. Look for htdocs inside xampp folder and place your project there
4. To test project, go to xampp control panel inside the same xampp folder (scroll down)
5. Start the Apache and MySQL
6. Go to your browser and search localhost (you should find your project folder)
7. For your database, go to localhost/phpmyadmin and create your database
8. Make sure it is alligned to your database pathway in VS Code
9. For creating a database, go to Databases in the upper nav bar once inside the localhost/phpmyadmin
10. Name your database and click create
11. When creating a table manually inside your database, make sure to click your database name in the left side and click SQL
12. Follow this syntax:
                          CREATE TABLE users (
	                         id INT(11) NOT NULL AUTO_INCREMENT,
                           username VARCHAR(30) NOT NULL,
                           pass(dont use password) VARCHAR(255) NOT NULL,
                           created_at DATETIME NOT NULL DEFAULT CURRENT_TIME,
		                       role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
                           PRIMARY KEY (id)
                          );
13. Important notes: INT for numbers, VARCHAR for small texts, TEXT for paragraphs and DATETIME for dates, ENUM for select options
