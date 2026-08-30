
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slider-item');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    
    if (!slides.length || !nextBtn || !prevBtn) return;
    
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('hidden');
                slide.classList.add('active');
            } else {
                slide.classList.add('hidden');
                slide.classList.remove('active');
            }
        });
    }

    nextBtn.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    });

    prevBtn.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    });

    const autoSlideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 6000);

    const stopAutoSlide = () => clearInterval(autoSlideInterval);
    nextBtn.addEventListener('click', stopAutoSlide, { once: true });
    prevBtn.addEventListener('click', stopAutoSlide, { once: true });
});
