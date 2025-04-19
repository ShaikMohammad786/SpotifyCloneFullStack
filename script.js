let login = document.getElementById("login");
let signup = document.getElementById("signup");
let home = document.getElementById("homebtn");

home.addEventListener("click",()=>{
    window.location.href="index.php";

})


if(login){
login.addEventListener("click",()=>{
    window.location.href="login.html";

})}

if(signup){
signup.addEventListener("click",()=>{
    window.location.href="signup.html";

})}


const showLoginPopup = () => {
    document.getElementById("loginPopup").style.display = "flex";
};

const hideLoginPopup = () => {
    document.getElementById("loginPopup").style.display = "none";
};

document.getElementById("closeLoginPopup")?.addEventListener("click", hideLoginPopup);

// const playMusic = async (filename, pause = false) => {
//     try {
//         const res = await fetch("php/check_login.php");
//         const data = await res.json();

//         if (!data.loggedIn) {
//             showLoginPopup();
//             return;
//         }

//         currentSong.src = `songs/${filename}`;
//         if (!pause) {
//             currentSong.play();
//             play.src = "img/pause.svg";
//         }
//         document.querySelector(".songinfo").innerHTML = decodeURI(filename);
//         document.querySelector(".songtime").innerHTML = "00:00 / 00:00";
//     } catch (err) {
//         console.error("Error checking login:", err);
//     }
// };

const playMusic = async (filename, pause = false) => {
    try {
        const res = await fetch("php/check_login.php");
        const data = await res.json();

        if (!data.loggedIn) {
            showLoginPopup();
            return;
        }

        // Try to find song details from already loaded data
        let song =
            (songs || []).find(s => s.filename === filename) ||
            (recentlyPlayedSongs || []).find(s => s.filename === filename);

        // Fallback if nothing found
        song = song || { filename, title: filename, artist: "Unknown", cover_url: "img/default.jpg" };

        addToRecentlyPlayed(song);

        currentSong.src = `songs/${filename}`;
        if (!pause) {
            currentSong.play();
            play.src = "img/pause.svg";
        }

        document.querySelector(".songinfo").innerHTML = decodeURI(song.title || filename);
        document.querySelector(".songtime").innerHTML = "00:00 / 00:00";
    } catch (err) {
        console.error("Error in playMusic:", err);
    }
};



let currentSong = new Audio();
let songs;
let currFolder;

function secondsToMinutesSeconds(seconds) {
    if (isNaN(seconds) || seconds < 0) {
        return "00:00";
    }

    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);

    const formattedMinutes = String(minutes).padStart(2, '0');
    const formattedSeconds = String(remainingSeconds).padStart(2, '0');

    return `${formattedMinutes}:${formattedSeconds}`;
}





async function loadTrendingSongs() {
    const container = document.querySelector(".cardContainer");
    if (!container) {
        console.warn("Trending songs container not found!");
        return;
    }

    const res = await fetch("php/get_trending.php");
    const songsData = await res.json();

    songs = [];
    container.innerHTML = "";

    const visibleSongs = songsData.slice(0, 5);
    const hiddenSongs = songsData.slice(5);

    visibleSongs.forEach(song => {
        songs.push(song.filename);

        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
            <div class="play">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 20V4L19 12L5 20Z" stroke="#141B34" fill="#000"
                        stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
            <img src="${song.cover_url}" alt="${song.title}">
            <h2>${song.title}</h2>
            <p>${song.artist}</p>
        `;

        card.addEventListener("click", () => {
            playMusic(song.filename);
        });

        container.appendChild(card);
    });

    if (hiddenSongs.length > 0) {
        const showAllBtn = document.createElement("button");
        showAllBtn.textContent = "Show All ▶";
        showAllBtn.classList.add("showAllBtn");

        showAllBtn.addEventListener("click", () => {
            showAllTrending(songsData);
        });

        container.appendChild(showAllBtn);
    }
}


function showAllTrending(songsData) {
    const main = document.getElementById("maincontent");

    main.innerHTML = `
        <div class="spotifyPlaylists">
            <h1>All Trending Songs</h1>
            <div class="cardContainer"></div>
        </div>
    `;

    const container = document.querySelector(".cardContainer");
    songs = [];

    songsData.forEach(song => {
        songs.push(song.filename);

        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
            <div class="play">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 20V4L19 12L5 20Z" stroke="#141B34" fill="#000"
                        stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
            <img src="${song.cover_url}" alt="${song.title}">
            <h2>${song.title}</h2>
            <p>${song.artist}</p>
        `;

        card.addEventListener("click", () => {
            playMusic(song.filename);
        });

        container.appendChild(card);
    });
}


