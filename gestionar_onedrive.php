<?php
session_start();

if (!isset($_SESSION['onedrive_access_token'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestió d'Arxius - OneDrive</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            background: linear-gradient(135deg, #800020, #330000);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }

        .upload-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
        }

        h1, p {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .drop-zone {
            padding: 40px;
            border: 2px dashed rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .drop-zone p {
            margin: 0;
            font-size: 16px;
        }

        #preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .upload-button {
            padding: 12px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        .upload-button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <h1>Gestió d'Arxius</h1>
    <p>Arrossega i deixa anar arxius aquí per pujar-los a OneDrive.</p>

    <form action="upload_to_onedrive.php" method="post" enctype="multipart/form-data" id="upload-form">
        <input type="file" name="archivo" id="file-input" hidden>
        <div class="drop-zone" id="drop-zone">
            <p id="drop-message">Arrossega i deixa anar arxius aquí o fes clic per seleccionar</p>
            <div id="preview"></div>
        </div>
        <button type="submit" class="upload-button">Pujar a OneDrive</button>
        <div style="margin-top: 30px;">
  <a href="connectar_tots.php" style="color: white; font-weight: bold; text-decoration: underline;">
    ← Torna a la pàgina de connexió múltiple
  </a>
</div>

    </form>
</div>

<script>
    const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("file-input");
    const preview = document.getElementById("preview");
    const dropMessage = document.getElementById("drop-message");

    dropZone.addEventListener("click", () => fileInput.click());

    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showPreview(files[0]);
        }
    });

    fileInput.addEventListener("change", () => {
        if (fileInput.files.length > 0) {
            showPreview(fileInput.files[0]);
        }
    });

    function showPreview(file) {
        preview.innerHTML = "";
        if (file.type.startsWith("image/")) {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);
        } else {
            const fileInfo = document.createElement("p");
            fileInfo.textContent = `Arxiu seleccionat: ${file.name}`;
            preview.appendChild(fileInfo);
        }
        dropMessage.textContent = "Arxiu llest per pujar:";
    }
</script>

</body>
</html>
