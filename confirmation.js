// bookingConfirmation.js
const express = require("express");
const session = require("express-session");
const path = require("path");

const app = express();
const PORT = 3000;

// Session middleware
app.use(
  session({
    secret: "your-secret-key",
    resave: false,
    saveUninitialized: true,
  })
);

// Middleware to serve static files (CSS/JS if needed)
app.use(express.static(path.join(__dirname, "public")));

// Booking confirmation route
app.get("/booking-confirmed", (req, res) => {
  if (!req.session.booking_confirmed || !req.session.booking_details) {
    return res.redirect("/cab");
  }

  const booking = req.session.booking_details;

  // Clear session data after rendering
  delete req.session.booking_confirmed;
  delete req.session.booking_details;

  // Render HTML
  res.send(`
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Booking Confirmed - Travel App</title>
      <style>
        * { margin*
