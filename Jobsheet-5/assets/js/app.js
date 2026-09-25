function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");

    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

initNavToggle();

function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const nama = row
                ? row.querySelector("td")?.textContent
                : "data ini";

            const yakin = confirm(
                'Yakin ingin menghapus "' + nama + '"?'
            );

            if (yakin && row) {
                row.remove();
            }
        });
    });
}

initHapusConfirm();

function initTableFilter() {
    const input = document.getElementById("search-input");

    if (!input) return;

    const rows = document.querySelectorAll("table tbody tr");

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();

        rows.forEach(function (row) {
            const teks = row.textContent.toLowerCase();

            row.style.display =
                teks.includes(keyword) ? "" : "none";
        });
    });
}

initTableFilter();

function tampilkanError(input, pesan) {
    hapusError(input);

    const span = document.createElement("span");

    span.className = "error";
    span.textContent = pesan;

    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        const judul = form.querySelector(
            "[name='judul'], [name='nama']"
        );

        if (judul && judul.value.trim() === "") {
            tampilkanError(
                judul,
                "Field ini wajib diisi."
            );

            valid = false;
        } else if (judul) {
            hapusError(judul);
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

initValidasiForm();
