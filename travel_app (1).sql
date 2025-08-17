-- Database: `travel_app`

-- --------------------------------------------------------
-- Table structure for table `payment`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(10) NOT NULL,
  `card_holder` varchar(100) NOT NULL,
  `card_last_four` varchar(4) NOT NULL,
  `expiry_date` varchar(5) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_payment_status` (`payment_status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample Data
INSERT INTO `payment` (`id`, `booking_id`, `card_holder`, `card_last_four`, `expiry_date`, `payment_status`, `created_at`) VALUES
(1, 'CAB10938', '', '', '', 'completed', '2024-11-21 01:52:33'),
(2, 'CAB22673', '4353WQARRT3ATRWTAR', '34T2', '34/53', 'completed', '2024-11-21 01:56:17');

-- --------------------------------------------------------
-- Extra table to store JS backend code for reference
-- --------------------------------------------------------

DROP TABLE IF EXISTS `backend_code`;
CREATE TABLE IF NOT EXISTS `backend_code` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(100) NOT NULL,
  `language` VARCHAR(50) NOT NULL DEFAULT 'javascript',
  `code` TEXT NOT NULL,
  PRIMARY KEY (`id`)
);

-- Insert Converted JavaScript Code Snippets

-- Database connection (converted from PHP to Node.js/JS)
INSERT INTO `backend_code` (`file_name`, `code`) VALUES
('db.js', 
"import mysql from 'mysql2/promise';\n\nconst pool = mysql.createPool({\n  host: 'localhost',\n  user: 'root',\n  password: 'root',\n  database: 'travel_app',\n  waitForConnections: true,\n  connectionLimit: 10,\n  queueLimit: 0\n});\n\nexport default pool;");

-- Login API
INSERT INTO `backend_code` (`file_name`, `code`) VALUES
('login.js',
"import express from 'express';\nimport pool from './db.js';\n\nconst router = express.Router();\n\nrouter.post('/login', async (req, res) => {\n  const { username, password } = req.body;\n  try {\n    const [rows] = await pool.query('SELECT * FROM users WHERE username = ? AND password = ?', [username, password]);\n    if (rows.length > 0) {\n      res.json({ success: true, message: 'Login successful!' });\n    } else {\n      res.json({ success: false, message: 'Invalid credentials' });\n    }\n  } catch (err) {\n    res.status(500).json({ success: false, message: 'Server error' });\n  }\n});\n\nexport default router;");

-- Express App setup
INSERT INTO `backend_code` (`file_name`, `code`) VALUES
('server.js',
"import express from 'express';\nimport bodyParser from 'body-parser';\nimport loginRouter from './login.js';\n\nconst app = express();\napp.use(bodyParser.json());\n\napp.use('/api', loginRouter);\n\napp.listen(3000, () => console.log('Server running on http://localhost:3000'));");
