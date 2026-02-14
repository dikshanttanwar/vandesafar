// Footer Accordion (Mobile Only)
const footerHeaders = document.querySelectorAll('.links-col h4');

footerHeaders.forEach(header => {
    header.addEventListener('click', () => {
        if (window.innerWidth <= 768) { // Only works on mobile
            const list = header.nextElementSibling; // The <ul>
            list.style.display = (list.style.display === 'block') ? 'none' : 'block';
            header.classList.toggle('open'); // For rotating arrows if you add them
        }
    });
});