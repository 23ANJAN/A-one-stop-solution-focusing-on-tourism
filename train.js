// server.js
import express from "express";
import mysql from "mysql2/promise";

const app = express();
const PORT = 3000;

// Create MySQL connection
const db = await mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "train_booking"
});

// API to fetch trains
app.get("/trains", async (req, res) => {
    try {
        const [trains] = await db.query("SELECT * FROM trains");

        let trainData = [];

        for (let train of trains) {
            // Get running days
            const [days] = await db.query(
                "SELECT day FROM train_runs_on WHERE train_id = ?",
                [train.id]
            );
            const runs_on = days.map(d => d.day);

            // Get classes
            const [classes] = await db.query(
                "SELECT class_code, price, seats_available FROM train_classes WHERE train_id = ?",
                [train.id]
            );

            let price = {};
            let seats = {};
            for (let c of classes) {
                price[c.class_code] = c.price;
                seats[c.class_code] = c.seats_available;
            }

            trainData.push({
                id: train.id,
                name: train.name,
                from: train.source,
                to: train.destination,
                departure: train.departure,
                arrival: train.arrival,
                duration: train.duration,
                type: train.type,
                runs_on,
                pantry: train.pantry,
                price,
                seats_available: seats
            });
        }

        res.json(trainData);
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: "Database fetch error" });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
