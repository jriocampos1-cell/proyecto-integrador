/* =========================================
   ParviPan — JavaScript principal
   ========================================= */

"use strict";

/* ---------- Toast helper ---------- */
const Toast = {
  container: null,

  init() {
    if (this.container) return;
    this.container = document.createElement("div");
    this.container.className = "pp-toast-container";
    document.body.appendChild(this.container);
  },

  show(msg, type = "success", duration = 3000) {
    this.init();
    const t = document.createElement("div");
    t.className = `pp-toast ${type}`;
    const iconMap = { success: "ti-check", danger: "ti-alert-circle", warning: "ti-alert-triangle", info: "ti-info-circle" };
    t.innerHTML = `<i class="ti ${iconMap[type] || "ti-info-circle"}"></i><span>${msg}</span>`;
    this.container.appendChild(t);
    setTimeout(() => { t.style.opacity = "0"; t.style.transition = "opacity .3s"; setTimeout(() => t.remove(), 300); }, duration);
  }
};

/* ---------- Sidebar mobile ---------- */
function initSidebar() {
  const sidebar = document.querySelector(".pp-sidebar");
  const toggleBtn = document.getElementById("pp-sidebar-toggle");
  if (!sidebar || !toggleBtn) return;

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
  });

  document.addEventListener("click", (e) => {
    if (sidebar.classList.contains("open") && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove("open");
    }
  });
}

/* ---------- Nav links activos ---------- */
function initNavLinks() {
  const currentPage = window.location.pathname.split("/").pop();
  document.querySelectorAll(".pp-nav-link").forEach((link) => {
    const href = link.getAttribute("href");
    if (href && href === currentPage) {
      link.classList.add("active");
    }
  });
}

/* ---------- Qty stepper ---------- */
function initQtyStepper() {
  document.querySelectorAll(".pp-qty-stepper").forEach((stepper) => {
    const input = stepper.querySelector("input");
    const btnMinus = stepper.querySelector(".btn-minus");
    const btnPlus  = stepper.querySelector(".btn-plus");
    if (!input || !btnMinus || !btnPlus) return;

    btnMinus.addEventListener("click", () => {
      const min = parseInt(input.min) || 0;
      const val = parseInt(input.value) || 0;
      if (val > min) input.value = val - 1;
    });

    btnPlus.addEventListener("click", () => {
      const max = parseInt(input.max) || 9999;
      const val = parseInt(input.value) || 0;
      if (val < max) input.value = val + 1;
    });

    input.addEventListener("input", () => {
      const min = parseInt(input.min) || 0;
      const max = parseInt(input.max) || 9999;
      let val = parseInt(input.value) || 0;
      if (val < min) input.value = min;
      if (val > max) input.value = max;
    });
  });
}

/* ---------- Barras de stock (animar al cargar) ---------- */
function initStockBars() {
  document.querySelectorAll(".pp-stock-bar-fill[data-pct]").forEach((bar) => {
    const pct = parseInt(bar.dataset.pct) || 0;
    bar.style.width = "0%";
    setTimeout(() => { bar.style.width = pct + "%"; }, 100);

    if (pct <= 15)      bar.classList.add("low");
    else if (pct <= 35) bar.classList.add("medium");
    else                bar.classList.add("high");
  });
}

/* ---------- Validación de formularios ---------- */
function validateForm(formEl) {
  let valid = true;
  formEl.querySelectorAll("[required]").forEach((field) => {
    const group = field.closest(".pp-form-group");
    field.classList.remove("is-invalid");
    if (group) group.querySelector(".pp-form-error")?.remove();

    if (!field.value.trim()) {
      valid = false;
      field.classList.add("is-invalid");
      field.style.borderColor = "var(--pp-red)";
      if (group) {
        const err = document.createElement("p");
        err.className = "pp-form-error";
        err.style.cssText = "font-size:12px;color:var(--pp-red);margin-top:3px";
        err.textContent = "Este campo es obligatorio.";
        group.appendChild(err);
      }
    } else {
      field.style.borderColor = "";
    }
  });
  return valid;
}

/* ---------- Cerrar sesión ---------- */
function initLogout() {
  document.querySelectorAll("[data-action='logout']").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (confirm("¿Cerrar sesión?")) {
        Toast.show("Sesión cerrada correctamente.", "success");
        setTimeout(() => { window.location.href = "login.html"; }, 1000);
      }
    });
  });
}

/* ---------- Confirmar acciones críticas ---------- */
function initConfirmActions() {
  document.querySelectorAll("[data-confirm]").forEach((el) => {
    el.addEventListener("click", (e) => {
      if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
  });
}

/* ---------- Filtro de tabla en tiempo real ---------- */
function initTableSearch(inputSelector, tableSelector) {
  const input = document.querySelector(inputSelector);
  const table = document.querySelector(tableSelector);
  if (!input || !table) return;

  input.addEventListener("input", () => {
    const q = input.value.toLowerCase();
    table.querySelectorAll("tbody tr").forEach((row) => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
    });
  });
}

/* ---------- Solicitud: bloquear si excede stock ---------- */
function initStockValidation() {
  const form = document.getElementById("form-solicitud");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const selectInsumo = form.querySelector("#sel-insumo");
    const inputCantidad = form.querySelector("#inp-cantidad");
    if (!selectInsumo || !inputCantidad) return;

    const stockMax = parseInt(selectInsumo.selectedOptions[0]?.dataset.stock) || 0;
    const solicitado = parseInt(inputCantidad.value) || 0;

    if (solicitado <= 0) {
      Toast.show("La cantidad debe ser mayor a cero.", "warning");
      return;
    }
    if (solicitado > stockMax) {
      Toast.show(`Stock insuficiente. Máximo disponible: ${stockMax} kg.`, "danger");
      inputCantidad.style.borderColor = "var(--pp-red)";
      return;
    }

    inputCantidad.style.borderColor = "";
    Toast.show("Solicitud enviada al gerente.", "success");
    setTimeout(() => form.reset(), 800);
  });
}

