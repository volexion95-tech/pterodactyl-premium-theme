#!/bin/bash
# ================================================
#   PREMIUM THEME - Uninstaller
#   github.com/volexion95-tech/pterodactyl-premium-theme
# ================================================
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'
PANEL_DIR="/var/www/pterodactyl"

echo -e "${CYAN}${BOLD}🗑️  PREMIUM THEME UNINSTALLER${NC}\n"

[ "$EUID" -ne 0 ] && echo -e "${RED}Run as root!${NC}" && exit 1

echo -e "${YELLOW}Removing theme files...${NC}"
rm -f "$PANEL_DIR/resources/scripts/custom.css"
rm -f "$PANEL_DIR/resources/views/admin/theme/index.blade.php"
rm -f "$PANEL_DIR/app/Http/Controllers/Admin/ThemeController.php"

echo -e "${YELLOW}Removing CSS import from index.tsx...${NC}"
sed -i '/import ".\/custom.css";/d' "$PANEL_DIR/resources/scripts/index.tsx"

echo -e "${YELLOW}Removing admin route...${NC}"
sed -i '/\/\/ Premium Theme/,/^});$/d' "$PANEL_DIR/routes/admin.php"

echo -e "${YELLOW}Rebuilding assets...${NC}"
cd "$PANEL_DIR"
export NODE_OPTIONS=--openssl-legacy-provider
yarn build:production 2>/dev/null

php artisan view:clear &>/dev/null
php artisan config:clear &>/dev/null
chown -R www-data:www-data /var/www/pterodactyl/* 2>/dev/null || true

echo -e "\n${GREEN}${BOLD}✅ Theme uninstalled! Press CTRL+SHIFT+R${NC}\n"
