<?php
// admin/search_users.php
// AJAX endpoint used by the dashboard search bar.
// Accepts ?q=... and returns a JSON list of matching users (by name, email, or phone).

require_once '../confiq.php'; // defines $pdo

header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "SELECT id, full_name AS name, email, phone_number AS phone
         FROM people
         WHERE full_name LIKE ? OR email LIKE ? OR phone_number LIKE ?
         ORDER BY full_name ASC
         LIMIT 20" 
    );
    $like = "%$q%";
    $stmt->execute([$like, $like, $like]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Search failed: ' . $e->getMessage()]);
}
