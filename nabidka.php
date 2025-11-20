<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$jsonPath = __DIR__ . "/produkty.json";
if (!file_exists($jsonPath)) die("Chybí soubor produkty.json");
$produkty = json_decode(file_get_contents($jsonPath), true);
if (!is_array($produkty)) die("Chybný formát JSON");

if (!isset($_SESSION["kosik"])) $_SESSION["kosik"] = [];
if (isset($_POST["ajax"])) {
    error_reporting(0);
    ini_set("display_errors", 0);
    header("Content-Type: application/json");

    switch ($_POST["ajax"]) {
        case "add":
            $id = intval($_POST["id"]);
            if (isset($produkty[$id])) {
                if (!isset($_SESSION["kosik"][$id])) {
                    $_SESSION["kosik"][$id] = [
                        "produkt" => $produkty[$id],
                        "mnozstvi" => 1
                    ];
                } else {
                    $_SESSION["kosik"][$id]["mnozstvi"]++;
                }
            }
            break;

        case "remove":
            $id = intval($_POST["id"]);
            unset($_SESSION["kosik"][$id]);
            break;

        case "update":
            $id = intval($_POST["id"]);
            $m = max(1, intval($_POST["mnozstvi"]));
            if (isset($_SESSION["kosik"][$id])) {
                $_SESSION["kosik"][$id]["mnozstvi"] = $m;
            }
            break;

        case "get":
            break;
    }

    $celkovyPocet = array_sum(array_column($_SESSION["kosik"], "mnozstvi"));
    $celkovaCena = array_sum(array_map(fn($p) => $p["produkt"]["cena"] * $p["mnozstvi"], $_SESSION["kosik"]));

    echo json_encode([
        "pocet" => $celkovyPocet,
        "polozky" => $_SESSION["kosik"],
        "celkem" => $celkovaCena
    ]);
    exit;
}

$page = 'nabidka';
include "includes/header.php";
?>

<div class="eshop-container">
    <h1>Naše nabídka šejků</h1>

    <div class="filter-bar">
        <select id="filter">
            <option value="vse">Všechny kategorie</option>
            <?php
            $kategorie = array_unique(array_column($produkty, "kategorie"));
            foreach ($kategorie as $kat) {
                echo "<option value='" . htmlspecialchars($kat, ENT_QUOTES) . "'>" . htmlspecialchars($kat) . "</option>";
            }
            ?>
        </select>
        <?php
        $pocetKs = array_sum(array_column($_SESSION["kosik"], "mnozstvi"));
        ?>
        <button id="kosik-btn">🛒 <span class="kosik_text">Zobrazit košík</span> (<?= $pocetKs ?>)</button>
    </div>

    <div class="produkty-grid">
        <?php foreach ($produkty as $index => $produkt): ?>
            <div class="produkt-card"
                 data-kategorie="<?= htmlspecialchars($produkt["kategorie"] ?? "ostatni") ?>"
                 data-detaily="<?= htmlspecialchars($produkt["detaily"] ?? "", ENT_QUOTES) ?>">

                <div class="produkt-img-wrapper" style="position:relative;">
                    <?php if (!empty($produkt["obrazek"])): ?>
                        <img class="produkt-img" src="<?= htmlspecialchars($produkt["obrazek"]) ?>" alt="<?= htmlspecialchars($produkt["nazev"]) ?>">
                    <?php endif; ?>
                    <form class="add-form">
                        <input type="hidden" name="id" value="<?= intval($index) ?>">
                        <button type="submit" class="add-btn">+</button>
                    </form>
                </div>

                <h3><?= htmlspecialchars($produkt["nazev"]) ?></h3>
                <p class="popis"><?= htmlspecialchars($produkt["popis"]) ?></p>
                <p class="cena"><?= number_format(floatval($produkt["cena"]), 2) ?> Kč</p>
                <button class="detail-btn">Zobrazit detaily</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="kosik-modal" class="modal">
    <div class="modal-content">
        <h2>Váš košík</h2>
        <table class="kosik-table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Množství</th>
                    <th>Cena</th>
                    <th>Odebrat</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div class="celkem">Celkem: <strong>0 Kč</strong></div>

        <div class="modal-actions">
            <a href="objednavka.php" class="order-btn">Dokončit objednávku →</a>
            <button id="close-modal" class="close-btn">Zavřít</button>
        </div>
    </div>
</div>

<div id="detail-modal" class="modal">
    <div class="modal-content">
        <h2 id="detail-title"></h2>
        <img id="detail-img" src="" alt="" style="max-width:300px;display:block;margin:15px auto;border-radius:10px;">
        <p id="detail-text"></p>
        <button id="close-detail" class="close-btn">Zavřít</button>
    </div>
</div>



<?php include "includes/footer.php"; ?>
