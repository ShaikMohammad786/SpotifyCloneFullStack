# SpotifyCloneFullStack
It is full stack end to end clone of Spotify which allows you login and register with database connection approximately 315 songs were given in database and can search the songs and artist names playlists were designed library for recently played songs dynamic java script functionality clickables super polished UI fetching data from spotify web api

Folder Structure
📁 your-project/
├── 📁 css/
├── 📁 img/
├── 📁 js/
│   └── script.js
├── 📁 php/
│   ├── check_login.php
│   ├── fetch_artist_playlists.php
│   ├── get_trending.php
│   ├── logout.php
│   └── spotify_token.php and many...
├── 📁 songs/
├── index.php
├── login.html
├── signup.html
├── .gitignore
└── README.md

# 🎵 MyMusic — PHP Music Streaming Web App

This is a fully functional music streaming platform built using **PHP + MySQL + JavaScript**, with features inspired by Spotify. The project includes **login/signup**, **playlist browsing**, **song streaming**, **artist-based discovery**, and **Spotify API integration** for real metadata and album covers.

---

## 🚀 Features

- 🔐 Login & Signup Authentication
- 🎧 Stream Songs with Interactive UI
- 🌟 Trending Songs Section
- 🎨 Artist-Based Playlists
- 🔍 Search Songs and Artists
- 🎵 Recently Played (Spotify Enhanced)
- 🔁 Next/Previous/Volume/Seek Controls
- 🧠 Smart Metadata via Spotify API

---

## 🛠 Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (via XAMPP)
- **Database**: MySQL
- **APIs**: Spotify Web API

---

## 📦 Installation (Local Setup with XAMPP)

1. **Clone the repo**
   ```bash
   git clone https://github.com/your-username/your-repo.git
Move it to XAMPP

bash
Copy
Edit
mv your-repo/ /xampp/htdocs/your-repo/
Import the SQL file

Open phpMyAdmin

Create a database music_app

Import music_app.sql if provided (or manually create users/songs table)

Set Up Spotify API

Go to Spotify Developer Dashboard

Create an app and get Client ID & Client Secret

Add them to php/spotify_token.php

php
Copy
Edit
$client_id = "YOUR_CLIENT_ID";
$client_secret = "YOUR_CLIENT_SECRET";
Run XAMPP and go to http://localhost/your-repo/index.php



🧑‍💻 Developer Notes
Songs must be placed in the /songs/ folder.

Cover images should be named/linked appropriately via cover_url.

Spotify API is used only to fetch metadata, not for actual music streaming.

This is a hobby/demo project. Not for commercial use.



📜 License
 Free for educational use.

🤝 Contribution
Pull requests and feature ideas are welcome! Let’s build cool music stuff together 🎶

📫 Contact
GitHub:github.com/ShaikMohammad786

Email: skmohammad378@gmail.com
phone :9391970347


---

### ✅ Final Tips

- Rename your PHP files inside `/php/` folder as above for cleanliness.
- Don’t commit `spotify_token.php` to GitHub. Put a dummy file there or `.env` based method.
- Want help exporting your MySQL DB as `.sql` and making a sample data dump? I can help you prep that too!

Let me know if you'd like the `README.md` and `.gitignore` files exported for you now—or if you'd like a zipped GitHub-ready version of your project.
