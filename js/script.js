const slidesData = [{
      image: 'images/ChatGPT Image Nov 12, 2025, 09_11_04 AM.png',
      title: 'Signature Shaky',
      text: 'Zaručujeme pečlivě připravené chutě a bohaté složení.',
      buttonText: 'Navštiv naši nabídku',
      buttonLink: '#',
      slogan: 'Ať chutná či nechutná, toť otázka',
   },
   {
      image: 'images/ChatGPT Image Nov 11, 2025, 10_21_20 AM.png',
      title: 'Čokoláda, banán, jahoda',
      text: 'Milovníci klasiky zde rozhodně najdou to svoje.',
      buttonText: 'Navštiv naši nabídku',
      buttonLink: '#',
      slogan: 'Ať chutná či nechutná, toť otázka'
   },
   {
      image: 'images/ChatGPT Image Nov 12, 2025, 09_12_56 AM.png',
      title: 'Zdravé chutné nápoje',
      text: 'Objev naší širokou nabídu klasických i speciálních chutí.',
      buttonText: 'Navštiv naši nabídku',
      buttonLink: '#',
      slogan: 'Ať chutná či nechutná, toť otázka'
   }
];

const slidesContainer = document.querySelector('.slides');
const indicatorsContainer = document.querySelector('.indicators');

if (slidesContainer && indicatorsContainer) {

   slidesData.forEach((slide, i) => {

      const div = document.createElement('div');
      div.classList.add('slide');
      if (i === 0) div.classList.add('active');
      div.style.backgroundImage = `url('${slide.image}')`;
      div.innerHTML = `
      <h2>${slide.title}</h2>
      <p>${slide.text}</p>
      <a href="nabidka.php" class="button">${slide.buttonText}</a>
      <h3 class="slogan_text">${slide.slogan}</h3>
    `;
      slidesContainer.appendChild(div);

      const ind = document.createElement('span');
      ind.classList.add('ind');
      if (i === 0) ind.classList.add('active');
      ind.dataset.index = i;
      indicatorsContainer.appendChild(ind);
   });

   const slides = document.querySelectorAll('.slide');
   const inds = document.querySelectorAll('.ind');
   let current = 0;
   const intervalTime = 5000;
   let slideTimer;

   function showSlide(index) {
      slides.forEach((s, i) => {
         s.classList.toggle('active', i === index);
         inds[i].classList.toggle('active', i === index);
      });
      current = index;
   }

   function startSlideShow() {
      slideTimer = setInterval(() => {
         let next = (current + 1) % slides.length;
         showSlide(next);
      }, intervalTime);
   }

   inds.forEach(ind => {
      ind.addEventListener('click', () => {
         clearInterval(slideTimer);
         showSlide(parseInt(ind.dataset.index));
         startSlideShow();
      });
   });

   startSlideShow();
}


document.querySelectorAll('.faq-item').forEach(item => {
   item.addEventListener('click', () => {
      document.querySelectorAll('.faq-item').forEach(el => {
         if (el !== item) el.classList.remove('active');
      });
      item.classList.toggle('active');
      item.classList.toggle('open');
   });
});


const revealElements = document.querySelectorAll('.scroll-reveal, .reason');

const revealOnScroll = () => {
   const triggerBottom = window.innerHeight * 0.85;
   revealElements.forEach(el => {
      const boxTop = el.getBoundingClientRect().top;
      if (boxTop < triggerBottom) {
         el.classList.add('visible');
      }
   });
};

window.addEventListener('scroll', revealOnScroll);
revealOnScroll();


document.addEventListener("DOMContentLoaded", () => {

   const menuToggle = document.getElementById("menuToggle");
   const navMenu = document.getElementById("navMenu");

   if (!menuToggle || !navMenu) {
      console.error("menuToggle nebo navMenu nebyly nalezeny v DOM.");
      return;
   }


   menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      navMenu.classList.toggle("open");
      document.body.classList.toggle("lock-scroll");
   });

   navMenu.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => {
         menuToggle.classList.remove("active");
         navMenu.classList.remove("open");
         document.body.classList.remove("lock-scroll");
      });
   });
});

async function updateKosik(data) {
   const res = await fetch(window.location.pathname, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(data)
   });
   return res.json();
}


const kosikBtn = document.getElementById('kosik-btn');
const kosikModal = document.getElementById('kosik-modal');
const kosikBody = kosikModal.querySelector('.kosik-table tbody');
const celkemEl = document.querySelector('.celkem strong');

document.getElementById('filter').addEventListener('change', function () {
   const kat = this.value;
   document.querySelectorAll('.produkt-card').forEach(card => {
      card.style.display = (kat === 'vse' || card.dataset.kategorie === kat) ? 'flex' : 'none';
   });
});

kosikBtn.addEventListener('click', async () => {
   kosikModal.style.display = 'flex';
   const data = await updateKosik({
      ajax: 'get'
   });
   refreshKosik(data);
});
document.getElementById('close-modal').addEventListener('click', () => kosikModal.style.display = 'none');

document.querySelectorAll('.add-form').forEach(form => {
   form.addEventListener('submit', async e => {
      e.preventDefault();
      const id = form.querySelector('[name="id"]').value;
      const data = await updateKosik({
         ajax: 'add',
         id
      });
      refreshKosik(data);
      kosikBtn.classList.add('animate');
      setTimeout(() => kosikBtn.classList.remove('animate'), 400);
   });
});

document.addEventListener('click', async e => {
   if (e.target.classList.contains('remove-btn')) {
      e.preventDefault();
      const id = e.target.value;
      const data = await updateKosik({
         ajax: 'remove',
         id
      });
      refreshKosik(data);
   }
});

document.addEventListener('input', async e => {
   if (e.target.matches('.kosik-table input[type="number"]')) {
      const id = e.target.name.match(/\d+/)[0];
      const mnozstvi = e.target.value;
      const data = await updateKosik({
         ajax: 'update',
         id,
         mnozstvi
      });
      refreshKosik(data);
   }
});

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
   celkemEl.textContent = data.celkem.toLocaleString('cs-CZ', {
      minimumFractionDigits: 2
   }) + ' Kč';
   kosikBtn.innerHTML = `🛒 Zobrazit košík (${data.pocet})`;
}

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