// db.js
import mysql from "mysql2/promise";

const dbConfig = {
  host: "localhost",
  user: "root",
  password: "root",
  database: "travel_app",
  charset: "utf8mb4"
};

let pool;

try {
  // Create a connection pool for better performance
  pool = mysql.createPool({
    ...dbConfig,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
  });

  console.log("✅ Database connection established successfully.");
} catch (err) {
  console.error("❌ Connection failed:", err.message);
  process.exit(1);
}

export default pool;
