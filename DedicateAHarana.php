<?php  
include 'db_connection.php'; 
?> 

<?php
include('fetchHarana.php');
?>

<html>
<head>
    <title>Dedicate a Harana</title>
    <link rel="stylesheet" href="DedicateAHarana.css">

    <!-- FONTS USED -->
    <!-- LOGO FONT (Qwigley) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Qwigley&display=swap" rel="stylesheet">

    <!-- HEADER FONT (Qwitcher Grypen) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Qwitcher+Grypen:wght@400;700&display=swap" rel="stylesheet">

    <!-- COMMON FONT (Sono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sono:wght@200..800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'navbar.php'; ?>

    <!-- MAIN SECTION -->
    <h1>Dedicate a Harana</h1>
    
    <!-- SONG SECTION -->
    <div class="song-card">

        <div class="song-search">
            <form id="songSearchForm" action="searchsong.php" method="get" onsubmit="event.preventDefault();">
                <input type="text" id="songSearch" name="q" placeholder="🔍 Search for a song" required>
                <button id="searchButton" type="button">Search</button>
            </form>   
            <div class="results" id="searchResults">
                <!-- Search results from searchsong.php will be displayed here -->
            </div>
        </div>

        <div class="upload-option">
            <label>🎙️ Sing your own harana? Upload a file here: </label>
            <input type="file" id="uploadFile" name="haranaFile">
        </div>
    </div>

    <!-- ADDED SONG SECTION -->
    <div id="song-card-container">
        <!-- Song Card Section where added songs will be displayed -->
    </div>

    <!-- DEDICATION FORM -->
    <form id="dedicationForm" class="dedication-form" enctype="multipart/form-data">
        <label for="to">To:</label>
        <input type="text" id="to" name="to" placeholder="Recipient's name">
      
        <label for="from">From:</label>
        <input type="text" id="from" name="from" placeholder="Your name">
      
        <label for="message">Attach a Message:</label>
        <textarea id="message" name="message" rows="4" placeholder="Write your message here..."></textarea>

        <!-- FORM BUTTONS -->
        <div class="form-footer">
            <div class="update">
                <button type="button" id="updateButton">Update</button>
            </div>
            <div class="delete">
                <button type="button" id="deleteButton">Delete</button>
            </div>
            <div class="send">
                <button type="submit" id="sendButton">Send</button>
            </div>
        </div>
    </form>

    <!-- Send New Entry Button -->
    <div class="send-new-entry">
        <button id="sendNewEntryButton">Send another Harana? Click Here.</button>
    </div>

    <!-- Previous Entries Preview -->
				<div id="previous-dedications">
				    <h2>Previous Dedications</h2>
				    <?php if (empty($entries)): ?>
				        <p>No previous dedications available.</p>
				    <?php else: ?>
				        <?php foreach ($entries as $entry): ?>
				            <div class="dedication-card">
				                <h3>Song: <?php echo htmlspecialchars($entry['song_name']); ?></h3>
				                <p><strong>To:</strong> <?php echo htmlspecialchars($entry['to_name']); ?></p>
				                <p><strong>From:</strong> <?php echo htmlspecialchars($entry['from_name']); ?></p>
				                <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($entry['message'])); ?></p>
				                <?php if (!empty($entry['file_path'])): ?>
				                    <audio controls>
				                        <source src="<?php echo htmlspecialchars($entry['file_path']); ?>" type="audio/mpeg">
				                        Your browser does not support the audio element.
				                    </audio>
				                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <!-- Include the script for adding songs dynamically -->
    <script>
        // Function to add song to the song card section
        function addSong(trackName, artistName, trackUrl) {
            const songCardContainer = document.getElementById("song-card-container");

            // Check if the song is already added to avoid duplicates
            const existingSongs = songCardContainer.querySelectorAll("p");
            for (let song of existingSongs) {
                if (song.textContent === `${trackName} by ${artistName}`) {
                    return; // Song is already added, no need to add again
                }
            }

            const songCard = document.createElement("div");
            songCard.classList.add("song-card");

            const songTitle = document.createElement("p");
            songTitle.textContent = `${trackName} by ${artistName}`;

            const iframe = document.createElement("iframe");
            iframe.src = trackUrl;
            iframe.allowTransparency = "true";
            iframe.allow = "encrypted-media";

            songCard.appendChild(songTitle);
            songCard.appendChild(iframe);

            // Add the song card to the container
            songCardContainer.appendChild(songCard);

            // Remove the song from the search results
            const results = document.getElementById("searchResults");
            results.innerHTML = ''; // Clear the search results after adding the song
        }

        document.getElementById("searchButton").addEventListener("click", function() {
            const query = document.getElementById("songSearch").value.trim();
            
            if (!query) {
                alert("Please enter a search term.");
                return;
            }

            // Make an AJAX request to searchsong.php
            fetch(`searchsong.php?q=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("searchResults").innerHTML = data;
                })
                .catch(error => {
                    console.error("Error fetching search results:", error);
                });
        });

        // Send New Entry Button
        document.getElementById("sendNewEntryButton").addEventListener("click", function() {
            document.getElementById("dedicationForm").reset();
            document.getElementById("song-card-container").innerHTML = '';
            document.getElementById("uploadFile").value = '';
        });

        // Update and Delete functionality
        document.getElementById("updateButton").addEventListener("click", function() {
            // Logic to allow the form to be editable again
            document.getElementById("dedicationForm").querySelectorAll("input, textarea").forEach(input => {
                input.disabled = false;
            });
        });

        document.getElementById("deleteButton").addEventListener("click", function() {
            if (confirm("Are you sure you want to delete this entry?")) {
                // Logic to delete the entry
                document.getElementById("dedicationForm").reset();
                document.getElementById("song-card-container").innerHTML = '';
                // Add AJAX call to delete the entry from the database here
            }
        });

								document.addEventListener('DOMContentLoaded', function () {
								    // Function to fetch and display previous dedications
								    function loadPreviousDedications() {
								        const previousDedicationsDiv = document.getElementById('previous-dedications');
								        const xhr = new XMLHttpRequest();
								        xhr.open('GET', 'fetchHarana.php', true); // Fetch entries from the server
								        xhr.onload = function () {
								            if (xhr.status === 200) {
								                previousDedicationsDiv.innerHTML = xhr.responseText; // Insert response into div
								            } else {
								                previousDedicationsDiv.innerHTML = '<p>Error loading dedications.</p>';
								            }
								        };
								        xhr.send();
								    }
								
								    // Load the previous dedications when the page is ready
								    loadPreviousDedications();
								});

    </script>
</body>
</html>