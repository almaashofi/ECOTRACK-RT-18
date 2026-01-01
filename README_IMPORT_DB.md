This folder contains the SQL dump `ecotrack_dump_2025-12-28.sql` exported from phpMyAdmin.

Recommended import options for a local XAMPP (Windows) environment:

1) Using phpMyAdmin
- Open http://localhost/phpmyadmin
- Create a new database named `ecotrack` (collation utf8mb4_general_ci)
- Select the new database, choose Import → Browse → select `ecotrack_dump_2025-12-28.sql` → Go

2) Using MySQL / MariaDB CLI (from an elevated PowerShell / Command Prompt)
- Stop services if needed, then run:

```powershell
# create database (if not exists)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS ecotrack CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
# import
mysql -u root -p ecotrack < "c:\xampp\htdocs\ecotrack\ecotrack1\db\ecotrack_dump_2025-12-28.sql"
```

3) Notes about credentials and app config
- The app reads DB settings from `config/database.php` in the project root. Verify `database` name is `ecotrack`, and `username`/`password` match your local MySQL.
- If you use XAMPP's default `root` without password, set that in `config/database.php` or create a dedicated user.

4) Post-import checks
- Check `uploads/` subfolders (e.g., `uploads/bukti_ronda.php`, `uploads/informasi/`) to ensure referenced files exist. The dump contains filenames (e.g., `695001fe9adb2.png`) but not the binary files.
- Verify `users` table entries and hashed passwords; some seeded rows contain placeholder cleartext passwords (e.g., `admin123`) — change these in production.

If you want, I can:
- Open and show `config/database.php` and suggest exact edits to match the `ecotrack` DB.
- Create a simple PowerShell script to automate the import on Windows.
