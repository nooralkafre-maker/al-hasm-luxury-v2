# Lightweight localhost-only preview server. Requires Windows PowerShell, not PHP/Composer.
$ErrorActionPreference = 'Stop'
$Root = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$IP = [System.Net.IPAddress]::Parse('127.0.0.1')
$Port = 8844
$Server = [System.Net.Sockets.TcpListener]::new($IP, $Port)
try { $Server.Start() } catch { Write-Host 'Preview port 8844 is already in use. Existing preview may be running.'; exit 1 }
Write-Host 'AL HASM browser preview is running at http://127.0.0.1:8844/preview/index.html' -ForegroundColor Green
Write-Host 'Dashboard: http://127.0.0.1:8844/preview/dashboard.html'
Write-Host 'Stop: close this terminal or press Ctrl+C.'
$Types = @{'.html'='text/html; charset=utf-8';'.css'='text/css; charset=utf-8';'.js'='text/javascript; charset=utf-8';'.svg'='image/svg+xml';'.png'='image/png';'.webp'='image/webp';'.jpg'='image/jpeg';'.jpeg'='image/jpeg';'.ico'='image/x-icon'}
while ($true) {
  $Client = $null
  try {
    $Client = $Server.AcceptTcpClient()
    $Client.ReceiveTimeout = 8000
    $Client.SendTimeout = 8000
    $Stream = $Client.GetStream()
    $Reader = [System.IO.StreamReader]::new($Stream,[System.Text.Encoding]::ASCII,$false,4096,$true)
    $Line = $Reader.ReadLine()
    while ($true) { $h = $Reader.ReadLine(); if ($null -eq $h -or $h -eq '') { break } }
    $RawPath = if ($Line -match '^(GET|HEAD)\s+([^\s]+)') { $Matches[2].Split('?')[0] } else { '/_bad_' }
    $Path = [System.Uri]::UnescapeDataString($RawPath).Replace('\','/')
    if ($Path -eq '/') { $Path = '/preview/index.html' }
    $Allowed = ($Path -eq '/preview/index.html' -or $Path -eq '/preview/dashboard.html' -or $Path.StartsWith('/public/assets/'))
    if ($Path.Contains('..') -or !$Allowed) { $Status='403 Forbidden'; $Bytes=[System.Text.Encoding]::UTF8.GetBytes('Forbidden'); $Mime='text/plain' }
    else {
      $Relative = $Path.TrimStart('/').Replace('/',[System.IO.Path]::DirectorySeparatorChar)
      $File = Join-Path $Root $Relative
      if (Test-Path $File -PathType Leaf) {
        $Status='200 OK';$Bytes=[System.IO.File]::ReadAllBytes($File)
        $Ext=[System.IO.Path]::GetExtension($File).ToLowerInvariant(); $Mime=if ($Types.ContainsKey($Ext)) { $Types[$Ext] } else { 'application/octet-stream' }
      } else { $Status='404 Not Found';$Bytes=[System.Text.Encoding]::UTF8.GetBytes('Not Found');$Mime='text/plain' }
    }
    $Response="HTTP/1.1 $Status`r`nContent-Type: $Mime`r`nContent-Length: $($Bytes.Length)`r`nConnection: close`r`nCache-Control: no-store`r`nX-Content-Type-Options: nosniff`r`n`r`n"
    $Header=[System.Text.Encoding]::ASCII.GetBytes($Response)
    $Stream.Write($Header,0,$Header.Length)
    if ($Bytes.Length -gt 0 -and $Line -notmatch '^HEAD\s') { $Stream.Write($Bytes,0,$Bytes.Length) }
  } catch { Write-Host "Preview request error: $($_.Exception.Message)" -ForegroundColor Yellow }
  finally { if ($Client) { $Client.Close() } }
}
