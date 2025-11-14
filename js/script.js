// JSON data pro slidy
const slidesData = [
  {
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


// =============================
//  SLIDESHOW – bezpečná verze
// =============================
const slidesContainer = document.querySelector('.slides');
const indicatorsContainer = document.querySelector('.indicators');

if (slidesContainer && indicatorsContainer) {

  slidesData.forEach((slide, i) => {

    // Slide
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

    // Indikátor
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

  function showSlide(index){
    slides.forEach((s,i)=>{
      s.classList.toggle('active', i === index);
      inds[i].classList.toggle('active', i === index);
    });
    current = index;
  }

  function startSlideShow(){
    slideTimer = setInterval(()=>{
      let next = (current + 1) % slides.length;
      showSlide(next);
    }, intervalTime);
  }

  inds.forEach(ind=>{
    ind.addEventListener('click', ()=>{
      clearInterval(slideTimer);
      showSlide(parseInt(ind.dataset.index));
      startSlideShow();
    });
  });

  startSlideShow();
}


// =============================
// FAQ
// =============================
document.querySelectorAll('.faq-item').forEach(item => {
  item.addEventListener('click', () => {
    document.querySelectorAll('.faq-item').forEach(el => {
      if (el !== item) el.classList.remove('active');
    });
    item.classList.toggle('active');
    item.classList.toggle('open');
  });
});


// =============================
// Scroll reveal
// =============================
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
        console.error("❌ menuToggle nebo navMenu nebyly nalezeny v DOM.");
        return;
    }

    // toggle hamburger menu
    menuToggle.addEventListener("click", () => {
        menuToggle.classList.toggle("active");
        navMenu.classList.toggle("open");
        document.body.classList.toggle("lock-scroll"); // zakáže scroll při otevření
    });

    // zavření při kliknutí na link
    navMenu.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            menuToggle.classList.remove("active");
            navMenu.classList.remove("open");
            document.body.classList.remove("lock-scroll");
        });
    });
});

