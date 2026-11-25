-- Test results for mathLearningSite. Author: WR
CREATE TABLE IF NOT EXISTS results (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(60) NOT NULL,
  last_name VARCHAR(60) NOT NULL,
  class VARCHAR(60) NOT NULL,
  points TINYINT UNSIGNED NOT NULL,
  operation ENUM('add', 'sub', 'mul', 'div') NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
