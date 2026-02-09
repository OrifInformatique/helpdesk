-- Create test database if not exists
CREATE DATABASE IF NOT EXISTS ci4_test;

-- Drop user if it already exists
DROP USER IF EXISTS 'ci4_test_user'@'%';

-- Create user with mysql_native_password
CREATE USER 'ci4_test_user'@'%' IDENTIFIED BY 'ci4_test_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON ci4_test.* TO 'ci4_test_user'@'%';
FLUSH PRIVILEGES;


-- Create main database if not exists
CREATE DATABASE IF NOT EXISTS ci4;

-- Drop user if it already exists
DROP USER IF EXISTS 'ci4_user'@'%';

-- Create user with mysql_native_password
CREATE USER 'ci4_user'@'%' IDENTIFIED BY 'ci4_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON ci4.* TO 'ci4_user'@'%';
-- Grant privileges on test database as well (for phpMyAdmin access)
GRANT ALL PRIVILEGES ON ci4_test.* TO 'ci4_user'@'%';
FLUSH PRIVILEGES;

-- Switch to the created database
USE ci4;