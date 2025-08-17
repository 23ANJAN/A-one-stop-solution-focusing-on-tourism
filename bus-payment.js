// server.js
const express = require("express");
const mysql = require("mysql2/promise");
const session = require("express-session");
const bodyParser = require("body-parser");

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// --- Session handling ---
app.use(session({
  secret: "secretKey",
  resave: false,
  saveUninitialized: true
}));

// --- DB Connection ---
const dbConfig = {
  host: "localhost",
  user: "root",
  password: "your_password",
  database: "travel_app"
};

// --- Payment Route ---
app.post("/submit-payment", async (req, res) => {
  const connection = await mysql.createConnection(dbConfig);
  const {
    name,
    email,
    phone,
    pickup_address,
    destination_address,
    payment_method,
    card_number,
    card_name,
    expiry
  } = req.body;

  try {
    await connection.beginTransaction();

    // Generate booking ID
    const booking_id = "BUS" + Math.floor(10000 + Math.random() * 90000);

    // Get last 4 digits of card
    let card_last_four = null;
    if (card_number) {
      card_last_four = card_number.replace(/\s/g, "").slice(-4);
    }

    // Insert into bookings table
    await connection.execute(
      `INSERT INTO bus_bookings (
        booking_id, customer_name, customer_email, customer_phone,
        pickup_address, destination_address, total_amount,
        payment_method, card_last_four, booking_date, status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'confirmed')`,
      [
        booking_id,
        name,
        email,
        phone,
        pickup_address,
        destination_address,
        1200.0, // dynamic or fixed
        payment_method,
        card_last_four
      ]
    );

    // If card payment, insert payment details
    if (payment_method === "card") {
      await connection.execute(
        `INSERT INTO payment_details (
          booking_id, card_holder, card_last_four, expiry_date, payment_status
        ) VALUES (?, ?, ?, ?, 'completed')`,
        [booking_id, card_name, card_last_four, expiry]
      );
    }

    await connection.commit();

    // Store in session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id,
      name,
      email,
      phone,
      pickup_address,
      destination_address,
      total_amount: 1200.0
    };

    res.redirect("/confirmation"); // confirmation page
  } catch (error) {
    await connection.rollback();
    console.error(error);
    res.status(500).send("Error processing payment");
  } finally {
    await connection.end();
  }
});

// --- Confirmation Page ---
app.get("/confirmation", (req, res) => {
  if (!req.session.booking_confirmed) {
    return res.send("No booking found.");
  }
  res.json(req.session.booking_details);
});

app.listen(3000, () => console.log("🚀 Server running on port 3000"));
