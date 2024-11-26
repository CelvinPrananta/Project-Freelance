$(document).ready(function() {
    // Untuk Slide Card Atas
    const slideCard = document.getElementById('slide-card-atas');
    
    if (slideCard) {
        let isDown = false;
        let startX;
        let startY;
        let scrollLeft;
        let scrollTop;

        slideCard.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - slideCard.offsetLeft;
            startY = e.pageY - slideCard.offsetTop;
            scrollLeft = slideCard.scrollLeft;
            scrollTop = slideCard.scrollTop;
            slideCard.style.cursor = 'grabbing';
        });

        slideCard.addEventListener('mouseleave', () => {
            isDown = false;
            slideCard.style.cursor = 'grab';
        });

        slideCard.addEventListener('mouseup', () => {
            isDown = false;
            slideCard.style.cursor = 'grab';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slideCard.offsetLeft;
            const y = e.pageY - slideCard.offsetTop;
            const walkX = (x - startX) * 1;
            const walkY = (y - startY) * 1;
            slideCard.scrollLeft = scrollLeft - walkX;
            slideCard.scrollTop = scrollTop - walkY;
        });

        const scrollLeftButton = document.getElementById('action-button--previous');
        const scrollRightButton = document.getElementById('action-button--next');

        if (scrollLeftButton && scrollRightButton) {
            scrollLeftButton.addEventListener('click', () => {
                slideCard.scrollBy({
                    top: 0,
                    left: -200,
                    behavior: 'smooth'
                });
            });

            scrollRightButton.addEventListener('click', () => {
                slideCard.scrollBy({
                    top: 0,
                    left: 200,
                    behavior: 'smooth'
                });
            });

            slideCard.addEventListener('scroll', (e) => {
                const position = slideCard.scrollLeft;
                if (position === 0) {
                    scrollLeftButton.disabled = true;
                } else {
                    scrollLeftButton.disabled = false;
                }

                if (
                    Math.round(position) ===
                    slideCard.scrollWidth -
                    slideCard.clientWidth
                ) {
                    scrollRightButton.disabled = true;
                } else {
                    scrollRightButton.disabled = false;
                }
            });
        }

        function slideContent(direction) {
            const scrollAmount = 300;

            if (direction === 'left') {
                slideCard.scrollLeft -= scrollAmount;
            } else if (direction === 'right') {
                slideCard.scrollLeft += scrollAmount;
            }
        }

        window.slideContent = slideContent;
    }

    // Untuk Slide Card Bawah
    const slideCard2 = document.getElementById('slide-card-bawah');
    
    if (slideCard2) {
        let isDown2 = false;
        let startX2;
        let startY2;
        let scrollLeft2;
        let scrollTop2;

        slideCard2.addEventListener('mousedown', (e) => {
            isDown2 = true;
            startX2 = e.pageX - slideCard2.offsetLeft;
            startY2 = e.pageY - slideCard2.offsetTop;
            scrollLeft2 = slideCard2.scrollLeft;
            scrollTop2 = slideCard2.scrollTop;
            slideCard2.style.cursor = 'grabbing';
        });

        slideCard2.addEventListener('mouseleave', () => {
            isDown2 = false;
            slideCard2.style.cursor = 'grab';
        });

        slideCard2.addEventListener('mouseup', () => {
            isDown2 = false;
            slideCard2.style.cursor = 'grab';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDown2) return;
            e.preventDefault();
            const x2 = e.pageX - slideCard2.offsetLeft;
            const y2 = e.pageY - slideCard2.offsetTop;
            const walkX2 = (x2 - startX2) * 1;
            const walkY2 = (y2 - startY2) * 1;
            slideCard2.scrollLeft = scrollLeft2 - walkX2;
            slideCard2.scrollTop = scrollTop2 - walkY2;
        });

        const scrollLeftButton2 = document.getElementById('action-button2--previous');
        const scrollRightButton2 = document.getElementById('action-button2--next');

        if (scrollLeftButton2 && scrollRightButton2) {
            scrollLeftButton2.addEventListener('click', () => {
                slideCard2.scrollBy({
                    top: 0,
                    left: -200,
                    behavior: 'smooth'
                });
            });

            scrollRightButton2.addEventListener('click', () => {
                slideCard2.scrollBy({
                    top: 0,
                    left: 200,
                    behavior: 'smooth'
                });
            });

            slideCard2.addEventListener('scroll', (e) => {
                const position2 = slideCard2.scrollLeft;
                if (position2 === 0) {
                    scrollLeftButton2.disabled = true;
                } else {
                    scrollLeftButton2.disabled = false;
                }

                if (
                    Math.round(position2) ===
                    slideCard2.scrollWidth -
                    slideCard2.clientWidth
                ) {
                    scrollRightButton2.disabled = true;
                } else {
                    scrollRightButton2.disabled = false;
                }
            });
        }

        function slideContent2(direction2) {
            const scrollAmount2 = 300;

            if (direction2 === 'left') {
                slideCard2.scrollLeft -= scrollAmount2;
            } else if (direction2 === 'right') {
                slideCard2.scrollLeft += scrollAmount2;
            }
        }

        window.slideContent2 = slideContent2;
    }
});