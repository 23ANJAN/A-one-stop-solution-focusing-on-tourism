// server.js
const express = require("express");
const mysql = require("mysql2/promise");
const session = require("express-session");
const bodyParser = require("body-parser");
const path = require("path");

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// --- Session handling ---
app.use(session({
  secret: "secretKey",
  resave: false,
  saveUninitialized: true
}));

// serve static files (for confirmation.html, CSS, etc.)
app.use(express.static(path.join(__dirname, "public")));

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

    const booking_id = "BUS" + Math.floor(10000 + Math.random() * 90000);
    let card_last_four = null;
    if (card_number) {
      card_last_four = card_number.replace(/\s/g, "").slice(-4);
    }

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
        1200.0,
        payment_method,
        card_last_four
      ]
    );

    if (payment_method === "card") {
      await connection.execute(
        `INSERT INTO payment_details (
          booking_id, card_holder, card_last_four, expiry_date, payment_status
        ) VALUES (?, ?, ?, ?, 'completed')`,
        [booking_id, card_name, card_last_four, expiry]
      );
    }

    await connection.commit();

    // save details in session
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

    res.redirect("/confirmation");
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
    return res.send("<h2>No booking found. Please try again.</h2>");
  }

  const details = req.session.booking_details;

  res.send(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>Booking Confirmation</title>
      <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
        h2 { color: green; }
        .details { margin-top: 20px; }
        .details p { font-size: 16px; margin: 8px 0; }
      </style>
    </head>
    <body>
      <div class="container">
        <h2>✅ Booking Confirmed!</h2>
        <p>Thank you, <b>${details.name}</b>. Your booking has been successfully confirmed.</p>
        
        <div class="details">
          <p><strong>Booking ID:</strong> ${details.booking_id}</p>
          <p><strong>Email:</strong> ${details.email}</p>
          <p><strong>Phone:</strong> ${details.phone}</p>
          <p><strong>Pickup:</strong> ${details.pickup_address}</p>
          <p><strong>Destination:</strong> ${details.destination_address}</p>
          <p><strong>Total Paid:</strong> ₹${details.total_amount}</p>
        </div>
      </div>
    </body>
    </html>
  `);
});

app.listen(3000, () => console.log("🚀 Server running on http://localhost:3000"));