let allArtistPlaylists = [];

async function loadArtistPlaylists() {
    const res = await fetch("php/fetch_artist_playlists.php");
    allArtistPlaylists = await res.json(); 

    showArtistPlaylists(false); 
}


async function showArtistDetails(artist) {
    const mainContent = document.getElementById("maincontent");
    
    mainContent.innerHTML = `
        <div class="spotifyPlaylists">
            <h1>Loading songs by ${artist}...</h1>
        </div>
    `;
    
    try {
        const res = await fetch(`php/fetch_artist_playlists.php?artist=${encodeURIComponent(artist)}`);
        const artistSongs = await res.json();
        
        if (!artistSongs || artistSongs.length === 0) {
            mainContent.innerHTML = `
                <div class="spotifyPlaylists">
                    <h1>No songs found for ${artist}</h1>
                </div>
            `;
            return;
        }
        
        // Use the first song's cover as the playlist cover
        const coverUrl = artistSongs[0].cover_url;
        
        songs = artistSongs.map(song => song.filename);
        
        mainContent.innerHTML = `
            <div class="spotifyPlaylists">
                <div class="playlistDetail">
                    <div class="playlistHeader">
                        <img src="${coverUrl}" alt="${artist}" class="playlist-cover">
                        <div>
                            <p>Playlist</p>
                            <h1>${artist}</h1>
                            <p>${artistSongs.length} songs</p>
                        </div>
                    </div>
                    
                    <div class="songListColumn" id="artistSongsList">
                        <!-- Songs will be inserted here -->
                    </div>
                </div>
            </div>
        `;
        
        const songsList = document.getElementById("artistSongsList");
        
        artistSongs.forEach((song, index) => {
            const songRow = document.createElement("div");
            songRow.className = "song-row";
            songRow.innerHTML = `
                <img src="${song.cover_url}" alt="${song.title}" class="song-thumb">
                <div class="song-info">
                    <h4>${song.title}</h4>
                    <p>${song.artist}</p>
                </div>
            `;
            
            songRow.addEventListener("click", () => {
                playMusic(song.filename);
            });
            
            songsList.appendChild(songRow);
        });
    } catch (error) {
        console.error("Error fetching artist songs:", error);
        mainContent.innerHTML = `
            <div class="spotifyPlaylists">
                <h1>Error loading songs for ${artist}</h1>
                <p>Please try again later</p>
            </div>
        `;
    }
}

function showArtistPlaylists(showAll = false) {
    const container = document.getElementById("artistPlaylistContainer");
    container.innerHTML = ""; 

    const data = showAll ? allArtistPlaylists : allArtistPlaylists.slice(0, 5);

    data.forEach(p => {
        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
            <div class="play">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 20V4L19 12L5 20Z" stroke="#141B34" fill="#000"
                        stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
            <img src="${p.cover_url}" alt="${p.artist}">
            <h2>${p.artist}</h2>
            <p>${p.song_count} songs</p>
        `;
        
        card.addEventListener("click", () => {
            showArtistDetails(p.artist);
        });
        
        container.appendChild(card);
    });
}

function toggleAllArtistPlaylists() {
    const mainContent = document.getElementById("maincontent");
    mainContent.innerHTML = `
        <div class="spotifyPlaylists">
            <h1>All Popular Playlists</h1>
            <div class="cardContainer" id="allArtistPlaylists"></div>
        </div>
    `;

    const container = document.getElementById("allArtistPlaylists");
    songs = [];

    allArtistPlaylists.forEach(song => {
        songs.push(song.filename);

        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
            <div class="play">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 20V4L19 12L5 20Z" stroke="#141B34" fill="#000"
                        stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
            <img src="${song.cover_url}" alt="">
            <h2>${song.artist}</h2>
            <p>Playlist</p>
        `;

     
        card.addEventListener("click", () => {
            showArtistDetails(song.artist);
        });

        container.appendChild(card);
    });
}

