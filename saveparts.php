<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$page = 'Add Parts';

require_once 'includes/conn.php';

require_once 'includes/class.parts.php';

$parts = new Parts($pdo);

/**
 * require_once 'includes/class.category.php';
 *
 * $categories = new Category($pdo);
 * $fullCats = $categories->getAll();
 **/

// $currentDir = getcwd();
$uploadDirectory = '/home/thaynefam/thaynefam-upload/jewel/';

$errors = []; // Store all foreseen and unforseen errors here

// Get all the file extensions
$fileExtensions = [
    'jpeg',
    'jpg',
    'png',
];

// var_dump($_POST);
// echo PHP_EOL;
// var_dump($_FILES);
// die();

$a = [];

$fileName = $_FILES['picture']['name'];
$fileSize = $_FILES['picture']['size'];
$fileTmpName  = $_FILES['picture']['tmp_name'];
$fileType = $_FILES['picture']['type'];
$fileExtensionStart = explode('.',$fileName);
$fileExtension = strtolower(end($fileExtensionStart));

// $uploadPath = $currentDir . $uploadDirectory . basename($fileName);
$uploadPath = $uploadDirectory . basename($fileName);

if (isset($_POST['name'])) {
    if (in_array($fileExtension,$fileExtensions) === false) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
    }

    if ($fileSize > 2000000) {
        $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
    }

    if (empty($errors)) {
        $partId = $parts->addNewPart($_POST['name'], $_POST['description'], $_POST['purchased'], $_POST['quantity'], $_POST['price'], $_POST['category']);

        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
            $parts->addFileToPart($partId, basename($fileName));
            $a['successful'] = 'yes';
        } else {
            $a['successful'] = 'no';
            $a['errors'][] = "An error occurred with the upload. Try again or contact the admin";
        }
    } else {
        $a['errors'] = $errors;
    }
}

echo json_encode($a);