/* ---------- Aprobar / rechazar solicitudes ---------- */
function initSolicitudesGerente() {
  document.querySelectorAll(".btn-aprobar").forEach((btn) => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr, .pp-row-item");
      Toast.show("Solicitud aprobada. Inventario actualizado.", "success");
      if (row) {
        row.style.opacity = "0";
        row.style.transition = "opacity .3s";
        setTimeout(() => row.remove(), 300);
      }
    });
  });

  document.querySelectorAll(".btn-rechazar").forEach((btn) => {
    btn.addEventListener("click", () => {
      const motivo = prompt("Motivo del rechazo (obligatorio):");
      if (!motivo || !motivo.trim()) {
        Toast.show("Debes ingresar un motivo para rechazar.", "warning");
        return;
      }
      const row = btn.closest("tr, .pp-row-item");
      Toast.show("Solicitud rechazada.", "danger");
      if (row) {
        row.style.opacity = "0";
        row.style.transition = "opacity .3s";
        setTimeout(() => row.remove(), 300);
      }
    });
  });
}

/* ---------- Formulario entrada bodega ---------- */
function initFormEntrada() {
  const form = document.getElementById("form-entrada");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!validateForm(form)) {
      Toast.show("Completa todos los campos obligatorios.", "warning");
      return;
    }

    const fechaVenc = form.querySelector("#inp-fecha-venc");
    if (fechaVenc && new Date(fechaVenc.value) <= new Date()) {
      Toast.show("La fecha de vencimiento debe ser posterior a hoy.", "danger");
      fechaVenc.style.borderColor = "var(--pp-red)";
      return;
    }

    Toast.show("Entrada registrada exitosamente.", "success");
    setTimeout(() => form.reset(), 800);
  });
}

/* ---------- Formulario salida bodega ---------- */
function initFormSalida() {
  const form = document.getElementById("form-salida");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!validateForm(form)) {
      Toast.show("Completa todos los campos obligatorios.", "warning");
      return;
    }
    Toast.show("Salida registrada exitosamente.", "success");
    setTimeout(() => form.reset(), 800);
  });
}

/* ---------- Formulario login ---------- */
function initLogin() {
  const form = document.getElementById("form-login");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const user = form.querySelector("#inp-usuario").value.trim();
    const pass = form.querySelector("#inp-password").value.trim();

    if (!user || !pass) {
      Toast.show("Ingresa usuario y contraseña.", "warning");
      return;
    }

    /* Simulación de roles — en producción esto va al backend PHP */
    const roles = {
      "gerente":  "dashboard-gerencial.html",
      "cocinero": "dashboard-cocina.html",
      "bodega":   "inventario-bodega.html",
    };

    const dest = roles[user.toLowerCase()];
    if (dest) {
      Toast.show(`Bienvenido, ${user}.`, "success");
      setTimeout(() => { window.location.href = dest; }, 900);
    } else {
      Toast.show("Credenciales incorrectas.", "danger");
      form.querySelector("#inp-password").value = "";
    }
  });
}

/* ---------- Formulario proveedores ---------- */
function initFormProveedor() {
  const form = document.getElementById("form-proveedor");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!validateForm(form)) {
      Toast.show("Completa todos los campos obligatorios.", "warning");
      return;
    }
    Toast.show("Proveedor agregado al catálogo.", "success");
    setTimeout(() => form.reset(), 800);
  });
}

/* ---------- Formulario usuarios ---------- */
function initFormUsuario() {
  const form = document.getElementById("form-usuario");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!validateForm(form)) {
      Toast.show("Completa todos los campos obligatorios.", "warning");
      return;
    }
    Toast.show("Usuario creado exitosamente.", "success");
    setTimeout(() => form.reset(), 800);
  });
}

/* ---------- Desactivar usuario ---------- */
function initToggleUsuario() {
  document.querySelectorAll(".btn-toggle-user").forEach((btn) => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const badge = row?.querySelector(".estado-badge");
      if (!badge) return;

      const isActive = badge.textContent.trim() === "Activo";
      if (!confirm(isActive ? "¿Desactivar este usuario?" : "¿Reactivar este usuario?")) return;

      badge.textContent = isActive ? "Inactivo" : "Activo";
      badge.className = `pp-badge estado-badge ${isActive ? "pp-badge-danger" : "pp-badge-success"}`;
      Toast.show(`Usuario ${isActive ? "desactivado" : "reactivado"}.`, isActive ? "danger" : "success");
    });
  });
}

/* ---------- Init global ---------- */
document.addEventListener("DOMContentLoaded", () => {
  initSidebar();
  initNavLinks();
  initQtyStepper();
  initStockBars();
  initLogout();
  initConfirmActions();
  initStockValidation();
  initSolicitudesGerente();
  initFormEntrada();
  initFormSalida();
  initLogin();
  initFormProveedor();
  initFormUsuario();
  initToggleUsuario();

  /* Búsqueda en tablas si existen */
  initTableSearch("#search-historial", "#tabla-historial");
  initTableSearch("#search-proveedores", "#tabla-proveedores");
  initTableSearch("#search-usuarios", "#tabla-usuarios");
});