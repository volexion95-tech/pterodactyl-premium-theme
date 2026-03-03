#!/bin/bash
# ================================================
#   PREMIUM THEME - Auto Installer
#   github.com/volexion95-tech/pterodactyl-premium-theme
# ================================================
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'
PANEL_DIR="/var/www/pterodactyl"
REPO_URL="https://raw.githubusercontent.com/volexion95-tech/pterodactyl-premium-theme/main"
THEME_VERSION="1.0.0"

clear
echo -e "${CYAN}"
echo "  ██████╗ ██████╗ ███████╗███╗   ███╗██╗██╗   ██╗███╗   ███╗"
echo "  ██╔══██╗██╔══██╗██╔════╝████╗ ████║██║██║   ██║████╗ ████║"
echo "  ██████╔╝██████╔╝█████╗  ██╔████╔██║██║██║   ██║██╔████╔██║"
echo "  ██╔═══╝ ██╔══██╗██╔══╝  ██║╚██╔╝██║██║██║   ██║██║╚██╔╝██║"
echo "  ██║     ██║  ██║███████╗██║ ╚═╝ ██║██║╚██████╔╝██║ ╚═╝ ██║"
echo "  ╚═╝     ╚═╝  ╚═╝╚══════╝╚═╝     ╚═╝╚═╝ ╚═════╝ ╚═╝     ╚═╝"
echo -e "${NC}"
echo -e "  ${BOLD}         🎨 PREMIUM THEME INSTALLER v${THEME_VERSION}${NC}"
echo -e "  ${CYAN}         by volexion95-tech${NC}"
echo ""

info()    { echo -e "  ${CYAN}[INFO]${NC}  $1"; }
success() { echo -e "  ${GREEN}[OK]${NC}    $1"; }
warn()    { echo -e "  ${YELLOW}[WARN]${NC}  $1"; }
error()   { echo -e "  ${RED}[ERROR]${NC} $1"; exit 1; }
step()    { echo -e "\n  ${BOLD}${BLUE}[$1]${NC} ${BOLD}$2${NC}"; echo -e "  ${YELLOW}────────────────────────────────${NC}"; }

step "1/7" "Checking requirements"
[ "$EUID" -ne 0 ] && error "Run as root: sudo bash install.sh"
[ ! -d "$PANEL_DIR" ] && error "Panel not found at $PANEL_DIR"
! command -v node &>/dev/null && error "Node.js not installed!"
! command -v yarn &>/dev/null && npm install -g yarn &>/dev/null
success "All checks passed"

step "2/7" "Creating backup"
BACKUP="/var/www/pterodactyl-backup-$(date +%Y%m%d_%H%M%S)"
cp -r "$PANEL_DIR/resources/scripts" "$BACKUP" 2>/dev/null
success "Backup at $BACKUP"

step "3/7" "Downloading theme files"
curl -fsSL "$REPO_URL/theme/custom.css" -o "$PANEL_DIR/resources/scripts/custom.css" || error "Download failed"
mkdir -p "$PANEL_DIR/resources/views/admin/theme"
curl -fsSL "$REPO_URL/theme/admin-theme.blade.php" -o "$PANEL_DIR/resources/views/admin/theme/index.blade.php" || error "Download failed"
curl -fsSL "$REPO_URL/theme/ThemeController.php" -o "$PANEL_DIR/app/Http/Controllers/Admin/ThemeController.php" || error "Download failed"
success "All files downloaded"

step "4/7" "Injecting CSS import"
INDEX="$PANEL_DIR/resources/scripts/index.tsx"
[ ! -f "$INDEX" ] && error "index.tsx not found"
grep -q "custom.css" "$INDEX" || sed -i '1s/^/import ".\/custom.css";\n/' "$INDEX"
success "CSS import added"

step "5/7" "Adding admin route"
ROUTES="$PANEL_DIR/routes/admin.php"
if ! grep -q "ThemeController" "$ROUTES"; then
  printf "\n// Premium Theme\nRoute::group(['prefix' => 'theme'], function () {\n    Route::get('/', [App\\Http\\Controllers\\Admin\\ThemeController::class, 'index'])->name('admin.theme');\n    Route::post('/', [App\\Http\\Controllers\\Admin\\ThemeController::class, 'update'])->name('admin.theme.update');\n});\n" >> "$ROUTES"
fi
success "Route added"

step "6/7" "Building assets"
cd "$PANEL_DIR"
export NODE_OPTIONS=--openssl-legacy-provider
yarn install --silent 2>/dev/null
yarn build:production 2>/dev/null && success "Build done!" || warn "Build warnings - check manually"

step "7/7" "Cleanup"
php artisan view:clear &>/dev/null
php artisan config:clear &>/dev/null
php artisan cache:clear &>/dev/null
chown -R www-data:www-data /var/www/pterodactyl/* 2>/dev/null || true
success "Done!"

echo ""
echo -e "  ${GREEN}${BOLD}✅ INSTALLED! Press CTRL+SHIFT+R in your browser${NC}"
echo -e "  ${CYAN}Admin settings: yourpanel.com/admin/theme${NC}"
echo -e "  ${YELLOW}Backup: $BACKUP${NC}"
echo ""
