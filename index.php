<?php
//koneksi
$koneksi = mysqli_connect("localhost", "root", "", "crud");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_close($koneksi);


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
    <title>Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-10">
        <!-- Form Input -->
        <div class="bg-white shadow-md rounded p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Create / Edit Data</h2>

            <?php if ($error) { ?>
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <?php echo $error ?>
                </div>
                <?php header("refresh:5;url=index.php"); ?>
            <?php } ?>

            <?php if ($sukses) { ?>
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    <?php echo $sukses ?>
                </div>
                <?php header("refresh:5;url=index.php"); ?>
            <?php } ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                    <input type="text" id="nim" name="nim" class="mt-1 block w-full border border-gray-300 rounded p-2" value="<?php echo $nim ?>">
                </div>
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="nama" name="nama" class="mt-1 block w-full border border-gray-300 rounded p-2" value="<?php echo $nama ?>">
                </div>
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="mt-1 block w-full border border-gray-300 rounded p-2" value="<?php echo $alamat ?>">
                </div>
                <div class="mb-4">
                    <label for="fakultas" class="block text-sm font-medium text-gray-700">Fakultas</label>
                    <select id="fakultas" name="fakultas" class="mt-1 block w-full border border-gray-300 rounded p-2">
                        <option value="">- Pilih Fakultas -</option>
                        <option value="saintek" <?php if ($fakultas == "saintek") echo "selected" ?>>saintek</option>
                        <option value="soshum" <?php if ($fakultas == "soshum") echo "selected" ?>>soshum</option>
                    </select>
                </div>
                <div>
                    <input type="submit" name="simpan" value="Simpan Data" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white shadow-md rounded p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Data Mahasiswa</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-left">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">NIM</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Alamat</th>
                            <th class="px-4 py-2">Fakultas</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $koneksi = mysqli_connect("localhost", "root", "", "crud"); // Reconnect to the database
                        $sql2 = "SELECT * FROM mahasiswa ORDER BY id DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nim = $r2['nim'];
                            $nama = $r2['nama'];
                            $alamat = $r2['alamat'];
                            $fakultas = $r2['fakultas'];
                        ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo $urut++ ?></td>
                                <td class="px-4 py-2"><?php echo $nim ?></td>
                                <td class="px-4 py-2"><?php echo $nama ?></td>
                                <td class="px-4 py-2"><?php echo $alamat ?></td>
                                <td class="px-4 py-2"><?php echo $fakultas ?></td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded text-sm">Edit</a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')" class="inline-block bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>