<?php include "includes/header.php"; $page = 'home';
 ?>



<main>
  <!-- Hero sekce -->
<section class="hero">
  <div class="slides"></div>
  <div class="indicators"></div>
</section>



  <!-- Produkty -->
  <section class="products">
    <h2>Naše základní produkty</h2>
    <div class="product-list">
      <a href="#" class="product">
        <img src="images\Gemini_Generated_Image_n1nt4in1nt4in1nt.png" alt="Produkt 1">
        <span>Čokoládový Shake</span>
      </a>
      <a href="#" class="product">
        <img src="images\Gemini_Generated_Image_5sfhj5sfhj5sfhj5.png" alt="Produkt 2">
        <span>Banánový Shake</span>
      </a>
      <a href="#" class="product">
        <img src="images\Gemini_Generated_Image_68603j68603j6860.png" alt="Produkt 3">
        <span>Jahodový Shake</span>
      </a>
    </div>
  </section>

  <!-- Co je Sejkspir -->
  <section class="about">
    <h2>Co je Sejkspir</h2>
    <p>
      Sejkspir je unikátní značka zaměřená na kvalitní shaku produkty, které spojují chuť, zdraví
      a moderní životní styl. Každý produkt je navržen s důrazem na přírodní složky a udržitelnost.
    </p>
  </section>

  <!-- Složení -->
  <section class="composition">
    <h2>Složení shaku</h2>
    <p>
      Naše shaku jsou tvořeny výhradně z přírodních ingrediencí — protein z hrachu, ovesné mléko,
      přirozené sladidla, ovoce a rostlinné oleje. Bez umělých barviv, konzervantů nebo rafinovaného cukru.
    </p>
  </section>

  <!-- FAQ -->
  <section class="faq">
    <h2>FAQ</h2>
    <div class="faq-item">
      <h3>Jak často mohu shaku pít?</h3>
      <p>Ideálně 1–2× denně podle potřeby a životního stylu.</p>
    </div>
    <div class="faq-item">
      <h3>Je produkt vhodný pro vegany?</h3>
      <p>Ano, všechny naše produkty jsou 100% rostlinné.</p>
    </div>
    <div class="faq-item">
      <h3>Jak dlouho vydrží po otevření?</h3>
      <p>Po otevření doporučujeme spotřebovat do 48 hodin.</p>
    </div>
  </section>

  <!-- Kontaktní formulář -->
  <section class="contact">
    <h2>Kontaktní formulář</h2>
    <form>
      <input type="text" name="name" placeholder="Vaše jméno" required>
      <input type="email" name="email" placeholder="Váš e-mail" required>
      <textarea name="message" placeholder="Vaše zpráva" rows="5" required></textarea>
      <button type="submit">Odeslat</button>
    </form>
  </section>
</main>
<?php include "includes/footer.php" ?>