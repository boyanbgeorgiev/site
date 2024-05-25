<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadedFile = $_FILES['RemoteFile']['tmp_name'];
    $destinationPath = 'uploads/' . basename($_FILES['RemoteFile']['name']);

    if (move_uploaded_file($uploadedFile, $destinationPath)) {
        echo 'Файлът е успешно получен и съхранен.';
    } else {
        echo 'Неуспешно качване на файла.';
    }
} else {
    echo 'Невалидна заявка.';
}
?>
