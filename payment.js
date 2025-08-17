// payment.js
import express from "express";
import session from "express-session";
import pool from "./db.js";

const router = express.Router();

// Enable session
router.use(
  session({
    secret: "your_secret_key",
    resave: false,
    saveUninitialized: true,
  })
);

router.post("/submit-payment", async (req, res) => {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // Generate booking ID
    const booking_id = "homestay" + Math.floor(10000 + Math.random() * 90000);

    // Extract data
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

    // Get last 4 digits of card
    let card_last_four = null;
    if (card_number) {
      card_last_four = card_number.replace(/\s/g, "").slice(-4);
    }

    // Insert booking
    await conn.query(
      `INSERT INTO homestay_bookings 
       (booking_id, customer_name, customer_email, customer_phone, pickup_address, destination_address, total_amount, payment_method, card_last_four, booking_date, status)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'confirmed')`,
      [
        booking_id,
        name,
        email,
        phone,
        pickup_address,
        destination_address,
        2440.0, // fixed for demo
        payment_method,
        card_last_four,
      ]
    );

    // Insert payment details if card
    if (payment_method === "card") {
      await conn.query(
        `INSERT INTO payment_details 
         (booking_id, card_holder, card_last_four, expiry_date, payment_status)
         VALUES (?, ?, ?, ?, 'completed')`,
        [booking_id, card_name, card_last_four, expiry]
      );
    }

    // Commit
    await conn.commit();

    // Save session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id,
      name,
      email,
      phone,
      pickup_address,
      destination_address,
      total_amount: 2440.0,
    };

    // Redirect (like PHP)
    res.redirect("/confirmation");
  } catch (err) {
    await conn.rollback();
    console.error("Payment error:", err.message);
    res.status(500).send("An error occurred while processing your payment.");
  } finally {
    conn.release();
  }
});

export default router;
