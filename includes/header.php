<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Šejkspír</title>
      <link rel="stylesheet" href="css/style.css">
   </head>
   <body>
      <div class="header">
         <div class="header__logo-section">
            <a href="index.php"><img src="images/logo_bez_pozadi_beztextu_BYGURT.jpg" alt=""></a>
            <a href="index.php"><h2>ŠEJKSPÍR</h2></a>
         </div>
         <div class="header__links-section">
            <a class="<?= ($page === 'home') ? 'active' : '' ?>" href="index.php">Menu</a>
            <a class="<?= ($page === 'onas') ? 'active' : '' ?>" href="onas.php">O nás</a>
            <a class="<?= ($page === 'nabidka') ? 'active' : '' ?>" href="nabidka.php">Šejky</a>
            <a class="<?= ($page === 'kontakt') ? 'active' : '' ?>" href="kontakt.php">Kontakt</a>
         </div>
      </div>