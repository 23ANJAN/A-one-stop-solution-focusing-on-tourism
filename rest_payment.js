const express = require("express");
const session = require("express-session");
const mysql = require("mysql2/promise");
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

// Database connection
let db;
(async () => {
  try {
    db = await mysql.createPool({
      host: "localhost",
      user: "root",
      password: "root",
      database: "travel_app",
      waitForConnections: true,
      connectionLimit: 10,
      queueLimit: 0,
    });
    console.log("✅ Connected to MySQL");
  } catch (err) {
    console.error("❌ Database connection failed:", err.message);
  }
})();

// Serve static files (for your HTML + CSS + JS form)
app.use(express.static(path.join(__dirname, "public")));

// Handle payment form submission
app.post("/process-payment", async (req, res) => {
  const connection = await db.getConnection();
  await connection.beginTransaction();

  try {
    const {
      name,
      email,
      phone,
      pickup_address,
      destination_address,
      payment_method,
      card_number,
      card_name,
      expiry,
    } = req.body;

    // Generate booking ID
    const booking_id = "Restaurant" + Math.floor(Math.random() * 90000 + 10000);

    // Get last 4 digits of card if card payment
    let card_last_four = null;
    if (card_number) {
      card_last_four = card_number.replace(/\s/g, "").slice(-4);
    }

    // Insert booking details
    await connection.execute(
      `INSERT INTO Restaurant_bookings (
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
        870.0, // fixed amount demo
        payment_method,
        card_last_four,
      ]
    );

    // Insert payment details if card method
    if (payment_method === "card") {
      await connection.execute(
        `INSERT INTO payment_details (
          booking_id, card_holder, card_last_four, expiry_date, payment_status
        ) VALUES (?, ?, ?, ?, 'completed')`,
        [booking_id, card_name, card_last_four, expiry]
      );
    }

    await connection.commit();

    // Save booking details in session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id,
      name,
      email,
      phone,
      pickup_address,
      destination_address,
      total_amount: 870.0,
    };

    res.redirect("/confirmation.html");
  } catch (err) {
    await connection.rollback();
    console.error("❌ Error processing payment:", err.message);
    res.status(500).send("An error occurred while processing your payment.");
  } finally {
    connection.release();
  }
});

// Confirmation page (serve session data as JSON)
app.get("/confirmation", (req, res) => {
  if (req.session.booking_confirmed) {
    res.json(req.session.booking_details);
  } else {
    res.status(400).json({ error: "No booking found." });
  }
});

app.listen(PORT, () => {
  console.log(`🚀 Server running at http://localhost:${PORT}`);
});
