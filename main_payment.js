const express = require("express");
const session = require("express-session");
const bodyParser = require("body-parser");
const path = require("path");

const app = express();
const PORT = 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(
  session({
    secret: "your_secret_key",
    resave: false,
    saveUninitialized: true,
  })
);

// Static folder (public contains HTML/CSS/JS)
app.use(express.static(path.join(__dirname, "public")));

// Mock routes (you had in PHP)
const routes = {
  1: { name: "New York → Boston", price: 45.0 },
  2: { name: "New York → Washington DC", price: 55.0 },
  3: { name: "New York → Philadelphia", price: 35.0 },
};

// API to get route details
app.get("/route/:id", (req, res) => {
  const route = routes[req.params.id];
  if (route) res.json(route);
  else res.status(404).json({ error: "Route not found" });
});

// Payment submission
app.post("/process-payment", (req, res) => {
  const { route_id, name, email, phone } = req.body;
  const route = routes[route_id];

  if (!route) {
    return res.status(400).json({ error: "Invalid route" });
  }

  // Fake booking ID
  const booking_id = "BK" + Math.floor(Math.random() * 90000 + 10000);

  // Save in session
  req.session.booking_confirmed = true;
  req.session.booking_details = {
    booking_id,
    route: route.name,
    price: route.price,
    name,
    email,
    phone,
  };

  // Redirect to confirmation page
  res.redirect("/confirmation.html");
});

// Confirmation API (to fetch booking details)
app.get("/confirmation", (req, res) => {
  if (req.session.booking_confirmed) {
    res.json(req.session.booking_details);
  } else {
    res.status(400).json({ error: "No booking found" });
  }
});

app.listen(PORT, () => {
  console.log(`🚀 Server running on http://localhost:${PORT}`);
});
