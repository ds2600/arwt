#!/bin/bash

PROJECT_DIR=$(pwd)

set_dir_permissions() {
    echo "Setting directory permissions..."
    sudo find "$PROJECT_DIR" -type d -exec chmod 775 {} \;
}

set_file_permissions() {
    echo "Setting file permissions..."
    sudo find "$PROJECT_DIR/$1" -type f -exec chmod 644 {} \;
}

copy_config() {
    if [ ! -f "$PROJECT_DIR/$2" ]; then
        cp "$PROJECT_DIR/$1" "$PROJECT_DIR/$2"
        echo "Created $2 from $1"
    fi
}

copy_config "config/config.example.php" "config/config.php"
copy_config ".env.example" ".env"

set_dir_permissions "classes"
set_dir_permissions "common"
set_dir_permissions "config"
set_dir_permissions "public"
set_dir_permissions "sql"
set_dir_permissions "tmp"

chmod -R 770 "$PROJECT_DIR/tmp"
chmod -R 750 "$PROJECT_DIR/public/dash"

set_file_permissions "classes"
set_file_permissions "common"
set_file_permissions "config"
set_file_permissions "public"
set_file_permissions "sql"

chmod 660 "$PROJECT_DIR/config/config.php"
chmod 660 "$PROJECT_DIR/.env"
chmod 644 "$PROJECT_DIR/.htaccess"

composer install --no-dev

echo "Installation complete."
echo "Next - modify the config.php and .env files for your environment."