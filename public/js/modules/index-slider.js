// Simple Slider Logic for Index Hero Section
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slider-item');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    
    if (!slides.length || !nextBtn || !prevBtn) return;
    
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.add('hidden'));
        slides[index].classList.remove('hidden');
        // Reset animation
        const content = slides[index].querySelector('.animate-fade-in-up');
        if (content) {
            content.style.animation = 'none';
            content.offsetHeight; /* trigger reflow */
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

    // Auto slide
    const autoSlideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 6000);
    
    // Optional: Stop auto-slide on user interaction
    const stopAutoSlide = () => clearInterval(autoSlideInterval);
    nextBtn.addEventListener('click', stopAutoSlide, { once: true });
    prevBtn.addEventListener('click', stopAutoSlide, { once: true });
});
