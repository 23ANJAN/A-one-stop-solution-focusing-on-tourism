import express from "express";
import session from "express-session";
import bodyParser from "body-parser";
import mysql from "mysql2/promise";
import crypto from "crypto";

const app = express();

// MySQL Connection
const pool = mysql.createPool({
  host: "localhost",
  user: "root",
  password: "root",
  database: "travel_app",
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static("public"));
app.set("view engine", "ejs"); // use ejs templates if needed

app.use(
  session({
    secret: "secretKey123",
    resave: false,
    saveUninitialized: true,
  })
);

// Handle payment submission
app.post("/train-payment", async (req, res) => {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // Generate booking ID
    const booking_id = "train" + Math.floor(10000 + Math.random() * 90000);

    // Get last 4 digits of card
    let card_last_four = null;
    if (req.body.card_number) {
      const digits = req.body.card_number.replace(/\s/g, "");
      card_last_four = digits.slice(-4);
    }

    // Insert into train_bookings
    await conn.execute(
      `
      INSERT INTO train_bookings (
        booking_id, customer_name, customer_email, customer_phone,
        pickup_address, destination_address, total_amount,
        payment_method, card_last_four, booking_date, status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'confirmed')
    `,
      [
        booking_id,
        req.body.name,
        req.body.email,
        req.body.phone,
        req.body.pickup_address,
        req.body.destination_address,
        870.0, // fixed amount
        req.body.payment_method,
        card_last_four,
      ]
    );

    // If card payment, insert into payment_details
    if (req.body.payment_method === "card") {
      await conn.execute(
        `
        INSERT INTO payment_details (
          booking_id, card_holder, card_last_four, expiry_date, payment_status
        ) VALUES (?, ?, ?, ?, 'completed')
      `,
        [booking_id, req.body.card_name, card_last_four, req.body.expiry]
      );
    }

    // Insert into generic payment table (like PHP second block)
    await conn.execute(
      `
      INSERT INTO payment (
        booking_id, card_holder, card_last_four, expiry_date,
        payment_status, created_at
      ) VALUES (?, ?, ?, ?, 'completed', NOW())
    `,
      [booking_id, req.body.card_name, card_last_four, req.body.expiry]
    );

    await conn.commit();

    // Store booking in session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id,
      name: req.body.name,
      email: req.body.email,
      phone: req.body.phone,
      pickup_address: req.body.pickup_address,
      destination_address: req.body.destination_address,
      total_amount: 870.0,
      card_last_four,
    };

    res.redirect("/confirmation");
  } catch (err) {
    await conn.rollback();
    console.error("Payment error:", err.message);
    res.send(
      "<h3>An error occurred while processing your payment. Please try again.</h3>"
    );
  } finally {
    conn.release();
  }
});

// Confirmation page
app.get("/confirmation", (req, res) => {
  if (!req.session.booking_confirmed) {
    return res.redirect("/");
  }
  res.json({
    message: "Booking confirmed",
    details: req.session.booking_details,
  });
});

app.listen(3000, () => {
  console.log("Server running at http://localhost:3000");
});
