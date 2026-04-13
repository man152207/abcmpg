document.addEventListener('DOMContentLoaded', function () {
    initSmmxModule();
});

function initSmmxModule() {
    addFadeAnimation();
    enhanceCards();
    initAutoDismissAlerts();
    initTextareaAutoResize();
    initProgressBars();
    initFormUnsavedNotice();
    initTableRowHover();
    initNumberFieldSafety();
    initQuickStatusBadges();
    initSmoothButtons();
    console.log('SMMX modern UI loaded');
}

/* -------------------------
   ANIMATIONS
------------------------- */
function addFadeAnimation() {
    document.querySelectorAll('.card, .small-box').forEach((el, index) => {
        el.classList.add('smmx-fade-up');
        el.style.animationDelay = `${index * 0.04}s`;
    });
}

/* -------------------------
   CARD HOVER
------------------------- */
function enhanceCards() {
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });
}

/* -------------------------
   ALERT AUTO HIDE
------------------------- */
function initAutoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all .4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(() => alert.remove(), 400);
        }, 3000);
    });
}

/* -------------------------
   TEXTAREA AUTO HEIGHT
------------------------- */
function initTextareaAutoResize() {
    document.querySelectorAll('textarea.form-control').forEach(textarea => {
        const resize = () => {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        };

        textarea.addEventListener('input', resize);
        resize();
    });
}

/* -------------------------
   PROGRESS BARS
------------------------- */
function initProgressBars() {
    document.querySelectorAll('.smmx-progress-bar').forEach(bar => {
        const target = bar.getAttribute('data-width') || bar.style.width || '0%';
        bar.style.width = '0%';

        setTimeout(() => {
            bar.style.transition = 'width 1s ease';
            bar.style.width = target;
        }, 250);
    });
}

/* -------------------------
   FORM DIRTY NOTICE
------------------------- */
function initFormUnsavedNotice() {
    const forms = document.querySelectorAll('form');
    let isDirty = false;

    forms.forEach(form => {
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('change', () => isDirty = true);
            field.addEventListener('input', () => isDirty = true);
        });

        form.addEventListener('submit', () => isDirty = false);
    });

    window.addEventListener('beforeunload', function (e) {
        if (!isDirty) return;
        e.preventDefault();
        e.returnValue = '';
    });
}

/* -------------------------
   TABLE POLISH
------------------------- */
function initTableRowHover() {
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.transition = 'all .2s ease';
        });
    });
}

/* -------------------------
   NUMBER FIELD SAFETY
------------------------- */
function initNumberFieldSafety() {
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function () {
            if (this.value < 0) this.value = 0;
        });
    });
}

/* -------------------------
   STATUS BADGES
------------------------- */
function initQuickStatusBadges() {
    document.querySelectorAll('td, p, div').forEach(el => {
        const text = (el.textContent || '').trim().toLowerCase();

        if (
            text === 'completed' ||
            text === 'draft' ||
            text === 'pending' ||
            text === 'in progress' ||
            text === 'report sent' ||
            text === 'sent'
        ) {
            el.classList.add('smmx-status-text');
        }
    });
}

/* -------------------------
   BUTTON FEEDBACK
------------------------- */
function initSmoothButtons() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function () {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 120);
        });
    });
}