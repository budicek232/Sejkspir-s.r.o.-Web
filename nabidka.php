<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$page = "nabidka";
include "includes/header.php";

$jsonPath = __DIR__ . "/produkty.json";
if (!file_exists($jsonPath)) {
    die("produkty.json nenalezen — zkontroluj cestu.");
}
$produkty = json_decode(file_get_contents($jsonPath), true);
if (!is_array($produkty)) {
    die("Chyba: produkty.json není validní JSON nebo neobsahuje pole.");
}

if (!isset($_SESSION["kosik"])) {
    $_SESSION["kosik"] = [];
}

// --- HANDLERY ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["pridat"], $_POST["id"])) {
    $id = intval($_POST["id"]);
    if (isset($produkty[$id])) {
        if (!isset($_SESSION["kosik"][$id])) {
            $_SESSION["kosik"][$id] = [
                "produkt" => $produkty[$id],
                "mnozstvi" => 1,
            ];
        } else {
            $_SESSION["kosik"][$id]["mnozstvi"]++;
        }
        echo "<script>sessionStorage.setItem('added','true');location.href=location.pathname;</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove_id"])) {
    $rid = intval($_POST["remove_id"]);
    if (isset($_SESSION["kosik"][$rid])) {
        unset($_SESSION["kosik"][$rid]);
    }
    echo "<script>location.href=location.pathname;</script>";
    exit();
}

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["update_mnozstvi"]) &&
    isset($_POST["mnozstvi"]) &&
    is_array($_POST["mnozstvi"])
) {
    foreach ($_POST["mnozstvi"] as $id => $value) {
        $iid = intval($id);
        $m = max(1, intval($value));
        if (isset($_SESSION["kosik"][$iid])) {
            $_SESSION["kosik"][$iid]["mnozstvi"] = $m;
        }
    }
    echo "<script>location.href=location.pathname;</script>";
    exit();
}
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
        <button id="kosik-btn">🛒 Zobrazit košík (<?php echo count($_SESSION["kosik"]); ?>)</button>
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
                    <form method="post" class="add-form">
                        <input type="hidden" name="id" value="<?= intval($index) ?>">
                        <button type="submit" name="pridat" class="add-btn">+</button>
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

<!-- MODÁLNÍ OKNO - KOŠÍK -->
<div id="kosik-modal" class="modal">
    <div class="modal-content">
        <h2>Váš košík</h2>
        <?php if (!empty($_SESSION["kosik"])): ?>
            <form method="post">
                <table class="kosik-table">
                    <tr>
                        <th>Produkt</th>
                        <th>Množství</th>
                        <th>Cena</th>
                        <th>Odebrat</th>
                    </tr>
                    <?php
                    $celkem = 0;
                    foreach ($_SESSION["kosik"] as $id => $polozka):
                        $cena = floatval($polozka["produkt"]["cena"]) * intval($polozka["mnozstvi"]);
                        $celkem += $cena;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($polozka["produkt"]["nazev"]) ?></td>
                            <td><input type="number" name="mnozstvi[<?= intval($id) ?>]" value="<?= intval($polozka["mnozstvi"]) ?>" min="1"></td>
                            <td><?= number_format($cena, 2) ?> Kč</td>
                            <td><button type="submit" name="remove_id" value="<?= intval($id) ?>" class="remove-btn">✕</button></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="celkem">Celkem: <strong><?= number_format($celkem, 2) ?> Kč</strong></div>
                <button type="submit" name="update_mnozstvi" value="1" class="update-btn">Aktualizovat množství</button>
            </form>
        <?php else: ?>
            <p>Košík je prázdný.</p>
        <?php endif; ?>
        <button id="close-modal" class="close-btn">Zavřít</button>
    </div>
</div>

<!-- MODÁLNÍ OKNO - DETAIL PRODUKTU -->
<div id="detail-modal" class="modal">
    <div class="modal-content">
        <h2 id="detail-title"></h2>
        <p id="detail-text"></p>
        <button id="close-detail" class="close-btn">Zavřít</button>
    </div>
</div>

<script>
// Filtrace kategorií
document.getElementById('filter').addEventListener('change', function() {
    const kat = this.value;
    document.querySelectorAll('.produkt-card').forEach(card => {
        card.style.display = (kat === 'vse' || card.dataset.kategorie === kat) ? 'flex' : 'none';
    });
});

// Modal - košík
const kosikModal = document.getElementById('kosik-modal');
document.getElementById('kosik-btn').addEventListener('click', e => { e.preventDefault(); kosikModal.style.display = 'flex'; });
document.getElementById('close-modal').addEventListener('click', () => { kosikModal.style.display = 'none'; });

// Modal - detaily
const detailModal = document.getElementById('detail-modal');
const detailTitle = document.getElementById('detail-title');
const detailText = document.getElementById('detail-text');

document.querySelectorAll('.detail-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const card = e.target.closest('.produkt-card');
        detailTitle.textContent = card.querySelector('h3').textContent;
        detailText.textContent = card.dataset.detaily || "Bez detailů.";
        detailModal.style.display = 'flex';
    });
});
document.getElementById('close-detail').addEventListener('click', () => { detailModal.style.display = 'none'; });

// Notifikace po přidání
if (sessionStorage.getItem('added')) {
    alert('Produkt byl přidán do košíku 🛒');
    sessionStorage.removeItem('added');
}
</script>

<?php include "includes/footer.php"; ?>
