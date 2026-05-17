param(
    [string]$MysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe",
    [string]$DbHost = "127.0.0.1",
    [int]$DbPort = 3306,
    [string]$DbUser = "root",
    [string]$DbName = "toloNJedia",
    [string]$DbPassword = "",
    [switch]$PromptPassword,
    [string]$ValidateSql = ""
)

function Test-TcpPort {
    param(
        [string]$TcpHost,
        [int]$Port
    )

    try {
        $client = New-Object System.Net.Sockets.TcpClient
        $iar = $client.BeginConnect( $TcpHost, $Port, $null, $null)
        $wait = $iar.AsyncWaitHandle.WaitOne(1200, $false)
        if (-not $wait) {
            $client.Close()
            return $false
        }
        $client.EndConnect($iar) | Out-Null
        $client.Close()
        return $true
    } catch {
        return $false
    }
}

if (-not (Test-Path -LiteralPath $MysqlExe)) {
    Write-Error "mysql.exe tidak ditemukan di: $MysqlExe"
    exit 1
}

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot "..\\..")).Path
if ([string]::IsNullOrWhiteSpace($ValidateSql)) {
    $ValidateSql = (Join-Path $projectRoot "scripts\\db\\validate_seed_counts.sql")
}

if (-not (Test-Path -LiteralPath $ValidateSql)) {
    Write-Error "File validasi SQL tidak ditemukan: $ValidateSql"
    exit 1
}

if (-not (Test-TcpPort -TcpHost $DbHost -Port $DbPort)) {
    Write-Error "MySQL belum aktif di ${DbHost}:$DbPort. Jalankan MySQL dulu dari XAMPP Control Panel."
    exit 1
}

$adminArgs = @("-h", $DbHost, "-P", $DbPort, "-u", $DbUser)
if ($PromptPassword) {
    $adminArgs += "-p"
} elseif (-not [string]::IsNullOrWhiteSpace($DbPassword)) {
    $adminArgs += "-p$DbPassword"
}

$createDbSql = "CREATE DATABASE IF NOT EXISTS $DbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
& $MysqlExe @adminArgs -e $createDbSql
if ($LASTEXITCODE -ne 0) {
    Write-Error "Gagal memastikan database '$DbName' tersedia."
    exit $LASTEXITCODE
}

Push-Location $projectRoot
try {
    Write-Host "Menjalankan migrate:fresh --seed ..."
    & php artisan migrate:fresh --seed
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Seeding gagal. Cek error di atas."
        exit $LASTEXITCODE
    }

    $validateFileForMysql = $ValidateSql -replace "\\", "/"
    $sourceCommand = "source `"$validateFileForMysql`";"

    $mysqlArgs = @("--table", "-h", $DbHost, "-P", $DbPort, "-u", $DbUser)
    if ($PromptPassword) {
        $mysqlArgs += "-p"
    } elseif (-not [string]::IsNullOrWhiteSpace($DbPassword)) {
        $mysqlArgs += "-p$DbPassword"
    }
    $mysqlArgs += @($DbName, "-e", $sourceCommand)

    Write-Host "Menjalankan validasi jumlah data tabel..."
    & $MysqlExe @mysqlArgs
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Validasi count gagal."
        exit $LASTEXITCODE
    }

    Write-Host "Seeding dan validasi selesai."
} finally {
    Pop-Location
}
