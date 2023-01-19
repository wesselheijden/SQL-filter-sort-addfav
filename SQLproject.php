<!DOCTYPE html>
<html style="font-size: 16px;" lang="nl">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="description" content="">
  <title>Top 250 IMDB movies</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="custom.js" defer=""></script>
  <link rel="stylesheet" href="./SQLproject.css">
  <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
  <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Oswald:200,300,400,500,600,700">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">


  <meta name="theme-color" content="#291569">
  <meta property="og:title" content="SQL Project">
  <meta property="og:type" content="website">
</head>

<body data-home-page="./index.php" data-home-page-title="Thuis" class="u-body u-xl-mode" data-lang="nl">
  <header class="u-clearfix u-header u-header" id="sec-3d90">
    <div class="u-clearfix u-sheet u-sheet-1">
      <?php
      $usedColumnsNames = array('rank', 'name', 'imdb_rating', 'genre', 'year', 'duration', 'imdb_votes', 'director_name', 'cast_name', 'writer_name');
      ?>
  </header>
  <section class="u-clearfix u-image u-section-1" id="carousel_4b49">
    <div class="filteringContainer">
      <div class="filtering">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <label>Amount of results:</label> <input type="number" value="250" name="numberResults">
          <label>Order by</label>
          <select name="selectedOrder">
            <?php
            //create filtering options
            foreach ($usedColumnsNames as $value) {?>
              <option value="<?php echo $value ?>"><? echo $value ?></option>
            <?php ;} ?>
          </select>
          <label>Ascending / Descending</label>
          <select name="ascDesc">
              <option value="ASC">Ascending</option>
              <option value="DESC">Descending</option>
          </select>
          <input type="submit">
        </form>
      </div>
    </div>
    <?php

    // Create connection
    $conn = mysqli_connect("studmysql01.fhict.local", "dbi432778", "#Heerbeeck2", "dbi432778");
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    //check form info
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $numberResults = $_POST['numberResults'];
      $selectedOrder = $_POST['selectedOrder'];
      $ascDesc = $_POST['ascDesc'];
      if (empty($numberResults || $selectedOrder || $ascDesc)) {
      } else {
        $limit = "LIMIT " . $numberResults;
        $orderBy = "ORDER BY " . $selectedOrder;
      }
    }
    //generate SQL Query    
    $sql = "SELECT rank, name, imdb_rating, genre, year, duration, imdb_votes, director_name, cast_name, writter_name, id FROM topimdb $orderBy $ascDesc $limit";
    $result = mysqli_query($conn, $sql);
    //generate html table
    echo "<table>";
    echo "<th></th><th>Rank</th><th>Title</th><th>Rating</th><th>Genre</th><th>Year</th><th>Duration</th><th>Amount of votes</th><th>Director Name</th><th>Cast Name</th><th>Writer Name</th>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td><button id='addFavorite_" . $row['id'] . "' onclick='addFavorite(event)'>Add to favorite</button></td>";
        foreach ($row as $field => $value) {
        echo "<td>". $value . "</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
    mysqli_close($conn);
    ?>
  </section>
  <button class="favoriteBtn" onclick="openOverlay()">
  <i class="fa-solid fa-heart" style="color:#fff; transform:scale(1.5);"></i>
  </button>

  <?php
$mysqli = new mysqli("studmysql01.fhict.local", "dbi432778", "#Heerbeeck2", "dbi432778");

$id = $_POST['id'];
var_dump($id);

if ($id) {
  // Prepare the query
  $query = "INSERT INTO favoritemovies (id) VALUES (?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $id);

  // Execute the query and get the result
  $stmt->execute();
  $result = $stmt->get_result();

  // Return the result as a JSON object
  echo json_encode($result);
} else {

  // Return an error message if the input data is invalid
  echo "het werkt niet";
}
$mysqli->close();
  ?>

<div id="overlay">
    <?php
$mysqli = new mysqli("studmysql01.fhict.local", "dbi432778", "#Heerbeeck2", "dbi432778");
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Prepare the query
$query = "SELECT *
        FROM topimdb 
        JOIN (SELECT id FROM favoritemovies) fm
        ON topimdb.id = fm.id";

// Execute the query
$result = $mysqli->query($query);

if (!$result) {
  die($mysqli->error);
}
// Check if the query returned any results
if ($result->num_rows > 0) {
  echo '<table>';
  echo '<tr><th>Name</th><th>Genre</th><th>Rating</th>...</tr>';
  // Loop through the results and output them in an HTML table
  while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<td>' . $row['name'] . '</td>';
      echo '<td>' . $row['genre'] . '</td>';
      echo '<td>' . $row['imdb_rating'] . '</td>';
      echo '</tr>';
  }
  echo '</table>';
} else {
  echo "No results found.";
}

// Close the database connection
$mysqli->close();
    ?>
</div>

</body>

</html>