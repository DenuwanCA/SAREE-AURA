(function () {
  'use strict';
  var WA = '94769864511', KEY = 'sareeAuraCart2', MAX_QTY = 99;
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var body = document.body, base = body.dataset.base || '';
  var el = function (tag, cls, text) { var n = document.createElement(tag); if (cls) n.className = cls; if (text !== undefined) n.textContent = text; return n; };

  /* Catalogue data (embedded by PHP on every page) */
  var catalog = [], byId = {};
  try { catalog = JSON.parse($('#catalogData').textContent) || []; } catch (e) { catalog = []; }
  function norm(s) { return String(s).toLowerCase().replace(/&/g, ' and ').replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim(); }
  catalog.forEach(function (p) { p.hay = norm(p.name + ' ' + p.category + ' ' + p.tags.join(' ')); byId[p.id] = p; });
  function matches(p, q) { return !q || q.split(' ').every(function (t) { return p.hay.indexOf(t) > -1; }); }
  var money = function (cents) { return 'LKR ' + (cents / 100).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

  /* Mobile menu */
  var menuBtn = $('#menuToggle'), nav = $('#nav');
  menuBtn.addEventListener('click', function () { var o = nav.classList.toggle('open'); menuBtn.setAttribute('aria-expanded', o); });
  $$('a', nav).forEach(function (a) { a.addEventListener('click', function () { nav.classList.remove('open'); menuBtn.setAttribute('aria-expanded', 'false'); }); });

  /* Active nav link while scrolling the home page */
  if (body.dataset.page === 'home') {
    var spy = $$('.nav a[data-spy]'), ticking = false;
    var updateSpy = function () {
      var cur = 'home';
      ['about', 'sarees', 'accessories', 'dresses'].forEach(function (id) {
        var s = document.getElementById(id);
        if (s && !s.hidden && s.getBoundingClientRect().top <= 160) cur = id;
      });
      spy.forEach(function (a) {
        var on = a.dataset.spy === cur;
        a.classList.toggle('active', on);
        if (on) a.setAttribute('aria-current', 'page'); else a.removeAttribute('aria-current');
      });
      ticking = false;
    };
    window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(updateSpy); } }, { passive: true });
    updateSpy();
  }

  /* Filtering: search text + saree tabs */
  var state = { tab: 'all', q: '' };
  var input = $('#searchInput'), panel = $('#searchPanel'), noResults = $('#noResults');
  function applyFilters() {
    var total = 0, hasCards = false;
    $$('[data-catalog]').forEach(function (sec) {
      var shown = 0;
      $$('.card', sec).forEach(function (c) {
        hasCards = true;
        var ok = (sec.id !== 'sarees' || state.tab === 'all' || c.dataset.cat === state.tab) && matches(byId[c.dataset.id], state.q);
        c.hidden = !ok; if (ok) shown++;
      });
      sec.hidden = shown === 0; total += shown;
    });
    if (noResults) noResults.hidden = !hasCards || total > 0;
  }
  $$('.tabs button.tab').forEach(function (t) {
    t.addEventListener('click', function () {
      state.tab = t.dataset.tab;
      $$('.tabs button.tab').forEach(function (x) { var on = x === t; x.classList.toggle('active', on); x.setAttribute('aria-selected', on); });
      var crumb = $('#crumbCurrent');
      if (crumb && document.body.dataset.page === 'collections') crumb.textContent = state.tab === 'all' ? 'Sarees' : t.textContent + ' Sarees';
      applyFilters();
    });
  });

  /* Search suggestions */
  var CHIPS = [['Bridal', 'bridal'], ['Party Saree', 'party saree'], ['Sequin Work', 'sequin work'], ['Handloom', 'handloom'], ['Lehenga', 'lehenga'], ['Dresses & Gowns', 'dresses and gowns']];
  function setQuery(text) { input.value = text; state.q = norm(text); applyFilters(); renderPanel(); }
  function closePanel() { panel.hidden = true; input.setAttribute('aria-expanded', 'false'); }
  function pick(p) {
    var card = document.getElementById('p' + p.id);
    if (!card) { location.href = base + 'index.php?q=' + encodeURIComponent(p.name) + '#p' + p.id; return; }
    input.value = p.name; state.q = norm(p.name); state.tab = 'all'; applyFilters(); closePanel();
    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    card.classList.add('flash'); setTimeout(function () { card.classList.remove('flash'); }, 1800);
  }
  function renderPanel() {
    panel.innerHTML = '';
    var q = norm(input.value);
    if (!q) {
      panel.appendChild(el('p', 'sp-title', 'Popular searches'));
      var row = el('div', 'chips');
      CHIPS.forEach(function (c) { var b = el('button', 'chip', c[0]); b.type = 'button'; b.addEventListener('click', function () { setQuery(c[1]); input.focus(); }); row.appendChild(b); });
      panel.appendChild(row);
    } else {
      var list = catalog.filter(function (p) { return matches(p, q); });
      if (!list.length) panel.appendChild(el('p', 'sp-empty', 'No matching items found'));
      list.slice(0, 6).forEach(function (p) {
        var b = el('button', 'sugg'); b.type = 'button';
        var img = el('img'); img.src = p.image; img.alt = ''; img.width = 44; img.height = 56; img.loading = 'lazy';
        var t = el('span', 'sugg-text'); t.appendChild(el('strong', '', p.name));
        t.appendChild(el('small', '', p.category + ' \u00b7 ' + money(Math.round(p.price * 100))));
        b.appendChild(img); b.appendChild(t);
        b.addEventListener('click', function () { pick(p); });
        panel.appendChild(b);
      });
      if (list.length > 6) panel.appendChild(el('p', 'sp-more', 'Showing 6 of ' + list.length + ' matches. Keep typing to narrow down.'));
    }
    panel.hidden = false; input.setAttribute('aria-expanded', 'true');
  }
  input.addEventListener('input', function () { state.q = norm(input.value); applyFilters(); renderPanel(); });
  input.addEventListener('focus', renderPanel);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePanel();
    if (e.key === 'Enter') {
      e.preventDefault();
      if (!$('.card') && state.q) location.href = base + 'index.php?q=' + encodeURIComponent(input.value.trim());
      else closePanel();
    }
  });
  document.addEventListener('click', function (e) { if (!$('#searchBox').contains(e.target)) closePanel(); });
  var reset = $('#resetSearch');
  if (reset) reset.addEventListener('click', function () {
    state.tab = 'all'; var all = $('.tabs button[data-tab="all"]'); if (all) all.click();
    setQuery(''); closePanel();
  });
  var hints = ['Bridal Saree', 'Sequin Work', 'Handloom', 'Lehenga', 'Handbag', 'Organza', 'Satin Dress'], hi = 0;
  setInterval(function () { if (document.activeElement !== input && !input.value) { hi = (hi + 1) % hints.length; input.placeholder = 'Try: ' + hints[hi]; } }, 3000);

  /* Start-up: ?q=, ?tab=, #p123 */
  var params = new URLSearchParams(location.search);
  if (params.get('tab')) { var tb = $('.tabs button[data-tab="' + params.get('tab').replace(/[^a-z-]/g, '') + '"]'); if (tb) tb.click(); }
  if (params.get('q')) { input.value = params.get('q'); state.q = norm(input.value); applyFilters(); }
  if (/^#p\d+$/.test(location.hash)) { var tc = document.getElementById(location.hash.slice(1)); if (tc && !tc.hidden) setTimeout(function () { tc.scrollIntoView({ block: 'center' }); tc.classList.add('flash'); }, 150); }

  /* Selection Bag */
  var drawer = $('#drawer'), overlay = $('#overlay'), list = $('#cartItems'), lastFocus = null;
  var cents = function (p) { return Math.round(p.price * 100); };  // whole cents avoid floating-point errors
  var bag = [];
  try {
    (JSON.parse(localStorage.getItem(KEY)) || []).forEach(function (e) {
      var q = parseInt(e && e.qty, 10);
      if (e && byId[e.id] && q > 0 && !bag.some(function (b) { return b.id === String(e.id); })) bag.push({ id: String(e.id), qty: Math.min(q, MAX_QTY) });
    });
  } catch (e) { bag = []; }
  function save() { try { localStorage.setItem(KEY, JSON.stringify(bag)); } catch (e) {} }
  function openBag(open) {
    if (open) lastFocus = document.activeElement;
    drawer.classList.toggle('open', open); drawer.setAttribute('aria-hidden', !open);
    overlay.hidden = !open; body.classList.toggle('no-scroll', open);
    if (open) $('#cartClose').focus(); else if (lastFocus && lastFocus.focus) lastFocus.focus();
  }
  $('#cartToggle').addEventListener('click', function () { openBag(true); });
  $('#cartClose').addEventListener('click', function () { openBag(false); });
  overlay.addEventListener('click', function () { openBag(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && drawer.classList.contains('open')) openBag(false); });
  function change(id, d) {
    var it = bag.filter(function (b) { return b.id === id; })[0];
    if (!it) { if (d > 0) bag.push({ id: id, qty: 1 }); }
    else { it.qty = Math.min(it.qty + d, MAX_QTY); if (it.qty < 1) bag.splice(bag.indexOf(it), 1); }
    render();
  }
  $$('[data-add]').forEach(function (b) { b.addEventListener('click', function () { change(b.closest('.card').dataset.id, 1); openBag(true); }); });
  $('#clearBag').addEventListener('click', function () { bag = []; render(); });

  function render() {
    save(); list.innerHTML = '';
    var subtotal = 0, count = 0, lines = [];
    bag.forEach(function (item, n) {
      var p = byId[item.id], line = cents(p) * item.qty;
      count += item.qty; subtotal += line;
      lines.push((n + 1) + '. ' + p.name + ' (' + p.category + ') - Qty: ' + item.qty + ' - ' + money(line));
      var li = el('li'), info = el('div');
      info.appendChild(el('p', 'ci-name', p.name)); info.appendChild(el('p', 'ci-meta', p.category));
      info.appendChild(el('p', 'ci-price', item.qty > 1 ? money(cents(p)) + ' \u00d7 ' + item.qty + ' = ' + money(line) : money(line)));
      var q = el('div', 'qty'), minus = el('button', '', '\u2212'), plus = el('button', '', '+');
      minus.type = plus.type = 'button';
      minus.setAttribute('aria-label', 'Decrease quantity of ' + p.name); plus.setAttribute('aria-label', 'Increase quantity of ' + p.name);
      plus.disabled = item.qty >= MAX_QTY;
      minus.addEventListener('click', function () { change(item.id, -1); }); plus.addEventListener('click', function () { change(item.id, 1); });
      q.appendChild(minus); q.appendChild(el('span', '', String(item.qty))); q.appendChild(plus);
      var rm = el('button', 'remove', 'Remove'); rm.type = 'button';
      rm.addEventListener('click', function () { bag.splice(bag.indexOf(item), 1); render(); });
      li.appendChild(info); li.appendChild(q); li.appendChild(rm); list.appendChild(li);
    });
    var badge = $('#cartCount'); badge.textContent = count; badge.hidden = count === 0;
    $('#cartEmpty').hidden = bag.length > 0; $('#clearBag').hidden = bag.length === 0;
    $('#cartSubtotal').textContent = money(subtotal);
    var btn = $('#orderBtn');
    btn.classList.toggle('disabled', !bag.length); btn.setAttribute('aria-disabled', !bag.length);
    btn.href = bag.length ? 'https://wa.me/' + WA + '?text=' + encodeURIComponent('Hello Saree Aura, I would like to order the following:\n\n' + lines.join('\n') + '\n\nSubtotal: ' + money(subtotal)) : '#';
  }
  $('#orderBtn').addEventListener('click', function (e) { if (!bag.length) e.preventDefault(); });
  render();
})();
