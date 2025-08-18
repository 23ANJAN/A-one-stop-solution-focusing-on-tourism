// booking.js
import express from "express";
import session from "express-session";
import pool from "./db.js"; // your db connection file

const router = express.Router();

// Session setup
router.use(
  session({
    secret: "supersecretkey", // change in production
    resave: false,
    saveUninitialized: true,
  })
);

router.post("/submit-payment", async (req, res) => {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    const bookingId = "HOTEL" + Math.floor(10000 + Math.random() * 90000);

    // Extract card last 4 digits
    let cardLastFour = null;
    if (req.body.card_number) {
      cardLastFour = req.body.card_number.replace(/\s+/g, "").slice(-4);
    }

    // Insert booking
    const bookingQuery = `
      INSERT INTO hotel_bookings (
        booking_id,
        customer_name,
        customer_email,
        customer_phone,
        pickup_address,
        destination_address,
        total_amount,
        payment_method,
        card_last_four,
        booking_date,
        status
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'confirmed')
    `;

    await conn.query(bookingQuery, [
      bookingId,
      req.body.name,
      req.body.email,
      req.body.phone,
      req.body.pickup_address,
      req.body.destination_address,
      12500.0, // demo fixed amount
      req.body.payment_method,
      cardLastFour,
    ]);

    // Insert payment record
    const paymentQuery = `
      INSERT INTO payment (
        booking_id,
        card_holder,
        card_last_four,
        expiry_date,
        payment_status,
        created_at
      ) VALUES (?, ?, ?, ?, 'completed', NOW())
    `;

    await conn.query(paymentQuery, [
      bookingId,
      req.body.card_name || null,
      cardLastFour,
      req.body.expiry || null,
    ]);

    await conn.commit();

    // Save booking details in session
    req.session.booking_confirmed = true;
    req.session.booking_details = {
      booking_id: bookingId,
      name: req.body.name,
      email: req.body.email,
      phone: req.body.phone,
      pickup_address: req.body.pickup_address,
      destination_address: req.body.destination_address,
      total_amount: 12500.0,
    };

    res.redirect("/confirmation");
  } catch (err) {
    await conn.rollback();
    console.error("❌ Error processing payment:", err.message);
    res.status(500).send("Error: " + err.message);
  } finally {
    conn.release();
  }
});

export default router;
