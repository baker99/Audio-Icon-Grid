document.addEventListener('DOMContentLoaded', function () {
    // ---- STATE ----
    var state = {
        currentGrid: 'main',
        data: typeof AIG_DATA !== 'undefined' ? AIG_DATA : { global: {}, main: [], subs: {} }
    };

    // ---- SAFE HELPERS ----
    function getCell(event) {
        var target = event.target;
        while (target && !target.classList.contains('aig-cell')) {
            target = target.parentNode;
        }
        return target;
    }

    function playAudio(event) {
        var cell = getCell(event);
        if (!cell) return;

        var url = cell.getAttribute('data-audio');
        if (url) {
            try {
                var audio = new Audio(url);
                audio.play();
            } catch (e) {
                // silently ignore audio errors
            }
        }
    }

    function switchGrid(event) {
        var cell = getCell(event);
        if (!cell) return;

        var gridName = cell.getAttribute('data-grid');
        if (!gridName) return;

        state.currentGrid = gridName;
        renderGrid();
    }

    function returnToMain() {
        state.currentGrid = 'main';
        renderGrid();
    }

    // ---- RENDER ----
    function renderGrid() {
        var gridEl = document.querySelector('.aig-grid');
        if (!gridEl || !state.data || !state.data.global) return;

        var global = state.data.global;
        var main = state.data.main || [];
        var subs = state.data.subs || {};

        var isMain = state.currentGrid === 'main';
        var key = state.currentGrid.replace('subgrid_', '');
        var cells = isMain ? main : (subs[key] || []);

        gridEl.classList.add('switching');

        setTimeout(function () {
            gridEl.innerHTML = '';

            var cols = isMain ? global.main_cols : global.sub_cols;
            if (!cols || cols < 1) cols = 3; // safe default
            gridEl.style.gridTemplateColumns = 'repeat(' + cols + ', 1fr)';

            cells.forEach(function (cell) {
                var div = document.createElement('div');
                div.className = 'aig-cell';
               // div.style.background = global.cell_bg || '#e0e0e0';
                div.style.borderColor = global.cell_border || '#cccccc';

                // Icon
                var img = document.createElement('img');
                img.src = cell.icon_url || '';
                img.alt = cell.text || '';
                div.appendChild(img);

                // Text
                if (cell.text) {
                    var txt = document.createElement('div');
                    txt.className = 'aig-text';
                    txt.innerText = cell.text;
                    div.appendChild(txt);
                }

                // Actions
                if (isMain) {
                    if (cell.action === 'play') {
                        div.setAttribute('data-audio', cell.audio_url || '');
                        div.addEventListener('click', playAudio);
                    } else {
                        div.setAttribute('data-grid', cell.action || '');
                        div.addEventListener('click', switchGrid);
                    }
                } else {
                    div.setAttribute('data-audio', cell.audio_url || '');
                    div.addEventListener('click', playAudio);
                }

                gridEl.appendChild(div);
            });

            // Return cell
            if (!isMain && global.return_icon_url) {
                var ret = document.createElement('div');
                ret.className = 'aig-cell aig-return';

                var img2 = document.createElement('img');
                img2.src = global.return_icon_url;
                img2.alt = 'Return';
                ret.appendChild(img2);

                ret.addEventListener('click', returnToMain);
                gridEl.appendChild(ret);
            }

            gridEl.classList.remove('switching');
        }, 50);
    }

    // ---- CSS VARIABLES ----
    if (state.data && state.data.global) {
        var root = document.documentElement;
        var g = state.data.global;

        root.style.setProperty('--aig-font-family', g.font_family || 'Arial');
        root.style.setProperty('--aig-font-size', (g.font_size || 16) + 'px');
        root.style.setProperty('--aig-font-weight', g.font_weight || 'normal');
        root.style.setProperty('--aig-text-color', g.text_color || '#000000');
       // root.style.setProperty('--aig-cell-bg', g.cell_bg || '#e0e0e0');
        root.style.setProperty('--aig-cell-border', g.cell_border || '#cccccc');
    }

    // ---- INITIAL RENDER ----
    renderGrid();
});
