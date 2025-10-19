// Event listener untuk form submission
document.getElementById('dataForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Ambil nilai dari input
    const nama = document.getElementById('nama').value.trim();
    const email = document.getElementById('email').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    
    // Validasi input
    if (nama === '') {
        alert('Silahkan isi nama !');
        return;
    }
    
    if (email === '') {
        alert('Silahkan isi email !');
        return;
    }
    
    // Validasi format email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Format email tidak valid !');
        return;
    }
    
    if (alamat === '') {
        alert('Silahkan isi alamat !');
        return;
    }
    
    // Jika semua validasi berhasil, tampilkan data
    displayData(nama, email, alamat);
});

// Fungsi untuk menampilkan data
function displayData(nama, email, alamat) {
    // Tampilkan container hasil
    document.getElementById('resultContainer').style.display = 'block';
    
    // Isi form display
    document.getElementById('displayNama').value = nama;
    document.getElementById('displayEmail').value = email;
    document.getElementById('displayAlamat').value = alamat;
    
    // Isi tabel
    document.getElementById('tableName').textContent = nama;
    document.getElementById('tableEmail').textContent = email;
    document.getElementById('tableAddress').textContent = alamat;
    
    // Scroll ke hasil
    document.getElementById('resultContainer').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

// Event listener untuk reset button
document.getElementById('dataForm').addEventListener('reset', function() {
    // Sembunyikan container hasil ketika form direset
    document.getElementById('resultContainer').style.display = 'none';
});
