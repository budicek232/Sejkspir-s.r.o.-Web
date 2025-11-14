<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

/* ==============================
   KONFIGURACE A DATA PRODUKTŮ
============================== */
$jsonPath = __DIR__ . "/produkty.json";
if (!file_exists($jsonPath)) die("Chybí soubor produkty.json");
$produkty = json_decode(file_get_contents($jsonPath), true);
if (!is_array($produkty)) die("Chybný formát JSON");

if (!isset($_SESSION["kosik"])) $_SESSION["kosik"] = [];

/* ==============================
   AJAX API pro košík (vrací JSON)
============================== */
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

    // Součet všech kusů (ne jen druhů)
    $celkovyPocet = array_sum(array_column($_SESSION["kosik"], "mnozstvi"));
    $celkovaCena = array_sum(array_map(fn($p) => $p["produkt"]["cena"] * $p["mnozstvi"], $_SESSION["kosik"]));

    echo json_encode([
        "pocet" => $celkovyPocet,
        "polozky" => $_SESSION["kosik"],
        "celkem" => $celkovaCena
    ]);
    exit;
}

/* ==============================
   STRÁNKA - NABÍDKA
============================== */
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
        <button id="kosik-btn">🛒 Zobrazit košík (<?= $pocetKs ?>)</button>
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

<!-- ================= MODÁLNÍ KOŠÍK ================= -->
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

<!-- ================= MODÁLNÍ DETAIL PRODUKTU ================= -->
<div id="detail-modal" class="modal">
    <div class="modal-content">
        <h2 id="detail-title"></h2>
        <img id="detail-img" src="" alt="" style="max-width:300px;display:block;margin:15px auto;border-radius:10px;">
        <p id="detail-text"></p>
        <button id="close-detail" class="close-btn">Zavřít</button>
    </div>
</div>

<script>
/* ===== AJAX Helper ===== */
async function updateKosik(data) {
    const res = await fetch(window.location.pathname, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams(data)
    });
    return res.json();
}

/* ===== Inicializace proměnných ===== */
const kosikBtn = document.getElementById('kosik-btn');
const kosikModal = document.getElementById('kosik-modal');
const kosikBody = kosikModal.querySelector('.kosik-table tbody');
const celkemEl = document.querySelector('.celkem strong');

/* ===== Filtrace kategorií ===== */
document.getElementById('filter').addEventListener('change', function() {
    const kat = this.value;
    document.querySelectorAll('.produkt-card').forEach(card => {
        card.style.display = (kat === 'vse' || card.dataset.kategorie === kat) ? 'flex' : 'none';
    });
});

/* ===== Zobrazení / zavření modalu ===== */
kosikBtn.addEventListener('click', async () => {
    kosikModal.style.display = 'flex';
    const data = await updateKosik({ajax: 'get'});
    refreshKosik(data);
});
document.getElementById('close-modal').addEventListener('click', () => kosikModal.style.display = 'none');

/* ===== Přidání do košíku ===== */
document.querySelectorAll('.add-form').forEach(form => {
    form.addEventListener('submit', async e => {
        e.preventDefault();
        const id = form.querySelector('[name="id"]').value;
        const data = await updateKosik({ajax: 'add', id});
        refreshKosik(data);
        kosikBtn.classList.add('animate');
        setTimeout(() => kosikBtn.classList.remove('animate'), 400);
    });
});

/* ===== Odebrání z košíku ===== */
document.addEventListener('click', async e => {
    if (e.target.classList.contains('remove-btn')) {
        e.preventDefault();
        const id = e.target.value;
        const data = await updateKosik({ajax: 'remove', id});
        refreshKosik(data);
    }
});

/* ===== Změna množství ===== */
document.addEventListener('input', async e => {
    if (e.target.matches('.kosik-table input[type="number"]')) {
        const id = e.target.name.match(/\d+/)[0];
        const mnozstvi = e.target.value;
        const data = await updateKosik({ajax: 'update', id, mnozstvi});
        refreshKosik(data);
    }
});

/* ===== Překreslení obsahu košíku ===== */
function refreshKosik(data) {
    kosikBody.innerHTML = '';
    for (const [id, p] of Object.entries(data.polozky)) {
        const cena = p.produkt.cena * p.mnozstvi;
        const obrazek = p.produkt.obrazek ? `<img src="${p.produkt.obrazek}" alt="" class="kosik-thumb">` : '';
        kosikBody.innerHTML += `
          <tr>
            <td>${obrazek}<span>${p.produkt.nazev}</span></td>
            <td><input type="number" name="mnozstvi[${id}]" value="${p.mnozstvi}" min="1"></td>
            <td>${cena.toLocaleString('cs-CZ', {minimumFractionDigits: 2})} Kč</td>
            <td><button class="remove-btn" value="${id}">✕</button></td>
          </tr>`;
    }
    celkemEl.textContent = data.celkem.toLocaleString('cs-CZ', {minimumFractionDigits: 2}) + ' Kč';
    kosikBtn.innerHTML = `🛒 Zobrazit košík (${data.pocet})`;
}

/* ===== Modal - Detaily produktu ===== */
const detailModal = document.getElementById('detail-modal');
const detailTitle = document.getElementById('detail-title');
const detailText = document.getElementById('detail-text');
const detailImg = document.getElementById('detail-img');

document.querySelectorAll('.detail-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const card = e.target.closest('.produkt-card');
        detailTitle.textContent = card.querySelector('h3').textContent;
        detailText.textContent = card.dataset.detaily || "Bez detailů.";
        const imgEl = card.querySelector('.produkt-img');
        detailImg.src = imgEl ? imgEl.src : '';
        detailImg.style.display = imgEl ? 'block' : 'none';
        detailModal.style.display = 'flex';
    });
});
document.getElementById('close-detail').addEventListener('click', () => detailModal.style.display = 'none');
</script>

<style>
#kosik-btn.animate {
  animation: bounce 0.4s ease;
}
@keyframes bounce {
  0% { transform: scale(1); }
  30% { transform: scale(1.15); }
  60% { transform: scale(0.95); }
  100% { transform: scale(1); }
}

/* Mini obrázek v košíku */
.kosik-thumb {
  width: 42px;
  height: 42px;
  border-radius: 8px;
  object-fit: cover;
  margin-right: 10px;
  vertical-align: middle;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.kosik-table td span {
  vertical-align: middle;
}
</style>

<?php include "includes/footer.php"; ?>
