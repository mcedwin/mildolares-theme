(function () {
  'use strict';

  // ---------------------------------------------------------------
  // Menú móvil
  // ---------------------------------------------------------------
  var toggle = document.getElementById('menuToggle');
  var menu = document.getElementById('mobileMenu');

  if (toggle && menu) {
    var setOpen = function (open) {
      menu.classList.toggle('hidden', !open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    toggle.addEventListener('click', function () {
      setOpen(menu.classList.contains('hidden'));
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
        setOpen(false);
        toggle.focus();
      }
    });

    var desktop = window.matchMedia('(min-width: 768px)');

    var onChange = function (e) {
      if (e.matches) {
        setOpen(false);
      }
    };

    if (desktop.addEventListener) {
      desktop.addEventListener('change', onChange);
    } else if (desktop.addListener) {
      desktop.addListener(onChange);
    }
  }

  // ---------------------------------------------------------------
  // Calculadora de interés compuesto
  // ---------------------------------------------------------------
  var form = document.getElementById('mil-calc');

  if (form) {
    var output = document.getElementById('mil-calc-result');

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var capital = parseFloat(form.elements.capital.value);
      var rate = parseFloat(form.elements.rate.value);

      if (isNaN(capital) || capital < 0 || isNaN(rate) || rate < 0) {
        output.textContent = 'Introduce un capital y una tasa válidos.';
        return;
      }

      var fmt = new Intl.NumberFormat('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });

      var lines = [];

      for (var years = 1; years <= 5; years++) {
        var total = capital * Math.pow(1 + rate / 100, years);
        lines.push(years + ' años: ' + fmt.format(total) + ' USD');
      }

      output.textContent = lines.join(' · ');
    });
  }
})();