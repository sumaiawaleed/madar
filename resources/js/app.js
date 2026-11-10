const SAR = document.querySelector('template#sar')?.innerHTML ?? '';
const money = (n) => `<span class="num">${SAR}${(Math.round(n * 100) / 100).toLocaleString('en-US', { maximumFractionDigits: 2 })}</span>`;

/* Line items editor used by proposals and invoices */
document.querySelectorAll('[data-line-items]').forEach((editor) => {
    const rows = editor.querySelector('.items');
    const template = editor.querySelector('template');
    const totals = editor.querySelector('.totals');
    const vat = document.getElementById(editor.dataset.vatToggle ?? '');

    const renumber = () => {
        rows.querySelectorAll('.item').forEach((row, index) => {
            row.querySelectorAll('[data-name]').forEach((input) => {
                input.name = `items[${index}][${input.dataset.name}]`;
            });
        });
    };

    const drawTotals = () => {
        let subtotal = 0;
        rows.querySelectorAll('.item').forEach((row) => {
            const quantity = parseFloat(row.querySelector('[data-name="quantity"]').value) || 0;
            const price = parseFloat(row.querySelector('[data-name="price"]').value) || 0;
            subtotal += quantity * price;
        });
        const withVat = vat?.checked ?? false;
        totals.innerHTML = `<span>المجموع ${money(subtotal)}</span>${withVat ? `<span>الضريبة 15٪ ${money(subtotal * 0.15)}</span>` : ''}<b>${money(withVat ? subtotal * 1.15 : subtotal)}</b>`;
    };

    const addRow = () => {
        rows.append(template.content.cloneNode(true));
        renumber();
        drawTotals();
        rows.lastElementChild.querySelector('input').focus();
    };

    editor.addEventListener('click', (event) => {
        if (event.target.closest('[data-add-item]')) {
            addRow();
        }
        const remove = event.target.closest('[data-remove-item]');
        if (remove) {
            remove.closest('.item').remove();
            if (!rows.querySelector('.item')) {
                addRow();
            }
            renumber();
            drawTotals();
        }
    });
    editor.addEventListener('input', drawTotals);
    vat?.addEventListener('change', drawTotals);
    renumber();
    drawTotals();
});

/* Selects that apply immediately: filters and the project board stage picker */
document.addEventListener('change', (event) => {
    if (event.target.matches('[data-autosubmit]')) {
        event.target.form.requestSubmit();
    }
});

/* Destructive buttons need a second click, like the original design */
document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-confirm]');
    if (!button || button.dataset.armed) {
        return;
    }
    event.preventDefault();
    button.dataset.armed = '1';
    button.textContent = button.dataset.confirm;
    setTimeout(() => {
        delete button.dataset.armed;
        button.textContent = button.dataset.label;
    }, 5000);
});

/* Logo picker preview */
document.querySelectorAll('[data-logo-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const file = input.files[0];
        const box = document.getElementById(input.dataset.logoInput);
        if (file && box && file.type.startsWith('image/')) {
            box.innerHTML = '';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = '';
            box.append(img);
        }
    });
});

document.querySelector('[data-print]')?.addEventListener('click', () => window.print());
