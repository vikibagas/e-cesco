# e-Cesco (Legacy PHP)

Native PHP e-Cesco application source imported from the legacy project.

## Local setup

1. Configure database credentials in the excluded local config files (`config/connection.php` / `config/db_connect.php`) based on your deployment environment.
2. Point Apache/Nginx document root to this directory and enable the required PHP extensions used by the application.
3. Restore required operational data and private uploads from the organization's secure backup, not from Git.

This repository intentionally excludes credentials, logs, operational uploads/photos, database dumps, and large generated reports.
