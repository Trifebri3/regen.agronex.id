document.addEventListener('DOMContentLoaded', () => {
    // Responsive mobile menu drawers
    document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-menu-toggle');
            const target = targetId ? document.getElementById(targetId) : null;
            if (!target) {
                return;
            }
            const isHidden = target.classList.toggle('hidden');
            button.setAttribute('aria-expanded', String(!isHidden));
        });
    });

    // Prototype gallery image lightbox
    const lightbox = document.getElementById('regen-lightbox');
    const lightboxImg = document.getElementById('regen-lightbox-img');
    const lightboxCaption = document.getElementById('regen-lightbox-caption');

    if (lightbox && lightboxImg) {
        document.querySelectorAll('[data-lightbox-src]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const src = trigger.getAttribute('data-lightbox-src') || '';
                const caption = trigger.getAttribute('data-lightbox-caption') || '';
                lightboxImg.setAttribute('src', src);
                lightboxImg.setAttribute('alt', caption || 'REGEN Prototype Image');
                if (lightboxCaption) {
                    lightboxCaption.textContent = caption;
                }
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
            });
        });

        lightbox.querySelectorAll('[data-lightbox-close]').forEach((closeBtn) => {
            closeBtn.addEventListener('click', () => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                lightboxImg.setAttribute('src', '');
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            }
        });
    }

    // Live interactive financial calculator instant preview (paired with server-side calculation)
    const calcForm = document.getElementById('regen-bi-calculator');
    if (calcForm) {
        const formatIDR = (val) =>
            new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(Number.isFinite(val) ? val : 0);

        const formatNum = (val) =>
            new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 2,
            }).format(Number.isFinite(val) ? val : 0);

        const parseSafe = (name) => {
            const el = calcForm.querySelector(`[name="${name}"]`);
            if (!el) {
                return 0;
            }
            const num = parseFloat(el.value);
            return Number.isFinite(num) && num >= 0 ? num : 0;
        };

        const updateLivePreview = () => {
            const fixed =
                parseSafe('land_lease_fixed') +
                parseSafe('equipment_fixed') +
                parseSafe('other_fixed');
            const variable =
                parseSafe('land_preparation_cost') +
                parseSafe('input_cost') +
                parseSafe('labor_cost') +
                parseSafe('operational_cost') +
                parseSafe('transportation_cost');
            const totalCost = fixed + variable;

            const harvested = parseSafe('harvested_quantity');
            const saleable = parseSafe('saleable_quantity');
            const sold = parseSafe('quantity_sold');
            const price = parseSafe('selling_price_per_unit');
            const basisEl = calcForm.querySelector('[name="hpp_basis"]:checked') || calcForm.querySelector('[name="hpp_basis"]');
            const basis = basisEl ? basisEl.value : 'saleable';
            const basisQty = basis === 'harvested' ? harvested : saleable;

            const revenue = sold * price;
            const profit = revenue - totalCost;
            const hpp = basisQty > 0 ? totalCost / basisQty : null;

            const customVarUnitEl = calcForm.querySelector('[name="variable_cost_per_unit"]');
            const customVarUnit = customVarUnitEl && customVarUnitEl.value !== '' ? parseFloat(customVarUnitEl.value) : null;
            const varPerUnit =
                customVarUnit !== null && Number.isFinite(customVarUnit) && customVarUnit > 0
                    ? customVarUnit
                    : basisQty > 0
                      ? variable / basisQty
                      : null;

            const marginUnit = varPerUnit !== null ? price - varPerUnit : null;
            const bepQty = marginUnit !== null && marginUnit > 0 ? fixed / marginUnit : null;

            const bindText = (id, text) => {
                const target = document.getElementById(id);
                if (target) {
                    target.textContent = text;
                }
            };

            bindText('live-total-cost', formatIDR(totalCost));
            bindText('live-revenue', formatIDR(revenue));
            bindText('live-profit-loss', formatIDR(profit));
            bindText('live-hpp-unit', hpp !== null ? `${formatIDR(hpp)} / kg` : 'N/A (Kuantitas 0)');
            bindText('live-bep-qty', bepQty !== null ? `${formatNum(bepQty)} kg` : 'Tidak Tercapai / N/A');
        };

        calcForm.addEventListener('input', updateLivePreview);
        calcForm.addEventListener('change', updateLivePreview);
    }
});
