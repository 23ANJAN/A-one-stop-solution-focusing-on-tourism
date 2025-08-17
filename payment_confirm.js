// confirmation.js
import express from "express";

const router = express.Router();

router.get("/confirmation", (req, res) => {
  if (req.session.booking_confirmed) {
    res.json({
      message: "Booking Confirmed!",
      booking: req.session.booking_details,
    });
  } else {
    res.status(400).json({ error: "No booking found." });
  }
});

export default router;
