// server.js
import express from "express";
import bodyParser from "body-parser";
import session from "express-session";
import paymentRoutes from "./payment.js";
import confirmationRoutes from "./confirmation.js";

const app = express();

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Enable session (must be before routes!)
app.use(
  session({
    secret: "your_secret_key",
    resave: false,
    saveUninitialized: true,
  })
);

// Serve static files (HTML/CSS/JS)
app.use(express.static("public"));

// Routes
app.use("/", paymentRoutes);
app.use("/", confirmationRoutes);

app.listen(3000, () => {
  console.log("🚀 Server running on http://localhost:3000");
});
