// app.js
import express from "express";
import session from "express-session";
import path from "path";
import { fileURLToPath } from "url";

const app = express();
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

app.use(express.json());
app.use(express.static(path.join(__dirname, "public"))); // put all HTML/JS/CSS in /public

app.use(
  session({
    secret: "supersecretkey",
    resave: false,
    saveUninitialized: true,
  })
);

// middleware: protect pages
function requireLogin(req, res, next) {
  if (!req.session.user) {
    return res.redirect("/login.html");
  }
  next();
}

// protected route example
app.get("/main.html", requireLogin, (req, res, next) => {
  res.sendFile(path.join(__dirname, "public", "main.html"));
});

// fallback
app.get("/", (req, res) => {
  res.redirect("/login.html");
});

app.listen(3000, () => {
  console.log("🚀 Server running at http://localhost:3000");
});
