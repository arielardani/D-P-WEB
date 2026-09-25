const tabelBuku = document.getElementById("tabel-buku");
const loadingBuku = document.getElementById("loading-buku");

let dataBuku = [];


// ========================================
// AMBIL DATA BUKU DARI JSON
// ========================================

async function loadBuku() {

    try {

        loadingBuku.style.display = "block";

        const response = await fetch("../data/buku.json");

        if (!response.ok) {
            throw new Error(
                "Gagal mengambil data (status " +
                response.status +
                ")"
            );
        }

        dataBuku = await response.json();

        setTimeout(function () {

            renderBuku(dataBuku);

            loadingBuku.style.display = "none";

        }, 600);

    } catch (error) {

        loadingBuku.textContent =
            "Gagal memuat data: " +
            error.message;
    }
}


// ========================================
// MENAMPILKAN DATA BUKU
// ========================================

function renderBuku(data) {

    tabelBuku.innerHTML = "";

    data.forEach(function (buku) {

        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${buku.judul}</td>
            <td>${buku.penulis}</td>
            <td>${buku.tahun}</td>
            <td>${buku.stok}</td>
            <td>
                <button type="button">
                    Edit
                </button>

                <button
                    type="button"
                    class="btn-hapus"
                    data-id="${buku.id}"
                >
                    Hapus
                </button>
            </td>
        `;

        tabelBuku.appendChild(row);
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
        dataBuku.filter(function (buku) {

            return (
                buku.judul
                    .toLowerCase()
                    .includes(keyword)

                ||

                buku.penulis
                    .toLowerCase()
                    .includes(keyword)
            );

        });

    renderBuku(hasil);
});


// ========================================
// FITUR HAPUS
// ========================================

tabelBuku.addEventListener("click", function (event) {

    if (!event.target.classList.contains("btn-hapus")) {
        return;
    }

    const row =
        event.target.closest("tr");

    const nama =
        row.querySelector("td")?.textContent;

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

loadBuku();