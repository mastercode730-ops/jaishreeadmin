# Deployment Script for JaiShree Matka (187.126.118.47)
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host " Deploying to 187.126.118.47" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

$server = "root@187.126.118.47"
$reactPath = "/var/www/jaishree_react"
$adminPath = "/var/www/jaishree_admin"

# 1. Upload React Build
Write-Host "`n[1/3] Uploading React Frontend build (react_build.zip)..." -ForegroundColor Yellow
scp .\react_build.zip "${server}:${reactPath}/"

# 2. Upload Admin & API Updates
Write-Host "`n[2/3] Uploading Admin & API updates (JaiShreeAdmin_update.zip)..." -ForegroundColor Yellow
scp .\JaiShreeAdmin_update.zip "${server}:${adminPath}/"

# 3. Extract and Set Permissions on Server
Write-Host "`n[3/3] Extracting files on remote server..." -ForegroundColor Yellow
$remoteCommands = @"
cd $reactPath && unzip -o react_build.zip && rm react_build.zip && chown -R www-data:www-data $reactPath
cd $adminPath && unzip -o JaiShreeAdmin_update.zip && rm JaiShreeAdmin_update.zip && chown -R www-data:www-data $adminPath
mysql jaishreematka -e "ALTER TABLE payments ADD COLUMN account_number VARCHAR(100) DEFAULT NULL AFTER upi_num, ADD COLUMN ifsc_code VARCHAR(50) DEFAULT NULL AFTER account_number, ADD COLUMN bank_name VARCHAR(100) DEFAULT NULL AFTER ifsc_code, ADD COLUMN account_holder_name VARCHAR(100) DEFAULT NULL AFTER bank_name, MODIFY COLUMN name VARCHAR(100) DEFAULT '', MODIFY COLUMN upi_num VARCHAR(100) DEFAULT '', MODIFY COLUMN qr_img TEXT DEFAULT NULL, MODIFY COLUMN upi_company_img VARCHAR(100) DEFAULT '';" || mysql jaishreematka -e "ALTER TABLE payments MODIFY COLUMN name VARCHAR(100) DEFAULT '', MODIFY COLUMN upi_num VARCHAR(100) DEFAULT '', MODIFY COLUMN qr_img TEXT DEFAULT NULL, MODIFY COLUMN upi_company_img VARCHAR(100) DEFAULT '';"
echo "Deployment Finished Successfully!"
"@

ssh $server $remoteCommands

Write-Host "`nAll files deployed successfully!" -ForegroundColor Green
