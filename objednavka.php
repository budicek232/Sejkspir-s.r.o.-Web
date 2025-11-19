<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

if (!isset($_SESSION["kosik"]) || empty($_SESSION["kosik"])) {
    header("Location: nabidka.php");
    exit;
}


$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jmeno = trim($_POST["jmeno"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefon = trim($_POST["telefon"] ?? "");
    $adresa = trim($_POST["adresa"] ?? "");
    $poznamka = trim($_POST["poznamka"] ?? "");

    if ($jmeno && $email && $telefon && $adresa) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $to = "sejkspir123@gmail.com";
            $subject = "Nová objednávka – Šejkspír";

            $body = "NOVÁ OBJEDNÁVKA\n\n";
            $body .= "Jméno: $jmeno\n";
            $body .= "Email: $email\n";
            $body .= "Telefon: $telefon\n";
            $body .= "Adresa: $adresa\n\n";
            $body .= "Poznámka:\n$poznamka\n\n";
            $body .= "===========================\n";
            $body .= "OBSAH OBJEDNÁVKY:\n\n";

            foreach ($_SESSION["kosik"] as $p) {
                $body .= "- {$p["produkt"]["nazev"]} ({$p["mnozstvi"]} ks) – "
                       . ($p["produkt"]["cena"] * $p["mnozstvi"]) . " Kč\n";
            }

            $body .= "\n===========================\n";
            $body .= "Celková cena: " .
                     array_sum(array_map(fn($p)=>$p["produkt"]["cena"] * $p["mnozstvi"], $_SESSION["kosik"])) .
                     " Kč\n";

            $headers = "From: $jmeno <$email>\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            if (mail($to, $subject, $body, $headers)) {
                $success = true;
                $_SESSION["kosik"] = [];
            } else {
                $error = "Objednávku se nepodařilo odeslat. Zkus to prosím později.";
            }

        } else {
            $error = "Zadej platný e-mail.";
        }
    } else {
        $error = "Vyplň prosím všechna povinná pole.";
    }
}

include "includes/header.php";
?>

<div class="order-page">

    <?php if ($success): ?>
        <div class="order-success">
            <h2> Objednávka byla úspěšně odeslána!</h2>
            <p>Ozveme se ti co nejdříve.</p>
            <a href="nabidka.php" class="btn-primary">Zpět do nabídky</a>
        </div>
    <?php else: ?>

    <h1>Dokončení objednávky</h1>

    <div class="order-wrapper">

        <div class="order-form">
            <h2>Kontaktní údaje</h2>

            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <form method="post">

                <label>Jméno a příjmení *</label>
                <input type="text" name="jmeno" required>

                <label>E-mail *</label>
                <input type="email" name="email" required>

                <label>Telefon *</label>
                <input type="text" name="telefon" required>

                <label>Adresa doručení *</label>
                <textarea name="adresa" rows="2" required></textarea>

                <label>Poznámka (nepovinné)</label>
                <textarea name="poznamka" rows="3"></textarea>

                <button class="btn-primary" type="submit">Odeslat objednávku</button>

            </form>
        </div>

        <div class="order-summary">
            <h2>Souhrn objednávky</h2>

            <?php foreach ($_SESSION["kosik"] as $p): ?>
            <div class="summary-item">
                <img src="<?= $p["produkt"]["obrazek"] ?>" alt="">
                <div>
                    <strong><?= $p["produkt"]["nazev"] ?></strong><br>
                    <?= $p["mnozstvi"] ?>×  
                    <?= number_format($p["produkt"]["cena"], 2) ?> Kč
                </div>
                <span class="item-total">
                    <?= number_format($p["produkt"]["cena"] * $p["mnozstvi"], 2) ?> Kč
                </span>
            </div>
            <?php endforeach; ?>

            <hr>

            <div class="summary-total">
                Celkem:
                <strong>
                    <?= number_format(array_sum(array_map(fn($p)=>$p["produkt"]["cena"] * $p["mnozstvi"], $_SESSION["kosik"])), 2) ?> Kč
                </strong>
            </div>

        </div>
    </div>

    <?php endif; ?>

</div>



<?php include "includes/footer.php"; ?>
