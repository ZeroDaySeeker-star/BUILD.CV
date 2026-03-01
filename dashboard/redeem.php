<?php
require_once __DIR__ . '/../includes/auth-check.php';
require_once __DIR__ . '/../classes/PremiumCode.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utiliser un code Premium - BUILD.CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/dashboard.css">
    <style>
        .redeem-container {
            max-width: 500px;
            margin: 40px auto;
            text-align: center;
        }
        .redeem-card {
            background: var(--surface);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            border: 1px solid var(--border);
        }
        .redeem-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .redeem-input {
            width: 100%;
            padding: 12px 15px;
            font-size: 1.2rem;
            text-align: center;
            letter-spacing: 2px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            background: var(--surface-2);
            color: var(--text);
            margin-bottom: 20px;
            text-transform: uppercase;
            transition: border-color 0.2s;
        }
        .redeem-input:focus {
            outline: none;
            border-color: var(--primary);
            border-style: solid;
        }
        #redeemMessage {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            display: none;
            font-size: 0.9rem;
        }
        .msg-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border: 1px solid var(--error);
        }
        .msg-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                <div>
                    <h1>Utiliser un Code Premium</h1>
                    <p>Débloquez des fonctionnalités avancées avec votre code</p>
                </div>
            </div>
        </header>

        <main class="page-content">
            <div class="redeem-container" style="max-width: 800px; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
                
                <!-- Instructions de Paiement -->
                <div class="payment-instructions" style="background: var(--surface); padding: 30px; border-radius: 12px; border: 1px solid var(--border); text-align: left;">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">1. Acheter un code</h2>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">
                        L'abonnement s'active via un code (ex: PREM-XXXX). Pour l'obtenir, effectuez un paiement manuel via TMoney ou Flooz.
                    </p>
                    
                    <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        <span style="display: block; font-weight: 700; color: #a5b4fc; margin-bottom: 5px;">Tarifs :</span>
                        • Standard : <strong>3000 FCFA</strong><br>
                        • Premium : <strong>5000 FCFA</strong>
                    </div>

                    <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        <span style="display: block; font-weight: 700; color: #a5b4fc; margin-bottom: 5px;">Numéros de paiement :</span>
                        <div style="margin-bottom: 10px;">🟢 <strong>FLOOZ :</strong> +228 79 91 62 97 (Nom: <i>Kodzo Matthias</i>)</div>
                        <div>🟡 <strong>TMoney / YAS :</strong> +228 92 27 73 63 (Nom: <i>KODZO MATTHIAS</i>)</div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 10px;">
                            Après le paiement, envoyez la capture d'écran par WhatsApp pour recevoir votre code :
                        </p>
                        <a href="https://wa.me/22879916297?text=Bonjour,%20je%20viens%20d'effectuer%20un%20paiement%20sur%20BUILD.CV.%20Voici%20ma%20capture%20d'écran%20:" target="_blank" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #25D366; color: white; width: 100%;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.066.381-.057c.106-.123.449-.531.567-.717.118-.186.231-.151.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                            Envoyer la capture sur WhatsApp
                        </a>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 10px; text-align: center;">
                            *L'activation prend généralement moins de 10 minutes après vérification.
                        </p>
                    </div>
                </div>

                <!-- Formulaire d'Activation -->
                <div class="redeem-card">
                    <div class="redeem-icon">🔑</div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">2. Activer un code</h2>
                    <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 0.95rem;">
                        Vous avez reçu votre code d'abonnement ? Entrez-le ci-dessous.
                    </p>
                    <form id="redeemForm">
                        <?= csrfField() ?>
                        <div style="margin-bottom:20px;">
                            <input type="text" id="premiumCode" name="code" class="redeem-input" placeholder="PREM-XXXX-XXXX" required autofocus autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;font-size:1.1rem;padding:12px;">Activer mon abonnement</button>
                    </form>
                    <div id="redeemMessage"></div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}

document.getElementById('redeemForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    const msgDiv = document.getElementById('redeemMessage');
    const code = document.getElementById('premiumCode').value;
    const csrfStr = document.querySelector('input[name="csrf_token"]');
    
    // Quick frontend validation format
    if (!code || code.length < 5) {
        showMessage('Veuillez entrer un code valide.', 'error');
        return;
    }

    try {
        btn.disabled = true;
        btn.innerHTML = 'Validation...';
        
        const response = await fetch('<?= APP_URL ?>/api/redeem-code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrfStr.value
            },
            body: JSON.stringify({ code: code, csrf_token: csrfStr.value })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            // Reload page or redirect after 2s
            setTimeout(() => {
                window.location.href = '<?= APP_URL ?>/dashboard/';
            }, 2000);
        } else {
            showMessage(data.error || 'Erreur lors de la validation.', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Activer mon abonnement';
        }
    } catch (err) {
        showMessage('Erreur serveur de validation.', 'error');
        btn.disabled = false;
        btn.innerHTML = 'Activer mon abonnement';
    }
});

function showMessage(msg, type) {
    const d = document.getElementById('redeemMessage');
    d.textContent = msg;
    d.className = type === 'error' ? 'msg-error' : 'msg-success';
    d.style.display = 'block';
}
</script>
</body>
</html>
