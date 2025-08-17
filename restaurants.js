// server.js
import express from "express";
import bodyParser from "body-parser";
import cors from "cors";

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Sample Indian restaurant data
let restaurants = [
  {
    id: 1,
    name: "Punjab Dhaba",
    cuisine: "North Indian",
    rating: 4.7,
    price_range: "₹₹",
    location: "Connaught Place, Delhi",
    opening_hours: "11:00 AM - 11:00 PM",
    popular_dishes: ["Butter Chicken", "Dal Makhani", "Naan", "Paneer Tikka"],
    features: ["Family Seating", "Pure Veg Section", "Live Tandoor", "Outdoor Seating"],
    description: "Authentic Punjabi cuisine with traditional ambiance",
    tables_available: 8,
    total_reviews: 845,
    min_booking: 2,
    max_booking: 15,
    image: "/api/placeholder/800/400?text=Punjab+Dhaba",
    meal_type: ["veg", "non-veg"]
  },
  {
    id: 2,
    name: "Dakshin",
    cuisine: "South Indian",
    rating: 4.8,
    price_range: "₹₹₹",
    location: "Bengaluru, Karnataka",
    opening_hours: "8:00 AM - 10:30 PM",
    popular_dishes: ["Masala Dosa", "Idli Sambar", "Filter Coffee", "Thali"],
    features: ["Pure Vegetarian", "Traditional Seating", "Live Dosa Counter"],
    description: "Premium South Indian vegetarian dining experience",
    tables_available: 12,
    total_reviews: 689,
    min_booking: 1,
    max_booking: 8,
    image: "/api/placeholder/800/400?text=Dakshin+Restaurant",
    meal_type: ["veg"]
  },
  {
    id: 3,
    name: "Mughal Darbar",
    cuisine: "Mughlai",
    rating: 4.6,
    price_range: "₹₹₹",
    location: "Lucknow, UP",
    opening_hours: "12:00 PM - 11:00 PM",
    popular_dishes: ["Galouti Kebab", "Biryani", "Nihari", "Shahi Tukda"],
    features: ["Family Hall", "Kebab Counter", "Banquet Hall", "Valet Parking"],
    description: "Royal Mughlai cuisine with authentic Lucknowi flavors",
    tables_available: 15,
    total_reviews: 912,
    min_booking: 2,
    max_booking: 20,
    image: "/api/placeholder/800/400?text=Mughal+Darbar",
    meal_type: ["non-veg"]
  },
  {
    id: 4,
    name: "Coastal Spice",
    cuisine: "Coastal Indian",
    rating: 4.7,
    price_range: "₹₹",
    location: "Mumbai, Maharashtra",
    opening_hours: "11:30 AM - 11:00 PM",
    popular_dishes: ["Fish Curry", "Prawn Masala", "Sol Kadhi", "Malvani Thali"],
    features: ["Fresh Seafood", "Family Seating", "Coastal Ambience"],
    description: "Authentic coastal cuisine from Maharashtra and Goa",
    tables_available: 10,
    total_reviews: 567,
    min_booking: 2,
    max_booking: 12,
    image: "/api/placeholder/800/400?text=Coastal+Spice",
    meal_type: ["non-veg", "seafood"]
  }
];

// API route - get restaurants
app.get("/api/restaurants", (req, res) => {
  res.json(restaurants);
});

// API route - book a table
app.post("/api/book", (req, res) => {
  const { restaurant_id, guests, date, time } = req.body;

  const restaurant = restaurants.find(r => r.id === restaurant_id);
  if (!restaurant) {
    return res.status(404).json({ message: "Restaurant not found" });
  }

  if (guests > restaurant.tables_available * restaurant.max_booking) {
    return res.status(400).json({ message: "Not enough tables available" });
  }

  restaurant.tables_available -= 1;

  res.json({
    success: true,
    message: `Table booked at ${restaurant.name} for ${guests} people on ${date} at ${time}`
  });
});

const PORT = 5000;
app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));
