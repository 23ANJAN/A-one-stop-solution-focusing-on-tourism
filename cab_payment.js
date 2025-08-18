const express = require("express");
const session = require("express-session");
const mysql = require("mysql2/promise");
const bodyParser = require("body-parser");
const path = require("path");

const app = express();

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static(path.join(__dirname, "public")));
app.use(
  session({
    secret: "secureSecretKey",
    resave: false,
    saveUninitialized: true,
  })
);

// DB Connection
const pool = mysql.createPool({
  host: "localhost",
  user: "root",
  password: "your_password",
  database: "tourism_app",
});

// Payment Route
app.post("/process-payment", async (req, res) => {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // Generate booking ID
    const booking_id = "CAB" + Math.floor(10000 + Math.random() * 90000);

    // Get card last four digits
    let card_last_four = null;
    if (req.body.card_number) {
      card_last_four = req.body.card_number.replace(/\s/g, "").slice(-4);
    }

    // Insert into bookings
    await conn.query(
      `INSERT INTO cab_bookings 
      (booking_id, customer_name, customer_email, customer_phone, pickup_address, destination_address, total_amount, payment_method, card_last_four, booking_date, status) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'confirmed')`,
      [
        booking_id,
        req.body.name,
        req.body.email,
        req.body.phone,
        req.body.pickup_address,
        req.body.destination_address,
        900.0, // Fixed for demo
        req.body.payment_method,
        card_last_four,
      ]
    );

    // Insert payment if card
    if (req.body.payment_method === "card") {
      await conn.query(
        `INSERT INTO payment_details (booking_id, card_holder, card_last_four, expiry_date, payment_status) 
        VALUES (?, ?, ?, ?, 'completed')`,
        [booking_id, req.body.card_name, card_last_four, req.body.expiry]
      );
    }

    await conn.commit();

    // Save to session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id,
      name: req.body.name,
      email: req.body.email,
      phone: req.body.phone,
      pickup_address: req.body.pickup_address,
      destination_address: req.body.destination_address,
      total_amount: 1500.0,
    };

    res.redirect("/confirmation");
  } catch (err) {
    await conn.rollback();
    console.error("Payment Error:", err);
    res.status(500).send("An error occurred while processing your payment.");
  } finally {
    conn.release();
  }
});

// Confirmation Page
app.get("/confirmation", (req, res) => {
  if (!req.session.booking_confirmed) {
    return res.redirect("/");
  }
  res.json({
    message: "Booking Confirmed!",
    booking: req.session.booking_details,
  });
});

// Start server
app.listen(3000, () => {
  console.log("🚖 Server running on http://localhost:3000");
});
