<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#281C59">
    <link rel="manifest" href="<?= BASEURL ?>/public/manifest.json">
    <link rel="apple-touch-icon" href="<?= BASEURL ?>/public/assets/images/icon-192.png">
    
    <title><?= translate('access_denied') ?? 'Scan QR Code' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
            --primary: #281C59;
            --primary-rgb: 40, 28, 89;
            --secondary: #4E8D9C;
            --accent: #85C79A;
            --light: #EDF7BD;
        }
        * { font-family: 'Inter', sans-serif; }
        body { 
            background: linear-gradient(135deg, var(--primary) 0%, #1a1240 100%); 
            min-height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .scan-card { 
            background: white; 
            border: none; 
            border-radius: 24px; 
            overflow: hidden; 
            box-shadow: 0 16px 40px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }

        .lang-switcher { background: rgba(0,0,0,0.05); padding: 4px; border-radius: 12px; white-space: nowrap; display: inline-flex; }
        .lang-switcher a { font-size: 0.9rem; font-weight: 700; color: rgba(0,0,0,0.4); text-decoration: none; transition: all 0.2s; padding: 4px 10px; border-radius: 8px; }
        .lang-switcher a:hover { color: var(--primary); background: rgba(0,0,0,0.05); }
        .lang-switcher .active-lang { background: white !important; color: var(--primary) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        #reader {
            width: 100%;
            border: none !important;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
        }
        #reader__scan_region {
            background: #000;
        }
        #reader button {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }
        #reader a {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    
    <div class="scan-card p-4 text-center">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0" style="color: var(--primary);">🍕 E-Menu</h4>
            <div class="lang-switcher">
                <a href="<?= BASEURL ?>/public/language/switch/id" class="<?= ($_SESSION['lang'] ?? 'id') == 'id' ? 'active-lang' : '' ?>">ID</a>
                <a href="<?= BASEURL ?>/public/language/switch/en" class="<?= ($_SESSION['lang'] ?? 'id') == 'en' ? 'active-lang' : '' ?>">EN</a>
                <a href="<?= BASEURL ?>/public/language/switch/vi" class="<?= ($_SESSION['lang'] ?? 'id') == 'vi' ? 'active-lang' : '' ?>">VN</a>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-bold" style="color: var(--primary);"><?= translate('menu_no_access') ?? 'Please Scan QR' ?></h5>
            <p class="text-muted small m-0"><?= translate('scan_qr_msg') ?? 'Scan the QR code on your table to view the menu and order.' ?></p>
        </div>

        <div id="reader"></div>
        
        <div class="mt-4 pt-3 border-top">
            <small class="text-muted d-block mb-2">PWA Ready</small>
            <button id="btnInstallPwa" class="btn btn-sm fw-bold d-none shadow-sm text-white w-100 py-2" style="background: var(--primary); border-radius: 12px;">
                ⬇️ Install App
            </button>
        </div>
    </div>

    <script>
        // Initialize Scanner
        let html5QrcodeScanner;
        
        function onScanSuccess(decodedText, decodedResult) {
            // Check if the decoded text is a valid URL and contains our BASEURL
            if(decodedText.includes("<?= BASEURL ?>") && (decodedText.includes("table=") || decodedText.includes("tableNumber="))) {
                html5QrcodeScanner.clear();
                // Redirect to the scanned URL
                window.location.href = decodedText;
            } else {
                alert("Invalid E-Menu QR Code!");
                // Optionally wait a bit before scanning again
            }
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning
        }

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);


        // PWA Installation
        let deferredPrompt;
        const btnInstallPwa = document.getElementById('btnInstallPwa');

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= BASEURL ?>/public/sw.js');
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            btnInstallPwa.classList.remove('d-none');
            
            btnInstallPwa.addEventListener('click', () => {
                btnInstallPwa.classList.add('d-none');
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome !== 'accepted') {
                        btnInstallPwa.classList.remove('d-none');
                    }
                    deferredPrompt = null;
                });
            });
        });
        
        window.addEventListener('appinstalled', () => {
            btnInstallPwa.classList.add('d-none');
            deferredPrompt = null;
        });
    </script>
</body>
</html>
