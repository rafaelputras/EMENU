<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col_md-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white p-3 rounded-top-3">
                        <h4 class="mb-0 fw-bold">Tambah Kategori Resto</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASEURL ?>/public/admin/addCategory" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nama Kategori</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Contoh: Makanan Utama, Minuman, Dessert" required autofocus>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="<?= BASEURL ?>/public/admin/categories" class="btn btn-outline-secondary px-4">Kembali</a>
                                <button type="submit" class="btn btn-primary px-4 btn-lg">Simpan Kategori</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>