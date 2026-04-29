# test_routes.ps1
$baseUrl = "http://127.0.0.1:8000"
$routes = @(
    @{ name = "Landing Page"; path = "/"; method = "GET" },
    @{ name = "Layanan"; path = "/layanan"; method = "GET" },
    @{ name = "FAQ"; path = "/faq"; method = "GET" },
    @{ name = "Login Page"; path = "/login"; method = "GET" },
    @{ name = "Register Page"; path = "/register"; method = "GET" }
)

Write-Host "`n--- Memulai Testing Route Koperasi Majakara ---" -ForegroundColor Cyan

foreach ($route in $routes) {
    $url = "$baseUrl$($route.path)"
    Write-Host "Testing $($route.name) ($url)... " -NoNewline
    
    try {
        # Gunakan Invoke-WebRequest dengan -UseBasicParsing untuk menghindari popup IE
        $response = Invoke-WebRequest -Uri $url -Method $route.method -Headers @{"Accept"="application/json"} -UseBasicParsing -TimeoutSec 5 -ErrorAction SilentlyContinue
        
        $status = $response.StatusCode
        if ($status -eq 200) {
            Write-Host "SUCCESS ($status)" -ForegroundColor Green
        } else {
            Write-Host "WARNING ($status)" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "FAILED: Error connecting to server." -ForegroundColor Red
    }
}

Write-Host "--- Testing Selesai ---`n" -ForegroundColor Cyan
