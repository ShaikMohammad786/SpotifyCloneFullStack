
# 🎵 SpotifyCloneFullStack

An end-to-end **full-stack Spotify clone** built with **PHP + MySQL + JavaScript**, offering user authentication, music playback, playlists, search, and Spotify Web API integration. The repository currently includes ~315 songs in the database and delivers a sleek, dynamic UI experience.

---

IMPORTANT NOTE : SONGS FOLDER IS NOT UPLOADED MAKE SURE TO CREATE A SONGS FOLDER ADD SONGS AND YOUR SONGS MAY NOT MATCH WITH FILE NAME IN THE SQL MAKE SURE TO MODIFY THE FILE PATHS IN THE SQL FILE (i.e run fetch_songs.php file after adding the songs folder in the profect file it will automatically manage) 

## 🚀 Key Features

- 🔐 **Login & Signup** (PHP + MySQL)
- 🎧 **Play Music Locally** from `/songs/`
- 🔍 **Search** by song title or artist
- 🌟 **Trending Section** for popular tracks
- 👤 **Artist-based Playlists**
- ⏱️ **Recently Played** history
- ▶️ **Player Controls**: play, pause, next, previous, volume, seek
- 💫 **Spotify Web API Integration** for metadata & cover images
- 🖥️ **Polished Responsive UI** using HTML, CSS, and JavaScript

---

## 📁 Project Structure

```
📦 SpotifyCloneFullStack/
├── css/                       # Stylesheets
├── img/                       # Icons & images
├── js/
│   └── script.js              # Frontend logic & audio handling
├── php/                       # Backend endpoints (login, search, token)
├── songs/                     # .mp3 files (≈315 songs)
├── index.php                  # Main application interface
├── login.html                 # Login page
├── signup.html                # Signup page
├── .gitignore                 # Ignore config/data files
└── README.md                  # This documentation
```

---

## 🛠️ Tech Stack

| Layer        | Technology                      |
|--------------|----------------------------------|
| Frontend     | HTML, CSS, JavaScript           |
| Backend      | PHP (run via XAMPP)             |
| Database     | MySQL (phpMyAdmin setup)        |
| API          | Spotify Web API (metadata)      |

---

## ⚙️ Setup Instructions (Local XAMPP)

1. **Clone the repo**  
   ```bash
   git clone https://github.com/ShaikMohammad786/SpotifyCloneFullStack.git
   mv SpotifyCloneFullStack/ /xampp/htdocs/
   ```

2. **Install XAMPP** and ensure Apache & MySQL are running.

3. **Database Setup**  
   - Open `phpMyAdmin` → Create `spotify_clone`  
   - Import the `music_app.sql` (if included) or manually create `users` & `songs` tables.

4. **Configure Spotify API**  
   - Register an app at [Spotify Developer Dashboard]
   - Add your `Client ID` & `Secret` in `php/spotify_token.php`.

5. **Add Music Files**  
   - Place `.mp3` files into `/songs/` (already populates ~315 songs).

6. **Launch**  
   Visit: `http://localhost/SpotifyCloneFullStack/index.php`

---

## 📦 Backend Endpoints (`/php/`)

- `check_login.php` – validates credentials  
- `signup.php` – new user registration  
- `fetch_songs.php` – retrieves all songs  
- `get_trending.php` – fetches trending tracks  
- `fetch_artist_playlists.php` – artist-related songs  
- `search.php` – dynamic search feature  
- `spotify_token.php` – handles Spotify API access tokens  
- `logout.php` – ends user session

---

## 💡 Developer Notes

- **Songs** must match filenames in the database for playback
- **Cover Images** are fetched live from Spotify’s API
- **Front-end controls** use `script.js` for all audio events
- Add `.env` support for Spotify credentials and exclude `spotify_token.php` from Git

---

## 🛡️ Security & Best Practices

- **Never commit** your Spotify credentials—use `.env` or placeholder
- **Add `.gitignore`** to exclude `/songs/`, `.env`, and `uploads/`
- **Database dump** (`music_app.sql`) can be generated from `phpMyAdmin` → Export

---

## 🎓 License & Contributions

- Licensed under **Apache-2.0**
- Pull requests and feature suggestions welcome!
- 🎵 Not for commercial use — personal/demo project

---

## 📫 Get in Touch

- **GitHub**: [ShaikMohammad786](https://github.com/ShaikMohammad786/SpotifyCloneFullStack)  
- **Email**: skmohammad378@gmail.com  
- **Phone**: +91‑9391970347

---

## ✅ Next Steps

- ✅ Database export script (`music_app.sql`)  
- ✅ `.env` implementation for configurations  
- 🔄 Improve UX: autoplay next track, playlists management  
- 🌍 Deploy on shared hosting with SSL support

---
