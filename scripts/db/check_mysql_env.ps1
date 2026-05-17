param(
    [string]$MysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe",
    [string]$DbHost = "127.0.0.1",
    [int]$DbPort = 3306
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

Write-Host "== MySQL Environment Check =="

if (Test-Path -LiteralPath $MysqlExe) {
    Write-Host "[OK] mysql.exe ditemukan: $MysqlExe"
} else {
    Write-Host "[FAIL] mysql.exe tidak ditemukan: $MysqlExe"
}

if (Test-TcpPort -TcpHost $DbHost -Port $DbPort) {
    Write-Host "[OK] Port MySQL aktif di ${DbHost}:$DbPort"
} else {
    Write-Host "[FAIL] Port MySQL belum aktif di ${DbHost}:$DbPort"
    Write-Host "       Jalankan MySQL dari XAMPP Control Panel, lalu cek ulang."
}
