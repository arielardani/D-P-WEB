const tabelAnggota = document.getElementById("tabel-anggota");
const loadingAnggota = document.getElementById("loading-anggota");

let dataAnggota = [];


// ========================================
// AMBIL DATA ANGGOTA DARI JSON
// ========================================

async function loadAnggota() {

    try {

        loadingAnggota.style.display = "block";

        const response = await fetch("../data/anggota.json");

        if (!response.ok) {
            throw new Error(
                "Gagal mengambil data (status " +
                response.status +
                ")"
            );
        }

        dataAnggota = await response.json();

        setTimeout(function () {

            renderAnggota(dataAnggota);

            loadingAnggota.style.display = "none";

        }, 600);

    } catch (error) {

        loadingAnggota.textContent =
            "Gagal memuat data: " +
            error.message;
    }
}


// ========================================
// MENAMPILKAN DATA ANGGOTA
// ========================================

function renderAnggota(data) {

    tabelAnggota.innerHTML = "";

    data.forEach(function (anggota) {

        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${anggota.id}</td>
            <td>${anggota.nama}</td>
            <td>${anggota.email}</td>
            <td>
                <button type="button">
                    Edit
                </button>

                <button
                    type="button"
                    class="btn-hapus"
                    data-id="${anggota.id}"
                >
                    Hapus
                </button>
            </td>
        `;

        tabelAnggota.appendChild(row);
    });
}


// ========================================
// FITUR PENCARIAN
// ========================================

const searchInput =
    document.getElementById("search-input");

searchInput.addEventListener("keyup", function () {

    const keyword =
        searchInput.value.toLowerCase().trim();

    const hasil =
        dataAnggota.filter(function (anggota) {

            return (
                anggota.nama
                    .toLowerCase()
                    .includes(keyword)

                ||

                anggota.email
                    .toLowerCase()
                    .includes(keyword)
            );

        });

    renderAnggota(hasil);
});


// ========================================
// FITUR HAPUS
// ========================================

tabelAnggota.addEventListener("click", function (event) {

    if (!event.target.classList.contains("btn-hapus")) {
        return;
    }

    const row =
        event.target.closest("tr");

    const nama =
        row.querySelector("td:nth-child(2)")?.textContent;

    const yakin =
        confirm(
            'Yakin ingin menghapus "' +
            nama +
            '"?'
        );

    if (yakin) {
        row.remove();
    }
});


// ========================================
// JALANKAN PROGRAM
// ========================================

loadAnggota();