<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="">
</head>

<body class="container border border-dark-subtle rounded-bottom shadow-sm p-3 mb-5 bg-body-tertiary">
  <?php
  // Überprüfen, ob das Formular gesendet wurde
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Überprüfen, ob alle erforderlichen Felder ausgefüllt sind
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["rating"]) && !empty($_POST["usercomment"])) {
      // Daten speichern (hier einfach in einer Textdatei für die Demo)
      $data = $_POST["username"] . "|" . $_POST["email"] . "|" . $_POST["rating"] . "|" . $_POST["usercomment"] . "\n";
      file_put_contents("reviews.txt", $data, FILE_APPEND);

      // Erfolgsmeldung ausgeben
      echo "<p><b>Well received! Thank you for your Feedback!</b></p>";
    } else {
      // Fehlermeldung ausgeben, wenn nicht alle Felder ausgefüllt sind
      echo "<p style='color: red;'><b>Bitte füllen Sie alle Felder aus!</b></p>";
    }
  }
  ?>

  <form method="post" action="">
    <p class="fw-bold my-3">Give a feedback</p>
    <label for="username" class="form-label mx-5">Username</label>
    <label for="email" class="form-label mx-5">Email</label>
    <br>
    <input type="text" name="username" required>
    <input type="email" name="email" required>
    <br>
    <br>
    <input type="radio" id="positive" name="rating" value="good" required>
    <label for="positive">😃</label><br>
    <input type="radio" id="normal" name="rating" value="neutral" required>
    <label for="normal">😐</label><br>
    <input type="radio" id="negative" name="rating" value="bad" required>
    <label for="negative">😡</label>
    <br>
    <br>
    <label for="comment">Comment</label>
    <br>
    <textarea id="comment" name="usercomment" rows="1" cols="40" required class="input-group-text"> </textarea>
    <br>
    <button type="submit" class="btn btn-primary">Save Feedback</button>
  </form>
  <br>

  <p><b>Saved reviews:</b></p>
  <?php
  // Anzeigen der Anzahl der Bewertungen und Fortschrittsbalken
  $smilies = ["😃", "😐", "😡"];
  $ratings = ["good", "neutral", "bad"];
  $percentages = []; // Array für die Prozentsätze

  foreach ($smilies as $index => $smiley) {
    $count = 0;

    // Zählt die Anzahl der Bewertungen für jedes Rating
    if (file_exists("reviews.txt")) { // Überprüfen, ob die Datei "reviews.txt" existiert
      $reviews = file_get_contents("reviews.txt"); // Die Inhalte der Datei "reviews.txt" abrufen
      $reviews = explode("\n", $reviews); // Die Inhalte der Datei anhand von Zeilenumbrüchen ("\n") in ein Array aufteilen
      foreach ($reviews as $review) { // Iteriert durch jedes Element des Arrays (jede Zeile der Datei)
        $fields = explode("|", $review); // Die Daten in jeder Zeile anhand des Trennzeichens "|" aufteilen und in ein Array speichern

        // Sicherstellen, dass der Index existiert, bevor darauf zugegriffen wird
        $username = isset($fields[0]) ? $fields[0] : "";
        $email = isset($fields[1]) ? $fields[1] : "";
        $currentRating = isset($fields[2]) ? $fields[2] : "";
        $comment = isset($fields[3]) ? $fields[3] : "";

        if ($currentRating == $ratings[$index]) {
          $count++;
        }
      }
    }



    // Fortschrittsbalken anzeigen
    echo "<label for='file'>$smiley</label>";
    echo "<progress class='mx-3' value='$count' max='" . count($reviews) . "'></progress>";
    // Fortschrittsbalken und Prozentsätze anzeigen
    $totalCount = count($reviews);
    $percentage = ($totalCount > 0) ? round(($count / $totalCount) * 100, 2) : 0;

    $percentages[$index] = $percentage; // Prozentsatz speichern
    // Prozentsätze anzeigen
    // $totalCount = count($reviews);
    // $percentage = ($totalCount > 0) ? round(($count / $totalCount) * 100, 2) : 0;
    // $percentages[$index] = $percentage;

    echo "$percentage%<br>";
  }
  ?>

  <br>
  <table border="1" class="table table-striped table-hover table-bordered table-md">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Rating</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Anzeigen der einzelnen Bewertungen in der Tabelle
      if (file_exists("reviews.txt")) {
        $reviews = file_get_contents("reviews.txt");
        $reviews = explode("\n", $reviews);
        foreach ($reviews as $review) {
          $fields = explode("|", $review);

          // Sicherstellen, dass die Indizes existieren, bevor darauf zugegriffen wird
          $username = isset($fields[0]) ? $fields[0] : "";
          $email = isset($fields[1]) ? $fields[1] : "";
          $currentRating = isset($fields[2]) ? $fields[2] : "";
          $comment = isset($fields[3]) ? $fields[3] : "";

          echo "<tr>";
          echo "<td>$username</td>";
          echo "<td>$email</td>";
          echo "<td>$currentRating</td>";
          echo "<td>$comment</td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>


</body>

</html>