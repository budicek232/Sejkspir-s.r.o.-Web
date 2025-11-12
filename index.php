<?php 
$page = 'home'; 
include "includes/header.php";
?>

<main>

  <!-- Hero sekce se sliderem -->
  <section class="hero">
    <div class="slides"></div>
    <div class="indicators"></div>
  </section>

  <!-- Produkty -->
  <section class="products">
    <h2>Naše základní produkty</h2>
    <div class="product-list">
      <a href="nabidka.php" class="product">
        <img src="images/Gemini_Generated_Image_n1nt4in1nt4in1nt.png" alt="Čokoládový Shake">
        <span>Čokoládový Shake</span>
      </a>
      <a href="nabidka.php" class="product">
        <img src="images/Gemini_Generated_Image_5sfhj5sfhj5sfhj5.png" alt="Banánový Shake">
        <span>Banánový Shake</span>
      </a>
      <a href="nabidka.php" class="product">
        <img src="images/Gemini_Generated_Image_68603j68603j6860.png" alt="Jahodový Shake">
        <span>Jahodový Shake</span>
      </a>
    </div>
  </section>

  <!-- Proč si vybrat Šejkspír -->
  <section class="why-us">
    <h2>Proč si vybrat Šejkspír</h2>
    <div class="reasons">
      <div class="reason">
        <img src="images/Gemini_Generated_Image_f1fe9mf1fe9mf1fe.png" alt="100% přírodní">
        <h3>100% přírodní</h3>
        <p>Bez umělých přísad, konzervantů nebo přidaného cukru.</p>
      </div>
      <div class="reason">
        <img src="images/Gemini_Generated_Image_hwidiihwidiihwid.png" alt="Lokální výroba">
        <h3>Lokální výroba</h3>
        <p>Naše šejky vznikají z kvalitních surovin od místních dodavatelů.</p>
      </div>
      <div class="reason">
        <img src="images/Gemini_Generated_Image_w342hww342hww342.png" alt="Udržitelné balení">
        <h3>Udržitelné balení</h3>
        <p>Každý produkt balíme s ohledem na planetu.</p>
      </div>
    </div>
  </section>

  <!-- Co je Šejkspír -->
  <section class="about">
    <h2>Co je Šejkspír</h2>
    <p>
      Šejkspír je značka zaměřená na kvalitní shake produkty, které spojují chuť, zdraví
      a moderní životní styl. Každý produkt je navržen s důrazem na přírodní složky a udržitelnost.
    </p>
  </section>

  <!-- Složení -->
  <section class="composition">
    <h2>Složení šejků</h2>
    <p>
      Naše šejky jsou tvořeny výhradně z <strong>přírodních ingrediencí</strong> — 
      <strong>protein z hrachu</strong>, <strong>ovesné mléko</strong>, ovoce a rostlinné oleje. 
      Bez umělých barviv, konzervantů nebo rafinovaného cukru.
    </p>
  </section>

  <!-- FAQ -->
  <section class="faq">
    <h2>FAQ</h2>
    <div class="faq-item">
      <h3>Jak často mohu šejk pít?</h3>
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

  <!-- CTA Kontakt -->
  <section class="contact-cta">
    <h2>Máte otázky?</h2>
    <p>Kontaktujte nás a my vám rádi poradíme s výběrem.</p>
    <a href="kontakt.php" class="btn-primary">Přejít na kontakt</a>
  </section>

</main>

<?php include "includes/footer.php"; ?>

