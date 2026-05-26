$(document).ready(function () {
    /* ─────────────────────────────────────────
       Shared state
    ───────────────────────────────────────── */
    const cards          = $('.slider-card');
    const imageEl        = $('#active-slide-image');
    const dotsContainer  = $('#slider-dots');
    const sliderPanel    = $('.slider-panel');       // original desktop column

    let currentIndex = parseInt(cards.filter('.active').data('index'), 10);
    if (Number.isNaN(currentIndex)) currentIndex = 0;

    const totalSlides = cards.length;

    /* ─────────────────────────────────────────
       Dot navigation (built once; shared)
    ───────────────────────────────────────── */
    function buildDots() {
        dotsContainer.empty();
        for (let i = 0; i < totalSlides; i++) {
            $('<button>', {
                class: 'slider-dot' + (i === currentIndex ? ' active' : ''),
                'data-index': i,
                'aria-label': 'Slide ' + (i + 1),
                type: 'button',
            }).appendTo(dotsContainer);
        }
    }

    /* ─────────────────────────────────────────
       Update slide (desktop + mobile)
    ───────────────────────────────────────── */
    function updateSlide(index) {
        if (totalSlides === 0) return;

        index        = (index + totalSlides) % totalSlides;
        currentIndex = index;

        cards.removeClass('active');
        const activeCard = cards.filter('[data-index="' + index + '"]');
        activeCard.addClass('active');

        // Desktop: update image panel
        const nextImage = activeCard.data('image');
        if (imageEl.length && nextImage) {
            imageEl.attr('src', nextImage).attr('alt', activeCard.find('.slider-title').text());
        }

        // Dots (wherever they live)
        $('.slider-dot').removeClass('active');
        $('.slider-dot[data-index="' + index + '"]').addClass('active');
    }

    /* ─────────────────────────────────────────
       Mobile background images
    ───────────────────────────────────────── */
    function setMobileBackgrounds() {
        cards.each(function () {
            $(this).css('background-image', 'none');
        });
    }

    /* ─────────────────────────────────────────
       Mobile accordion
       Each topic-tab gets an inline copy of the
       slider panel injected right after it.
    ───────────────────────────────────────── */
    let mobileInited = false;

    function initMobileAccordion() {
        if (mobileInited) return;
        mobileInited = true;

        const tabs = $('.topic-tab');

        tabs.each(function () {
            const tab = $(this);

            // Build a wrapper that will hold the slider panel inline
            const body = $('<div>', { class: 'accordion-slider-body' });

            // Clone the slider panel into this accordion body
            const panelClone = sliderPanel.clone(true);   // true = copy events
            panelClone.show();
            body.append(panelClone);

            // Re-attach dots container reference inside the clone
            const cloneDots = panelClone.find('.slider-dots');
            cloneDots.attr('id', '');   // remove duplicate id

            // Show/hide based on active state
            body.css('display', tab.hasClass('active') ? 'block' : 'none');
            tab.after(body);
        });
    }

    function teardownMobileAccordion() {
        if (!mobileInited) return;
        mobileInited = false;
        $('.accordion-slider-body').remove();
        // Restore the original slider panel
        sliderPanel.closest('.col-lg-5.order-lg-2').show();
    }

    /* ─────────────────────────────────────────
       Accordion tab click (mobile)
    ───────────────────────────────────────── */
    $(document).on('click', '.topic-tab', function (e) {
        if (!window.matchMedia('(max-width: 767px)').matches) return; // desktop handles via PHP link

        e.preventDefault();
        const clicked = $(this);
        const body    = clicked.next('.accordion-slider-body');

        if (clicked.hasClass('active')) {
            clicked.removeClass('active');
            body.slideUp(200);
            updateToggleIcons();
            return;
        }

        // Deactivate all tabs and hide all accordion bodies
        $('.topic-tab').removeClass('active');
        $('.accordion-slider-body').slideUp(200);

        // Activate clicked tab
        clicked.addClass('active');

        // Show its accordion body
        body.slideDown(200);

        // Reset to first slide inside that panel clone
        const bodySliders = body.find('.slider-card');
        bodySliders.removeClass('active');
        bodySliders.first().addClass('active');

        // Sync dots inside clone
        body.find('.slider-dot').removeClass('active');
        body.find('.slider-dot[data-index="0"]').addClass('active');

        currentIndex = 0;
        updateToggleIcons();
    });

    /* ─────────────────────────────────────────
       Dot click — delegate so it works in clones
    ───────────────────────────────────────── */
    $(document).on('click', '.slider-dot', function () {
        const selectedIndex = parseInt($(this).data('index'), 10);
        if (Number.isNaN(selectedIndex)) return;

        const isMobile = window.matchMedia('(max-width: 767px)').matches;

        if (isMobile) {
            // Find the accordion body this dot lives in
            const body        = $(this).closest('.accordion-slider-body');
            const bodyCards   = body.find('.slider-card');
            const bodyDots    = body.find('.slider-dot');
            const total       = bodyCards.length;
            const safeIndex   = (selectedIndex + total) % total;

            bodyCards.removeClass('active');
            bodyCards.filter('[data-index="' + safeIndex + '"]').addClass('active');
            bodyDots.removeClass('active');
            bodyDots.filter('[data-index="' + safeIndex + '"]').addClass('active');
        } else {
            updateSlide(selectedIndex);
        }
    });

    /* ─────────────────────────────────────────
       Responsive initialisation
    ───────────────────────────────────────── */
    function updateToggleIcons() {
        $('.topic-tab').each(function () {
            const tab = $(this);
            const icon = tab.find('.toggle-icon');
            if (!icon.length) return;
            const isOpen = tab.hasClass('active');
            icon.attr('src', isOpen ? 'files/images/minus-01.svg' : 'files/images/plus-01.svg');
            icon.attr('alt', isOpen ? 'close' : 'open');
        });
    }

    function handleResize() {
        const isMobile = window.matchMedia('(max-width: 767px)').matches;
        setMobileBackgrounds();
        updateToggleIcons();

        if (isMobile) {
            initMobileAccordion();
        } else {
            teardownMobileAccordion();
        }
    }

    /* ─────────────────────────────────────────
       Boot
    ───────────────────────────────────────── */
    buildDots();
    setMobileBackgrounds();
    updateToggleIcons();
    updateSlide(currentIndex);
    handleResize();

    $(window).on('resize', debounce(handleResize, 150));

    /* ─────────────────────────────────────────
       Utility
    ───────────────────────────────────────── */
    function debounce(fn, delay) {
        let timer;
        return function () {
            clearTimeout(timer);
            timer = setTimeout(fn, delay);
        };
    }
});