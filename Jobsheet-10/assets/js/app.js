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
    document.addEventListener("submit", function (e) {
        const form = e.target;

        if (!form.matches("form.form-hapus")) {
            return;
        }

        const yakin = window.confirm(
            "Yakin ingin menghapus data ini?"
        );

        if (!yakin) {
            e.preventDefault();
        }
    });
}

initHapusConfirm();



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

