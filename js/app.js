// App JS - vanilla helpers: sidebar, nav highlight, filtering, validation, toasts

(function(){
  'use strict';

  const sel = {
    sidebar: '.sidebar',
    toggle: '.btn-toggle',
    navLinks: '.sidebar nav a',
    toasts: '.toasts'
  };

  function $(s){ return document.querySelector(s); }
  function $all(s){ return Array.from(document.querySelectorAll(s)); }

  function ensureToasts(){
    let c = $(sel.toasts);
    if(c) return c;
    c = document.createElement('div'); c.className = 'toasts'; document.body.appendChild(c); return c;
  }

  function showToast(type, message, timeout = 3500){
    const c = ensureToasts();
    const t = document.createElement('div'); t.className = `toast ${type}`; t.textContent = message;
    c.appendChild(t);
    setTimeout(()=>{ t.style.opacity = '0'; setTimeout(()=> t.remove(), 200); }, timeout);
  }

  function initSidebar(){
    const sidebar = $(sel.sidebar);
    const toggle = $(sel.toggle);
    if(!sidebar || !toggle) return;
    toggle.addEventListener('click', ()=> sidebar.classList.toggle('open'));
    document.addEventListener('click', (e)=>{
      if(window.innerWidth <= 900 && sidebar.classList.contains('open')){
        if(!sidebar.contains(e.target) && !toggle.contains(e.target)) sidebar.classList.remove('open');
      }
    });
  }

  function highlightActive(){
    const links = $all(sel.navLinks);
    const p = location.pathname.split('/').pop();
    links.forEach(a=>{
      const href = a.getAttribute('href');
      if(!href) return;
      if(href === p || href === './' + p || href === ('../' + p)) a.classList.add('active'); else a.classList.remove('active');
    });
  }

  function filterTable(inputEl, tableEl){
    if(!inputEl || !tableEl) return;
    const run = ()=>{
      const q = inputEl.value.trim().toLowerCase();
      const rows = Array.from(tableEl.tBodies[0].rows);
      rows.forEach(r=> r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none');
    };
    inputEl.addEventListener('input', run);
    run();
  }

  function validateForm(form){
    if(!form) return true;
    const emailRE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let ok = true;
    Array.from(form.querySelectorAll('[required]')).forEach(inp=>{
      inp.classList.remove('form-error');
      const v = (inp.value || '').trim();
      if(v === ''){ ok = false; inp.classList.add('form-error'); }
      if(inp.type === 'email' && v !== '' && !emailRE.test(v)){ ok = false; inp.classList.add('form-error'); }
    });
    return ok;
  }

  function wireDemoForms(){
    $all('form[data-demo]').forEach(form=>{
      form.addEventListener('submit', e=>{
        e.preventDefault();
        if(!validateForm(form)){ showToast('error','Please fix the errors'); return; }
        showToast('success','Saved (demo)');
        const redirect = form.dataset.redirect;
        if(redirect) setTimeout(()=> location.href = redirect, 700);
      });
    });
  }

  function wireSearchInputs(){
    $all('[data-filter-for]').forEach(inp => {
      const table = document.getElementById(inp.dataset.filterFor);
      if(table) filterTable(inp, table);
    });
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    initSidebar();
    highlightActive();
    wireDemoForms();
    wireSearchInputs();
    window.showToast = showToast;
    window.validateForm = (idOrEl) => validateForm(typeof idOrEl === 'string' ? document.getElementById(idOrEl) : idOrEl);
    window.filterTable = (inputId, tableId) => filterTable(document.getElementById(inputId), document.getElementById(tableId));
  });

})();

