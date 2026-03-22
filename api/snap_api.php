<?php
 require_once 'database.php';
// API handler for course operations
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'get_folder':
        get_all_folders();
        break;
    case 'get_snippets':
        $folderId = $_REQUEST['id'] ?? null;
        get_snippets($folderId);
        break;
    case 'upload_snippets':
        $title = $_REQUEST['title'] ?? '';
        $folderId = $_REQUEST['folder_id'] ?? null;
        $tags = $_REQUEST['tags'] ?? '';
        $image = $_FILES['image'] ?? null;
            if (!$title ||!$folderId || !$tags || !$image) {
                echo json_encode(['error' => 'All fields are required']);
                return;
            }
        upload_snippets($title, $folderId, $tags, $image);    
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

    function   upload_snippets($title, $folderId, $tags, $image){
        global $pdo;
        $imagePath = '../uploads/' . uniqid() . '_' . $image['name'];
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $query = "INSERT INTO snippets (title, folder_id, tags, image) VALUES (:title, :folderId, :tags, :imagePath)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'title' => $title,
                'folderId' => $folderId,
                'tags' => $tags,
                'imagePath' => $imagePath
            ]);
            echo json_encode(['status' => 200, 'data' => ['id' => $pdo->lastInsertId(), 'title' => $title, 'folder_id' => $folderId, 'tags' => $tags, 'image' => $imagePath]]);
        } else {
            echo json_encode(['error' => 'Failed to upload image']);
        }
    }

function  get_snippets($folderId) {
     global $pdo;  
    if($folderId) {
        if (!$folderId) {
            echo json_encode(['error' => 'Folder ID is required']);
            return;
        }
        $query = "SELECT s.*, f.name as folder_name,f.id as folder_id FROM snippets s LEFT JOIN folders f ON s.folder_id = f.id WHERE f.id = :folderId";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['folderId' => $folderId]);
        
        if (!$stmt) {
            echo json_encode(['error' => 'Query failed']);
            return;
        }
        
        $snippets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $snippets[] = $row;
        }
        
        echo json_encode(['status' => 200, 'data' => $snippets]);
    } 
}
function get_all_folders() {
    global $pdo;
    
    $query = "SELECT * FROM folders";
    $result = $pdo->query($query);
    
    if (!$result) {
        echo json_encode(['error' => 'Query failed']);
        return;
    }
    
    $folders = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $folders[] = $row;
    }
    
    echo json_encode(['status' => 200, 'data' => $folders]);
}

?>

