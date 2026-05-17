param(
    [string]$MysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe",
    [string]$DbHost = "127.0.0.1",
    [int]$DbPort = 3306,
    [string]$DbUser = "root",
    [string]$DbName = "toloNJedia",
    [string]$DbPassword = "",
    [switch]$PromptPassword,
    [string]$SqlFile = "D:\\NJediah\\tokoNJedia\\data-import\\excel-samples\\import_mysql.sql"
)

if (-not (Test-Path -LiteralPath $MysqlExe)) {
    Write-Error "mysql.exe tidak ditemukan di: $MysqlExe"
    exit 1
}

if (-not (Test-Path -LiteralPath $SqlFile)) {
    Write-Error "File SQL import tidak ditemukan: $SqlFile"
    exit 1
}

$sqlFileForMysql = $SqlFile -replace "\\", "/"
$sourceCommand = "source `"$sqlFileForMysql`";"

Write-Host "Menjalankan import CSV -> MySQL menggunakan SOURCE command..."
Write-Host "Host: ${DbHost}:$DbPort | DB: $DbName | User: $DbUser"

$mysqlArgs = @("--local-infile=1", "-h", $DbHost, "-P", $DbPort, "-u", $DbUser)
if ($PromptPassword) {
    $mysqlArgs += "-p"
} elseif (-not [string]::IsNullOrWhiteSpace($DbPassword)) {
    $mysqlArgs += "-p$DbPassword"
}

$createDbSql = "CREATE DATABASE IF NOT EXISTS $DbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
& $MysqlExe @mysqlArgs -e $createDbSql
if ($LASTEXITCODE -ne 0) {
    Write-Error "Gagal memastikan database '$DbName' tersedia."
    exit $LASTEXITCODE
}

$mysqlArgs += @($DbName, "-e", $sourceCommand)

$result = & $MysqlExe @mysqlArgs 2>&1
$exitCode = $LASTEXITCODE

if ($result) {
    $result | ForEach-Object { Write-Host $_ }
}

$resultText = ($result | Out-String)
if ($exitCode -ne 0 -or $resultText -match "ERROR [0-9]+") {
    $failCode = if ($exitCode -eq 0) { 1 } else { $exitCode }
    Write-Error "Import gagal dengan exit code: $failCode"
    exit $failCode
}

Write-Host "Import selesai."