async function searchSongs(query) {
    try {
        const res = await fetch(`php/search.php?q=${encodeURIComponent(query)}`);
        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }
        const results = await res.json();

        const container = document.getElementById("maincontent");
        if (!container) {
            throw new Error("Main content container not found");
        }
        container.innerHTML = `<h1>Top  Results </h1><div class="cardContainer"></div>`;

        const cardContainer = container.querySelector(".cardContainer");
        if (results.length === 0) {
            cardContainer.innerHTML = "<p>No songs or artists found.</p>";
            return;
        }

        results.forEach(song => {
            const card = document.createElement("div");
            card.className = "card";
            card.innerHTML = `
                <div class="play">
                    <svg width="16" height="16" viewBox="0 0 24 24">
                        <path d="M5 20V4L19 12L5 20Z" stroke="#141B34" fill="#000" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                </div>
                <img src="${song.cover_url}" alt="${song.title}">
                <h2>${song.title}</h2>
                <p>${song.artist}</p>
            `;
            card.addEventListener("click", () => {
                playMusic(song.filename);
            });
            cardContainer.appendChild(card);
        });
    } catch (error) {
        console.error("Error in searchSongs:", error);
        const container = document.getElementById("maincontent");
        if (container) {
            container.innerHTML = `<p>Error loading search results: ${error.message}</p>`;
        }
    }
}


let recentlyPlayedSongs = [];

async function addToRecentlyPlayed(song) {
    try {
        // Step 1: Get Spotify token from server-side
        const tokenRes = await fetch("php/spotify_token.php");
        const { access_token } = await tokenRes.json();

        // Step 2: Search song on Spotify using title
        const query = encodeURIComponent(song.title || song.filename);
        const spotifyRes = await fetch(`https://api.spotify.com/v1/search?q=${query}&type=track&limit=1`, {
            headers: {
                Authorization: `Bearer ${access_token}`
            }
        });

        const spotifyData = await spotifyRes.json();
        const track = spotifyData.tracks?.items?.[0];

        // Step 3: Format metadata if found
        let updatedSong = {
            filename: song.filename,
            title: song.title,
            artist: song.artist,
            cover_url: song.cover_url
        };

        if (track) {
            updatedSong = {
                filename: song.filename,
                title: track.name,
                artist: track.artists.map(a => a.name).join(", "),
                cover_url: track.album.images?.[0]?.url || "img/default.jpg"
            };
        }

        // Step 4: Add to localStorage
        let recent = JSON.parse(localStorage.getItem("recentlyPlayed")) || [];

        // Remove duplicate
        recent = recent.filter(s => s.filename !== updatedSong.filename);

        // Add to top
        recent.unshift(updatedSong);

        // Limit to 10
        if (recent.length > 10) recent.pop();

        localStorage.setItem("recentlyPlayed", JSON.stringify(recent));
        renderRecentlyPlayed();
    } catch (err) {
        console.error("Error fetching from Spotify:", err);
    }
}

