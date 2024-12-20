<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "login"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? null;

if ($action === 'getDecks') {
    $result = $conn->query("SELECT * FROM decks");
    $decks = [];

    while ($row = $result->fetch_assoc()) {
        $decks[] = $row;
    }

    echo json_encode($decks);
} elseif ($action === 'createDeck') {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO decks (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        echo json_encode(['deck_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['error' => 'Failed to create deck.']);
    }
} elseif ($action === 'getFlashcards') {
    $deck_id = $_POST['deck_id'];
    $result = $conn->query("SELECT * FROM flashcards WHERE deck_id = $deck_id");
    $flashcards = [];

    while ($row = $result->fetch_assoc()) {
        $flashcards[] = $row;
    }

    echo json_encode($flashcards);
} elseif ($action === 'addFlashcard') {
    $deck_id = $_POST['deck_id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("INSERT INTO flashcards (deck_id, question, answer) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $deck_id, $question, $answer);
    
    if ($stmt->execute()) {
        echo json_encode(['flashcard_id' => $stmt->insert_id]);
    } else {
        echo json_encode(['error' => 'Failed to add flashcard.']);
    }
} elseif ($action === 'editFlashcard') {
    $flashcard_id = $_POST['flashcard_id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Validate inputs
    if (empty($flashcard_id) || empty($question) || empty($answer)) {
        echo json_encode(['error' => 'Flashcard ID, question, and answer cannot be empty.']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE flashcards SET question = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("ssi", $question, $answer, $flashcard_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Flashcard updated successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to update flashcard.']);
    }
} elseif ($action === 'deleteFlashcard') {
    $flashcard_id = $_POST['flashcard_id'];

    $stmt = $conn->prepare("DELETE FROM flashcards WHERE id = ?");
    $stmt->bind_param("i", $flashcard_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Flashcard deleted successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to delete flashcard.']);
    }
} elseif ($action === 'deleteDeck') {
    $deck_id = $_POST['deck_id'];

    // Delete associated flashcards first
    $conn->query("DELETE FROM flashcards WHERE deck_id = $deck_id");

    $stmt = $conn->prepare("DELETE FROM decks WHERE id = ?");
    $stmt->bind_param("i", $deck_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Deck deleted successfully.']);
    } else {
        echo json_encode(['error' => 'Failed to delete deck.']);
    }
}

$conn->close();
?>
