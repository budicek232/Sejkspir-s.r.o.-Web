<?php $page = 'kontakt'; include "includes/header.php"; ?>


<div class="contact-page">
    <div class="contact-container">
        <section class="contact-info">
            <h1>Kontakt</h1>
            <p class="lead">Najdeš nás v srdci Obchodní akademie v Domažlicích. Stav se osobně, nebo nám napiš či zavolej – rádi ti pomůžeme.</p>

            <div class="info-block">
                <p><strong>Adresa:</strong><br>Šejkspír s.r.o.<br>Erbenova 184, 344 01  Domažlice</p>
                <p><strong>Telefon:</strong><br>+420 123 456 789</p>
                <p><strong>E-mail:</strong><br><a href="mailto:sejkspir123@gmail.com">mailto:sejkspir123@gmail.com</a></p>
                <p><strong>Otevírací doba:</strong><br>Po–Pá 9:00–18:00<br>So 10:00–14:00</p>
            </div>

            <p class="privacy-note">Vaše osobní údaje chráníme a používáme pouze pro vyřízení vašeho dotazu.</p>
        </section>

        <aside class="contact-map">
            <h2>Kde nás najdete</h2>
            <div class="map-wrap">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    scrolling="no"
                    style="border:0;"
                    allowfullscreen
                    src="https://www.openstreetmap.org/export/embed.html?bbox=12.9115%2C49.4395%2C12.9315%2C49.4495&layer=mapnik&marker=49.4446593%2C12.9215060">
                </iframe>
                <p>
                    <a href="https://www.openstreetmap.org/?mlat=49.4446593&mlon=12.9215060#map=16/49.4446593/12.9215060"
                    target="_blank" rel="noopener">
                    Zobrazit větší mapu
                    </a>
                </p>
            </div>

        </aside>
    </div>
</div>
<section class="contact">
    <h2>Kontaktní formulář</h2>
    <form>
      <input type="text" name="name" placeholder="Vaše jméno" required>
      <input type="email" name="email" placeholder="Váš e-mail" required>
      <textarea name="message" placeholder="Vaše zpráva" rows="5" required></textarea>
      <button class="submit" type="submit">Odeslat</button>
    </form>
  </section>
<?php include "includes/footer.php" ?>