function renderRecentlyPlayed() {
    const list = document.getElementById("recentlyPlayedList");
    if (!list) return;

    const recent = JSON.parse(localStorage.getItem("recentlyPlayed")) || [];
    list.innerHTML = "";

    recent.forEach(song => {
        const li = document.createElement("li");
        li.style.listStyle = "none";
        li.style.padding = "5px 0";
        li.style.cursor = "pointer";

        li.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <img src="${song.cover_url}" alt="${song.title}" style="width: 30px; height: 30px; border-radius: 4px; object-fit: cover;">
                <div>
                    <div style="font-size: 12px; color: white;">${song.title}</div>
                    <div style="font-size: 10px; color: gray;">${song.artist}</div>
                </div>
            </div>
        `;

        li.addEventListener("click", () => {
            playMusic(song.filename);
        });

        list.appendChild(li);
    });
}



async function main() {
    // await loadTrendingSongs();
    await loadTrendingSongs();

    if (songs.length > 0) {
        try {
            const res = await fetch("php/check_login.php");
            const data = await res.json();
    
            if (data.loggedIn) {
                playMusic(songs[0], true); 
            }
        } catch (e) {
            console.error("Login check failed on page load:", e);
        }
    }
    
    await loadArtistPlaylists();
    showArtistPlaylists();

    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("searchInput");
    
    if (searchBtn && searchInput) {
        searchBtn.addEventListener("click", () => {
            if (searchInput.value.trim()) {
                searchSongs(searchInput.value.trim());
            }
        });
    
        searchInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault(); 
                if (searchInput.value.trim()) {
                    searchSongs(searchInput.value.trim());
                }
            }
        });
    }

   

    play.addEventListener("click", () => {
        if (currentSong.paused) {
            currentSong.play()
            play.src = "img/pause.svg"
        }
        else {
            currentSong.pause()
            play.src = "img/play.svg"
        }
    })

    // Listen for timeupdate event
    currentSong.addEventListener("timeupdate", () => {
        document.querySelector(".songtime").innerHTML = `${secondsToMinutesSeconds(currentSong.currentTime)} / ${secondsToMinutesSeconds(currentSong.duration)}`
        document.querySelector(".circle").style.left = (currentSong.currentTime / currentSong.duration) * 100 + "%";
    })

    // Add an event listener to seekbar
    document.querySelector(".seekbar").addEventListener("click", e => {
        let percent = (e.offsetX / e.target.getBoundingClientRect().width) * 100;
        document.querySelector(".circle").style.left = percent + "%";
        currentSong.currentTime = ((currentSong.duration) * percent) / 100
    })

    // Add an event listener for hamburger
    document.querySelector(".hamburger").addEventListener("click", () => {
        document.querySelector(".left").style.left = "0"
    })

    // Add an event listener for close button
    document.querySelector(".close").addEventListener("click", () => {
        document.querySelector(".left").style.left = "-120%"
    })

    // Add an event listener to previous
    previous.addEventListener("click", () => {
        currentSong.pause()
        console.log("Previous clicked")
        let index = songs.indexOf(currentSong.src.split("/").slice(-1)[0])
        if ((index - 1) >= 0) {
            playMusic(songs[index - 1])
        }
    })

    // Add an event listener to next
    next.addEventListener("click", () => {
        currentSong.pause()
        console.log("Next clicked")

        let index = songs.indexOf(currentSong.src.split("/").slice(-1)[0])
        if ((index + 1) < songs.length) {
            playMusic(songs[index + 1])
        }
    })

    // Add an event to volume
    document.querySelector(".range").getElementsByTagName("input")[0].addEventListener("change", (e) => {
        console.log("Setting volume to", e.target.value, "/ 100")
        currentSong.volume = parseInt(e.target.value) / 100
        if (currentSong.volume >0){
            document.querySelector(".volume>img").src = document.querySelector(".volume>img").src.replace("mute.svg", "volume.svg")
        }
    })

    // Add event listener to mute the track
    document.querySelector(".volume>img").addEventListener("click", e=>{ 
        if(e.target.src.includes("volume.svg")){
            e.target.src = e.target.src.replace("volume.svg", "mute.svg")
            currentSong.volume = 0;
            document.querySelector(".range").getElementsByTagName("input")[0].value = 0;
        }
        else{
            e.target.src = e.target.src.replace("mute.svg", "volume.svg")
            currentSong.volume = .10;
            document.querySelector(".range").getElementsByTagName("input")[0].value = 10;
        }

    })





   
    

}

main() 
















