<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "crud");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$error = "";
$sukses = "";
$nim = "";
$nama = "";
$alamat = "";
$fakultas = "";

// Cek jika ada operasi (edit atau delete)
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// DELETE DATA
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM mahasiswa WHERE id = $id";
    $query = mysqli_query($koneksi, $sql);
    if ($query) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Data gagal dihapus";
    }
}

// EDIT DATA
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql = "SELECT * FROM mahasiswa WHERE id = $id";
    $query = mysqli_query($koneksi, $sql);
    $data = mysqli_fetch_array($query);
    if ($data) {
        $nim = $data['nim'];
        $nama = $data['nama'];
        $alamat = $data['alamat'];
        $fakultas = $data['fakultas'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// SIMPAN DATA
if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $fakultas = $_POST['fakultas'];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') {
            // Update data
            $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', alamat='$alamat', fakultas='$fakultas' WHERE id=$id";
            $query = mysqli_query($koneksi, $sql);
            if ($query) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            // Insert data baru
            $sql = "INSERT INTO mahasiswa (nim, nama, alamat, fakultas) VALUES ('$nim', '$nama', '$alamat', '$fakultas')";
            $query = mysqli_query($koneksi, $sql);
            if ($query) {
                $sukses = "Data berhasil disimpan";
            } else {
                $error = "Gagal menyimpan data";
            }
        }
    } else {
        $error = "Silakan isi semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - Modern CRUD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-in': 'bounceIn 0.6s ease-out'
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #00C3D1FF 0%, #088F99FF 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center animate-bounce-in">
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Sistem Manajemen Mahasiswa
                </h1>
                <p class="text-white opacity-90">Kelola data mahasiswa dengan mudah dan efisien</p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 pb-10">
        <!-- Alert Messages -->
        <?php if ($error) { ?>
            <div class="glass-effect border-l-4 border-red-500 p-4 mb-6 rounded-r-lg animate-slide-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-700 font-medium"><?php echo $error ?></p>
                    </div>
                </div>
            </div>
            <?php header("refresh:5;url=index.php"); ?>
        <?php } ?>

        <?php if ($sukses) { ?>
            <div class="glass-effect border-l-4 border-green-500 p-4 mb-6 rounded-r-lg animate-slide-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700 font-medium"><?php echo $sukses ?></p>
                    </div>
                </div>
            </div>
            <?php header("refresh:5;url=index.php"); ?>
        <?php } ?>

        <!-- Form Input -->
        <div class="glass-effect card-shadow rounded-2xl p-8 mb-8 animate-fade-in">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        <?php echo ($op == 'edit') ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa'; ?>
                    </h2>
                    <p class="text-gray-600">Lengkapi formulir di bawah ini</p>
                </div>
            </div>

            <form action="" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-blue-500"></i>NIM
                        </label>
                        <input type="text" id="nim" name="nim" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all duration-300 group-hover:border-gray-300" 
                               value="<?php echo $nim ?>" placeholder="Masukkan NIM">
                    </div>
                    <div class="group">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-green-500"></i>Nama Lengkap
                        </label>
                        <input type="text" id="nama" name="nama" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-200 transition-all duration-300 group-hover:border-gray-300" 
                               value="<?php echo $nama ?>" placeholder="Masukkan nama lengkap">
                    </div>
                </div>
                
                <div class="group">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>Alamat
                    </label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition-all duration-300 group-hover:border-gray-300 resize-none" 
                              placeholder="Masukkan alamat lengkap"><?php echo $alamat ?></textarea>
                </div>
                
                <div class="group">
                    <label for="fakultas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-university mr-2 text-purple-500"></i>Fakultas
                    </label>
                    <select id="fakultas" name="fakultas" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all duration-300 group-hover:border-gray-300">
                        <option value="">- Pilih Fakultas -</option>
                        <option value="saintek" <?php if ($fakultas == "saintek") echo "selected" ?>>Sains dan Teknologi</option>
                        <option value="soshum" <?php if ($fakultas == "soshum") echo "selected" ?>>Sosial dan Humaniora</option>
                    </select>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" name="simpan" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        <?php echo ($op == 'edit') ? 'Update Data' : 'Simpan Data'; ?>
                    </button>
                    <?php if ($op == 'edit') { ?>
                        <a href="index.php" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 text-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    <?php } ?>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="glass-effect card-shadow rounded-2xl overflow-hidden animate-fade-in">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-table text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Data Mahasiswa</h2>
                            <p class="text-gray-600">Daftar semua mahasiswa terdaftar</p>
                        </div>
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold">
                        <?php 
                        $count_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mahasiswa");
                        $count_data = mysqli_fetch_array($count_query);
                        echo $count_data['total'] . ' Total Data';
                        ?>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-id-card mr-2"></i>NIM
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Nama
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-university mr-2"></i>Fakultas
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $sql2   = "SELECT * FROM mahasiswa ORDER BY id DESC";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        $no_data = mysqli_num_rows($q2) == 0;
                        
                        if ($no_data) {
                        ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-500 mb-2">Belum Ada Data</h3>
                                        <p class="text-gray-400">Tambahkan data mahasiswa pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        } else {
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $id         = $r2['id'];
                                $nim        = $r2['nim'];
                                $nama       = $r2['nama'];
                                $alamat     = $r2['alamat'];
                                $fakultas   = $r2['fakultas'];
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                        <?php echo $urut++ ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $nim ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">
                                                <?php echo strtoupper(substr($nama, 0, 2)); ?>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo $nama ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate"><?php echo $alamat ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?php echo ($fakultas == 'saintek') ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'; ?>">
                                        <?php echo ucfirst($fakultas) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="index.php?op=edit&id=<?php echo $id ?>" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-all duration-200 transform hover:scale-110"
                                           title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?op=delete&id=<?php echo $id ?>" 
                                           onclick="return confirm('⚠️ Yakin ingin menghapus data ini?')" 
                                           class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-200 transform hover:scale-110"
                                           title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-6 text-white opacity-75">
        <p>&copy; 2024 Sistem Manajemen Mahasiswa. Dibuat dengan ❤️</p>
    </footer>
</body>

</html>