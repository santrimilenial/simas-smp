# Script untuk mendapatkan URL Ngrok
Start-Sleep -Seconds 2

try {
    $response = Invoke-RestMethod -Uri "http://localhost:4040/api/tunnels" -ErrorAction Stop
    $httpsUrl = $response.tunnels | Where-Object { $_.proto -eq "https" } | Select-Object -First 1 -ExpandProperty public_url
    
    if ($httpsUrl) {
        Write-Host "`n========================================" -ForegroundColor Green
        Write-Host "NGROK URL PUBLIK:" -ForegroundColor Cyan
        Write-Host $httpsUrl -ForegroundColor Yellow
        Write-Host "========================================`n" -ForegroundColor Green
        
        # Save to file
        $httpsUrl | Out-File -FilePath "ngrok-url.txt" -Encoding UTF8
        Write-Host "URL juga disimpan di: ngrok-url.txt`n" -ForegroundColor Gray
        
        return $httpsUrl
    } else {
        Write-Host "Tidak dapat menemukan URL HTTPS" -ForegroundColor Red
    }
} catch {
    Write-Host "Error: Ngrok belum berjalan atau API tidak tersedia" -ForegroundColor Red
    Write-Host "Pastikan ngrok sudah berjalan di terminal lain" -ForegroundColor Yellow
}
