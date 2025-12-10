<?php
require "session_config.php";
require "db.php";

$logged = $_SESSION["user_id"];
$other  = $_GET["other_id"] ?? null;

if (!$other) exit;

$stmt = $pdo->prepare("
 SELECT * FROM messages
 WHERE (sender_id=? AND receiver_id=?)
    OR (sender_id=? AND receiver_id=?)
 ORDER BY created_at ASC
");
$stmt->execute([$logged, $other, $other, $logged]);
$messages = $stmt->fetchAll();

foreach ($messages as $m) {
    $class = ($m["sender_id"] == $logged) ? "me" : "other";

    echo "<div class='message $class'>";
    echo nl2br(htmlspecialchars($m["message"]));
    echo "<br><small>". $m["created_at"] ."</small>";
    echo "</div>";
}
