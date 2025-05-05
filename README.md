# PiCloud Web & Cloud Sync System

A web-based file upload interface integrated with Google Drive, Dropbox, and OneDrive. Users can upload files through a web application, and the files are automatically distributed to all three cloud services. In addition, the system includes automated weekly backups that synchronize cloud folders to a local Raspberry Pi, ensuring redundancy and offline access.

## ✨ Features

- Google Drive, Dropbox, and OneDrive file uploads via web interface.
- OAuth 2.0 authentication flows for all services.
- Multi-service simultaneous uploads.
- Weekly automated backup scripts (using Rclone).
- Files are stored locally in structured folders per service.
- Designed for home or educational environments.
- Clean UI with drag-and-drop file upload.

## 📂 Technologies

- **Frontend**: HTML, CSS (custom & Bootstrap-like styles).
- **Backend**: PHP, cURL.
- **Cloud APIs**: Google Drive API, Dropbox API, Microsoft Graph API.
- **Sync**: Rclone.
- **Server**: Raspberry Pi OS (Linux) & Apache.
- **OAuth 2.0** authentication.

## 🖥️ Folder Structure

- `gestionar_archivos.php` → Google Drive upload interface.
- `gestionar_onedrive.php` → OneDrive upload interface.
- `gestionar_dropbox.php` → Dropbox upload interface.
- `pujada_global.php` → Upload to all services simultaneously.
- `backup_scripts/` → Weekly Rclone backup scripts.

## 🔒 Security

⚠ **Important:** Before publishing or deploying:
- Remove or exclude all sensitive credentials (`client_id`, `client_secret`, `refresh_token`).
- Use environment variables or `.env` files to securely load API credentials in production.
- Sessions and tokens should not be hardcoded or stored in the repository.

## 📄 License

MIT License
