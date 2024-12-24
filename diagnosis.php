<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gejala = isset($_POST['gejala']) ? $_POST['gejala'] : [];

    // Dataset gejala dan penyakit beserta solusinya
    $penyakitData = [
        1 => ['penyakit' => 'Patek', 'gejala' => [1, 2, 3, 4], 'solusi' => 'Penyakit ini disebabkan oleh jamur Phytophthora yang menyerang akar dan batang cabai, menyebabkan tanaman layu dan mati. Untuk pengendalian, pastikan drainase tanah baik dan gunakan fungisida seperti Ridomil Gold atau Metalaxyl untuk mengendalikan infeksi jamur. Hindari penyiraman berlebihan, dan buang tanaman yang terinfeksi untuk mencegah penyebaran lebih lanjut.'],
        2 => ['penyakit' => 'Kirana', 'gejala' => [1, 5, 6, 7], 'solusi' => 'Penyakit ini disebabkan oleh jamur yang mengakibatkan bercak kuning pada daun cabai, mengurangi fotosintesis dan kesehatan tanaman. Pengendalian dapat dilakukan dengan memangkas daun yang terinfeksi dan menggunakan fungisida seperti Kocide (tembaga) atau Dithane M-45 untuk mengendalikan infeksi jamur. Juga, jaga kelembapan tanah agar tidak berlebihan, dan lakukan rotasi tanaman untuk mengurangi risiko serangan.'],
        3 => ['penyakit' => 'Penyakit Kuning', 'gejala' => [1, 8, 9, 10], 'solusi' => 'Penyakit ini disebabkan oleh virus yang ditularkan oleh kutu daun atau thrips, mengakibatkan daun menguning dan menggulung. Untuk pengendalian, pilih varietas cabai yang tahan terhadap virus, seperti cabai jenis IPB C-7. Gunakan insektisida seperti Confidor (imidacloprid) atau Admire untuk mengendalikan vektor penghisap seperti kutu daun dan thrips. Selain itu, buang daun yang terinfeksi dan lakukan rotasi tanaman untuk mengurangi risiko infeksi berulang'],
        4 => ['penyakit' => 'Busuk Batang', 'gejala' => [2, 11, 12, 13], 'solusi' => 'Penyakit busuk batang disebabkan oleh infeksi bakteri atau jamur yang merusak jaringan batang, sering terjadi akibat kelembapan berlebihan. Untuk mengendalikannya, perbaiki drainase tanah dan gunakan fungisida atau bakterisida seperti Copper Hydroxide (Kocide) atau Bordeaux mixture untuk mencegah infeksi. Pangkas bagian batang yang terinfeksi dan pastikan alat pertanian yang digunakan steril untuk mencegah penyebaran penyakit.'],
    ];

    // Probabilitas setiap gejala (untuk perhitungan Naive Bayes)
    $priorProbabilities = [
        'Patek' => 0.30,
        'Kirana' => 0.25,
        'Penyakit Kuning' => 0.20,
        'Busuk Batang' => 0.25,
       
    ];

    // Inisialisasi variabel untuk menghitung probabilitas untuk setiap penyakit
    $probabilities = [];

    foreach ($penyakitData as $key => $data) {
        $penyakit = $data['penyakit'];
        $gejalaPenyakit = $data['gejala'];

        // Menghitung probabilitas gejala yang cocok dengan penyakit ini
        $prob = $priorProbabilities[$penyakit];
        $matchCount = 0;
        foreach ($gejala as $g) {
            if (in_array($g, $gejalaPenyakit)) {
                $matchCount++;
            }
        }
        $prob *= $matchCount / count($gejalaPenyakit); // Naive Bayes simplifikasi

        $probabilities[$penyakit] = $prob;
    }

    // Menentukan penyakit dengan probabilitas tertinggi
    arsort($probabilities);
    $result = key($probabilities);  // Penyakit dengan probabilitas tertinggi
    $maxProb = current($probabilities); // Probabilitas tertinggi

    // Mendapatkan solusi untuk penyakit yang terdiagnosa
    $solusi = "Solusi tidak ditemukan.";
    foreach ($penyakitData as $key => $data) {
        if ($data['penyakit'] == $result) {
            $solusi = $data['solusi'];
            break; // Menghentikan pencarian setelah ditemukan
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa Penyakit Tanaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Hasil Diagnosa</h1>
        <h2>Penyakit yang Teridentifikasi: <?= $result ?></h2>
        <p>Probabilitas: <?= number_format($maxProb, 2) ?></p>
        <div class="solution">
            <h3>Solusi:</h3>
            <p><?= $solusi ?></p>
        </div>
        <br>
        <a href="diagnosa.php" class="btn">Kembali</a>
    </div>
</body>
</html>
