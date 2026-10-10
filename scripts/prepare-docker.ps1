[CmdletBinding()]
param(
    [switch]$Force
)

$ErrorActionPreference = 'Stop'
$destination = Join-Path $PSScriptRoot '..\.env.docker'

if ((Test-Path -LiteralPath $destination) -and -not $Force) {
    Write-Output '.env.docker ya existe; no se modifico.'
    exit 0
}

$keyBytes = New-Object byte[] 32
$random = New-Object System.Security.Cryptography.RNGCryptoServiceProvider
$random.GetBytes($keyBytes)
$appKey = 'base64:' + [Convert]::ToBase64String($keyBytes)

$passwordBytes = New-Object byte[] 24
$random.GetBytes($passwordBytes)
$random.Dispose()
$password = 'Gls!' + ([BitConverter]::ToString($passwordBytes).Replace('-', '')).ToLowerInvariant()

$content = @"
APP_NAME="Grand Line Store"
APP_ENV=production
APP_KEY=$appKey
APP_DEBUG=false
APP_PORT=8080

LOG_CHANNEL=stderr
LOG_LEVEL=warning

DB_CONNECTION=sqlsrv
DB_HOST=sqlserver
DB_PORT=1433
DB_DATABASE=redline
DB_USERNAME=sa
DB_PASSWORD=$password
DB_ENCRYPT=yes
DB_TRUST_SERVER_CERTIFICATE=true

MSSQL_SA_PASSWORD=$password

SESSION_DRIVER=database
SESSION_CONNECTION=sqlsrv
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

FILESYSTEM_DISK=public
CACHE_STORE=database
QUEUE_CONNECTION=sync
"@

[System.IO.File]::WriteAllText($destination, $content, [System.Text.UTF8Encoding]::new($false))
Write-Output '.env.docker creado con APP_KEY y contrasena SQL aleatorias. Los secretos no se mostraron.'
