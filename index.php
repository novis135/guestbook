<?php
// Konfigurasi koneksi database
$host = 'localhost';
$dbname = 'guestbook_db';
$user = 'root';       // Ganti sesuai user MySQL kamu
$pass = '';           // Ganti sesuai password MySQL kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = strip_tags(trim($_POST['name']));
    $message = strip_tags(trim($_POST['message']));

    if ($name && $message) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, message) VALUES (:name, :message)");
        $stmt->execute(['name' => $name, 'message' => $message]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Guestbook with MySQL</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f0f4f8; padding: 40px; }
    h1 { text-align: center; }
    form {
      max-width: 500px; margin: 0 auto 30px auto; background: white;
      padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    label { font-weight: bold; margin-top: 10px; display: block; }
    input[type="text"], textarea {
      width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-top: 5px;
    }
    button {
      margin-top: 15px; padding: 10px 20px; background-color: #3498db;
      color: white; border: none; border-radius: 4px; cursor: pointer;
    }
    .entry {
      max-width: 500px; margin: 10px auto; background: #fff;
      padding: 15px; border-left: 4px solid #3498db;
    }
    .entry p { margin: 0; }
    .entry .name { font-weight: bold; }
  </style>
</head>
<body>

<h1>Guestbook</h1>

<form method="POST">
  <label for="name">Your Name:</label>
  <input type="text" name="name" id="name" required />

  <label for="message">Your Message:</label>
  <textarea name="message" id="message" rows="5" required></textarea>

  <button type="submit">Submit</button>
</form>

<?php
// Ambil dan tampilkan pesan
$stmt = $pdo->query("SELECT name, message, created_at FROM messages ORDER BY id DESC");
foreach ($stmt as $row) {
    echo "<div class='entry'>";
    echo "<p class='name'>" . htmlspecialchars($row['name']) . " <small>(" . $row['created_at'] . ")</small></p>";
    echo "<p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
    echo "</div>";
}
?>

</body>
</html>
