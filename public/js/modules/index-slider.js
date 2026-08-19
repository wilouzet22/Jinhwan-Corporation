
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slider-item');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    
    if (!slides.length || !nextBtn || !prevBtn) return;
    
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.add('hidden'));
        slides[index].classList.remove('hidden');
        
        const content = slides[index].querySelector('.animate-fade-in-up');
        if (content) {
            content.style.animation = 'none';
            content.offsetHeight; 
            content.style.animation = null; 
        }
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
