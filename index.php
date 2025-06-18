<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/utility.css">
    <title>Spotify - Web Player: Music for everyone</title>
</head>

<body>
    <div class="container flex bg-black">
        <div class="left">
            <div class="close">
                <img width="30" class="invert" src="img/close.svg" alt="">
            </div>
            <div class="home bg-grey rounded m-1 p-1">
                <div class="logo"><img width="110" class="invert" src="img/logo.svg" alt=""></div>
                <ul>
                    <li id="homebtn"><img class="invert" src="img/home.svg" alt="home">Home</li>


                    <input class="search" type="search" id="searchInput" placeholder="search for songs" style="filter: invert(1);
    height: 40px;
    width: 247px;
    border: 0;
    border-radius: 20px;
    text-align:center;
    margin-top: 3%;">
                    <button id="searchBtn" style="    border: 0;
    border-radius: 20px;
    filter: invert(1);
    background-color: white;
    height: 40px;
    width: 50px">Search</button>



                </ul>
            </div>

            <div class="library bg-grey rounded m-1 p-1">
                <div class="heading">
                    <img class="invert" src="img/playlist.svg" alt="">
                    <h2>
                        Your Library
                    </h2>
                    
                </div>

                <div class="songList">

                <ul id="recentlyPlayedList" style="padding: 0 10px;"></ul>
                </div>

                <div class="footer">
                    <div><a href="https://www.spotify.com/jp/legal/"><span>Legal</span></a></div>
                    <div><a href="https://www.spotify.com/jp/privacy/"><span>Privacy Center</span></a></div>
                    <div><a href="https://www.spotify.com/jp/legal/privacy-policy/"><span>Privacy Policy</span></a>
                    </div>
                    <div><a href="https://www.spotify.com/jp/legal/cookies-policy/"><span>Cookies</span></a></div>
                    <div><a href="https://www.spotify.com/jp/legal/privacy-policy/#s3"><span>About Ads</span></a></div>
                    <div><a href="https://www.spotify.com/jp/accessibility/"><span>Accessibility</span></a></div>
                </div>
            </div>


        </div>
        <div class="right bg-grey rounded">
            <div class="header">
                <div class="nav">
                    <div class="hamburgerContainer">

                        <img width="40" class="invert hamburger" src="img/hamburger.svg" alt="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15 6L9.70711 11.2929C9.37377 11.6262 9.20711 11.7929 9.20711 12C9.20711 12.2071 9.37377 12.3738 9.70711 12.7071L15 18"
                                stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 18L14.2929 12.7071C14.6262 12.3738 14.7929 12.2071 14.7929 12C14.7929 11.7929 14.6262 11.6262 14.2929 11.2929L9 6"
                                stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>


                <div class="buttons">
                    <?php if ($isLoggedIn): ?>
                    <span style="margin-right: 10px;">Welcome,
                        <?= htmlspecialchars($username) ?>
                    </span>
                    <form action="php/logout.php" method="POST" style="display:inline;">
                        <button class="loginbtn" type="submit">Logout</button>
                    </form>
                    <?php else: ?>
                    <button id="signup" class="signupbtn">Sign up</button>
                    <button id="login" class="loginbtn">Log in</button>
                    <?php endif; ?>
                </div>

            </div>
            <div id="maincontent" class="mainarea">
                <div class="spotifyPlaylists">
                    <h1>Popular Songs</h1>
                    <div class="cardContainer"></div>
                    <br>
                    <h1>Popular Playlists </h1>
                    <div class="cardContainer" id="artistPlaylistContainer"></div>
                    <button class="showAllBtn" onclick="toggleAllArtistPlaylists()">Show All ▶</button>
                    <div class="playlist-card" onclick="showArtistDetails('Billie Eilish')">


                    </div>
                </div>
            </div>
            <div class="playbar">
                <div class="seekbar">
                    <div class="circle">

                    </div>
                </div>
                <div class="abovebar">


                    <div class="songinfo">

                    </div>
                    <div class="songbuttons">
                        <img width="35" id="previous" src="img/prevsong.svg" alt="">
                        <img width="35" id="play" src="img/play.svg" alt="">
                        <img width="35" id="next" src="img/nextsong.svg" alt="">
                    </div>
                    <div class="timevol">


                        <div class="songtime">

                        </div>
                        <div class="volume">
                            <img width="25" src="img/volume.svg" alt="">
                            <div class="range">
                                <input type="range" name="volume" id="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="login-popup" id="loginPopup">
  <div class="login-popup-content">
    <span class="close" id="closeLoginPopup">&times;</span>
    <h2>Please login to continue</h2>
    <button onclick="window.location.href='login.html'">Go to Login</button>
  </div>
</div>



    <script src="./js/script.js" defer></script>
</body>

</html>