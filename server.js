// server.js
import express from "express";
import bodyParser from "body-parser";
import paymentRoutes from "./payment.js";

const app = express();

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Serve static files (your HTML, CSS, JS)
app.use(express.static("public"));

// Payment route
app.use("/", paymentRoutes);

// Confirmation page
app.get("/confirmation", (req, res) => {
  if (req.session?.booking_confirmed) {
    res.json({
      message: "Booking Confirmed!",
      booking: req.session.booking_details,
    });
  } else {
    res.send("No booking found.");
  }
});

app.listen(3000, () => {
  console.log("🚀 Server running on http://localhost:3000");
});
