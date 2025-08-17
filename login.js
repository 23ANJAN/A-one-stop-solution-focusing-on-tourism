// login.js
import express from "express";
import session from "express-session";
import pool from "./db.js"; // MySQL connection

const router = express.Router();

// session setup
router.use(
  session({
    secret: "supersecretkey",
    resave: false,
    saveUninitialized: true,
  })
);

// login handler
router.post("/login", async (req, res) => {
  const { username, password } = req.body;

  try {
    const [rows] = await pool.query(
      "SELECT * FROM users WHERE username = ? AND password = ?",
      [username, password] // ⚠️ use hashed passwords in production
    );

    if (rows.length > 0) {
      req.session.user = rows[0];
      return res.json({ message: "Login successful" });
    } else {
      return res.status(401).json({ message: "Invalid username or password" });
    }
  } catch (err) {
    console.error("Login error:", err.message);
    return res.status(500).json({ message: "Internal server error" });
  }
});

// dummy protected page
router.get("/main", (req, res) => {
  if (!req.session.user) {
    return res.redirect("/login.html");
  }
  res.send(`<h1>Welcome, ${req.session.user.username}!</h1>`);
});

export default router;
