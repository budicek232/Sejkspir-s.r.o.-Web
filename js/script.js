// JSON data pro slidy
const slidesData = [
  {
    image: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1600&q=80',
    title: 'Signature Shaky',
    text: 'Zaručujeme pečlivě připravené chutě a bohaté složení.',
    buttonText: 'Navštiv naši nabídku',
    buttonLink: '#'
  },
  {
    image: 'images/ChatGPT Image Nov 11, 2025, 10_21_20 AM.png',
    title: 'Čokoláda, banán, jahoda',
    text: 'Milovníci klasiky zde rozhodně najdou to svoje.',
    buttonText: 'Navštiv naši nabídku',
    buttonLink: '#'
  },
  {
    image: 'https://images.unsplash.com/photo-1576402187878-974f70cb3f5f?auto=format&fit=crop&w=1600&q=80',
    title: 'Zdravé chutné nápoje',
    text: 'Objev naší širokou nabídu klasických i speciálních chutí.',
    buttonText: 'Navštiv naši nabídku',
    buttonLink: '#'
  }
];

const slidesContainer = document.querySelector('.slides');
const indicatorsContainer = document.querySelector('.indicators');

// generování slide a indikátorů
slidesData.forEach((slide, i) => {
  // slide
  const div = document.createElement('div');
  div.classList.add('slide');
  if(i === 0) div.classList.add('active');
  div.style.backgroundImage = `url('${slide.image}')`;
  div.innerHTML = `
    <h2>${slide.title}</h2>
    <p>${slide.text}</p>
    <a href="${slide.buttonLink}" class="button">${slide.buttonText}</a>
  `;
  slidesContainer.appendChild(div);

  // indikátor
  const ind = document.createElement('span');
  ind.classList.add('ind');
  if(i === 0) ind.classList.add('active');
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
    s.classList.toggle('active', i===index);
    inds[i].classList.toggle('active', i===index);
  });
  current = index;
}

function startSlideShow(){
  slideTimer = setInterval(()=>{
    let next = (current+1)%slides.length;
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

// start slideshow
startSlideShow();