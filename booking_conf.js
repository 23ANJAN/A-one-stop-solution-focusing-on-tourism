// confirmation.js
import express from "express";

const router = express.Router();

router.get("/confirmation", (req, res) => {
  if (req.session?.booking_confirmed) {
    const booking = req.session.booking_details;

    res.send(`
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <title>Booking Confirmation</title>
        <style>
          body { font-family: Arial, sans-serif; background:#f9f9f9; padding:20px; }
          .card { max-width:600px; margin:auto; padding:20px; background:#fff; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
          h2 { color:#28a745; }
          p { font-size:16px; }
        </style>
      </head>
      <body>
        <div class="card">
          <h2>✅ Booking Confirmed!</h2>
          <p><strong>Booking ID:</strong> ${booking.booking_id}</p>
          <p><strong>Name:</strong> ${booking.name}</p>
          <p><strong>Email:</strong> ${booking.email}</p>
          <p><strong>Phone:</strong> ${booking.phone}</p>
          <p><strong>Pickup:</strong> ${booking.pickup_address}</p>
          <p><strong>Destination:</strong> ${booking.destination_address}</p>
          <p><strong>Total Amount:</strong>
