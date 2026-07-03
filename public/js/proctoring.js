/**
 * CORE PROCTORING AI - METHOD: SSD MOBILENET V1 & FACIAL LANDMARKS (68 POINTS)
 * PENELITIAN SKRIPSI - ITN MALANG 2026
 * * UPDATE: Menampilkan Matriks Piksel Input & Koordinat Spasial Real-time ke Console
 * * UPDATE BUKTI: Ditambahkan fitur otomatis capture foto bukti kecurangan (Base64)
 */

// Konfigurasi Threshold (Ambang Batas) - Dapat disesuaikan saat bimbingan/pengujian
const THRESHOLD_MENOLEH = 125.0 // Jarak Euclidean deviasi ujung hidung dalam piksel
const THRESHOLD_MELIRIK = 0.05 // Rasio jarak pupil terhadap sudut mata
const INTERVAL_DETEKSI = 1000 // Jeda minimal kirim log ke server (1 detik) agar database tidak overload

let modelSudahSiap = false
let koordinatKalibrasi = null // Menyimpan titik jangkar posisi ideal awal wajah
let waktuPelanggaranTerakhir = 0

// Mengambil elemen video webcam dari halaman exam.blade.php
const videoElement = document.getElementById('proctor-cam')

// 1. INISIALISASI: Memuat File Weights Model AI dari Folder Publik
async function inisialisasiProctoring () {
    try {
        console.log('Memuat model weights face-api.js...')
        // Mengarah ke folder /public/models sesuai struktur direktori Laravel
        await faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
        await faceapi.nets.faceLandmark68Net.loadFromUri('/models')

        modelSudahSiap = true
        console.log('Model AI MobileNet V1 & Landmarks 68 Berhasil Dimuat.')

        // Aktifkan loop pengawasan kamera
        mulaiPengawasanUjian()
    } catch (error) {
        console.error('Gagal memuat model proctoring AI:', error)
    }
}

// 2. LOOP UTAMA: Pengawasan Wajah Secara Real-time Menggunakan RequestAnimationFrame
function mulaiPengawasanUjian () {
    if (!modelSudahSiap || !videoElement) return

    // Membuat elemen kanvas bayangan (offscreen canvas) untuk mengekstrak piksel gambar grayscale
    const canvasHidden = document.createElement('canvas')
    const ctxHidden = canvasHidden.getContext('2d')
    // Ukuran sampel matriks diperkecil menjadi 3x3 piksel untuk visualisasi perhitungan Bab 4 Skripsi
    canvasHidden.width = 3
    canvasHidden.height = 3

    async function frameLoop () {
        // Pastikan hardware kamera aktif dan sedang memutar frame gambar
        if (videoElement.paused || videoElement.ended) {
            requestAnimationFrame(frameLoop)
            return
        }

        // Jalankan pipeline deteksi face-api.js
        const hasilDeteksi = await faceapi
            .detectSingleFace(
                videoElement,
                new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 })
            )
            .withFaceLandmarks()

        if (hasilDeteksi) {
            const landmarks = hasilDeteksi.landmarks
            const titikKoordinat = landmarks.positions // Array berisi 68 koordinat objek {x, y}

            // =========================================================================
            // Data Input Matriks Piksel GS 3x3
            // =========================================================================
            // Menggambar frame webcam ke kanvas mini berukuran 3x3 piksel
            ctxHidden.drawImage(videoElement, 0, 0, 3, 3)
            const imgData = ctxHidden.getImageData(0, 0, 3, 3).data

            // Konversi nilai warna RGB menjadi 1 Channel Grayscale menggunakan standard luma formula
            let matriksGrayscale = [
                [0, 0, 0],
                [0, 0, 0],
                [0, 0, 0]
            ]

            for (let i = 0; i < 9; i++) {
                let r = imgData[i * 4]
                let g = imgData[i * 4 + 1]
                let b = imgData[i * 4 + 2]
                // Rumus Grayscale Y = 0.299R + 0.587G + 0.114B
                let grayValue = Math.round(0.299 * r + 0.587 * g + 0.114 * b)

                let row = Math.floor(i / 3)
                let col = i % 3
                matriksGrayscale[row][col] = grayValue
            }

            // Menampilkan matriks piksel ke dalam console log browser secara real-time
            console.log(
                'Matriks Piksel Wajah Input (X) [3x3 Grayscale]:',
                JSON.stringify(matriksGrayscale)
            )
            // =========================================================================

            // Jika belum ada data kalibrasi, kunci frame pertama sebagai posisi ideal awal
            if (!koordinatKalibrasi) {
                koordinatKalibrasi = titikKoordinat
                console.log(
                    'Kalibrasi Berhasil! Posisi ideal awal wajah telah terkunci.'
                )
            } else {
                // Analisis pergerakan wajah berdasarkan tolok ukur kalibrasi
                analisisGerakanWajah(titikKoordinat)
            }
        } else {
            // Wajah tidak terdeteksi oleh SSD MobileNet V1 (Peserta meninggalkan meja ujian)
            catatPelanggaranKeServer('Wajah Hilang', 0)
        }

        // Lanjutkan rekursif ke frame webcam berikutnya
        requestAnimationFrame(frameLoop)
    }

    frameLoop()
}

// 3. LOGIKA ANALISIS: Komputasi Geometri Wajah dengan Rumus Jarak Euclidean
function analisisGerakanWajah (titikBaru) {
    /**
     * DETEKSI MENOLEH (Menggunakan Koordinat Ujung Hidung - Titik Landmark 34 / Indeks 33)
     */
    const hidungAwal = koordinatKalibrasi[33]
    const hidungBaru = titikBaru[33]

    // =========================================================================
    // Koordinat Spasial
    // =========================================================================
    console.log(
        `Koordinat Hidung Awal (Kalibrasi): X=${hidungAwal.x.toFixed(
            2
        )}, Y=${hidungAwal.y.toFixed(2)}`
    )
    console.log(
        `Koordinat Hidung Real-time Berjalan: X=${hidungBaru.x.toFixed(
            2
        )}, Y=${hidungBaru.y.toFixed(2)}`
    )
    // =========================================================================

    // Implementasi Rumus Jarak Euclidean
    const jarakEuclideanHidung = Math.sqrt(
        Math.pow(hidungBaru.x - hidungAwal.x, 2) +
            Math.pow(hidungBaru.y - hidungAwal.y, 2)
    )

    console.log(
        `Jarak Spasial Deviasi Pergeseran (Euclidean): ${jarakEuclideanHidung.toFixed(
            2
        )} piksel`
    )

    if (jarakEuclideanHidung > THRESHOLD_MENOLEH) {
        catatPelanggaranKeServer('Menoleh', jarakEuclideanHidung)
        return // Prioritaskan deteksi menoleh sebelum memeriksa lirikan mata
    }

    /**
     * DETEKSI MELIRIK (Menggunakan Rasio Geometri Mata Kiri)
     * Titik Landmark Mata Kiri berada pada rentang indeks 36 sampai 41
     */
    const mataKiriKiri = titikBaru[36] // Sudut luar mata kiri
    const mataKiriKanan = titikBaru[39] // Sudut dalam mata kiri
    const pupilKiri = titikBaru[37] // Estimasi titik tengah pupil mata kiri

    // Hitung perbandingan rasio jarak horizontal pupil terhadap lebar sudut mata
    const jarakKiriKeluar = Math.abs(pupilKiri.x - mataKiriKiri.x)
    const lebarMataKiri = Math.abs(mataKiriKanan.x - mataKiriKiri.x)
    const rasioMelirik = jarakKiriKeluar / lebarMataKiri

    console.log(
        `Koordinat Mata Kiri - Sudut Luar: ${mataKiriKiri.x.toFixed(
            2
        )}, Pupil: ${pupilKiri.x.toFixed(
            2
        )}, Sudut Dalam: ${mataKiriKanan.x.toFixed(2)}`
    )
    console.log(`Rasio Lirikan Mata Kiri: ${rasioMelirik.toFixed(4)}`)

    // Jika rasio bergeser ekstrem mendekati sudut mata (Melirik ke kanan/kiri tanpa menolehkan kepala)
    if (
        rasioMelirik < THRESHOLD_MELIRIK ||
        rasioMelirik > 1 - THRESHOLD_MELIRIK
    ) {
        catatPelanggaranKeServer('Melirik', jarakEuclideanHidung)
    }
}

// 4. AJAX FETCH API: Mengirimkan Payload Data Pelanggaran & Bukti Foto ke Backend Laravel
function catatPelanggaranKeServer (jenisPelanggaran, skorJarak) {
    const waktuSekarang = Date.now()

    // Throttle System: Mencegah banjir/spam data insert ke MySQL dalam waktu milidetik
    if (waktuSekarang - waktuPelanggaranTerakhir < INTERVAL_DETEKSI) {
        return
    }

    waktuPelanggaranTerakhir = waktuSekarang
    console.warn(
        `[TERDETEKSI KECURANGAN]: ${jenisPelanggaran} (Skor Jarak: ${skorJarak.toFixed(
            2
        )})`
    )

    // =========================================================================
    // PROSES CAPTURE FOTO OTOMATIS SEBAGAI BUKTI
    // =========================================================================
    let buktiFotoBase64 = null
    if (videoElement && !videoElement.paused && !videoElement.ended) {
        try {
            // Membuat kanvas offscreen sementara berukuran sesuai resolusi webcam
            const canvasCapture = document.createElement('canvas')
            canvasCapture.width = videoElement.videoWidth || 640
            canvasCapture.height = videoElement.videoHeight || 480

            const ctxCapture = canvasCapture.getContext('2d')
            // Ambil frame video berjalan detik ini ke kanvas
            ctxCapture.drawImage(
                videoElement,
                0,
                0,
                canvasCapture.width,
                canvasCapture.height
            )

            // Konversi kanvas gambar menjadi string format Base64 JPEG dengan kompresi kualitas 0.7
            buktiFotoBase64 = canvasCapture.toDataURL('image/jpeg', 0.7)
        } catch (e) {
            console.error(
                'Gagal mengambil snapshot gambar bukti dari webcam:',
                e
            )
        }
    }
    // =========================================================================

    // Mengambil token keamanan CSRF dari meta tag layout/peserta.blade.php
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content')

    // Kirim request POST asinkron ke endpoint Laravel dengan menyertakan bukti foto
    fetch('/exam/violation-log', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json'
        },
        body: JSON.stringify({
            violation_type: jenisPelanggaran,
            euclidean_score: skorJarak,
            violation_image: buktiFotoBase64 // Menyertakan payload data gambar string Base64
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Respon Database Laravel:', data.message)
        })
        .catch(error => {
            console.error('Gagal mengirimkan log kecurangan ke server:', error)
        })
}

function hentikanProctoring () {
    // 1. Hentikan loop AI
    modelSudahSiap = false

    // 2. Matikan hardware webcam
    if (videoElement && videoElement.srcObject) {
        const stream = videoElement.srcObject
        const tracks = stream.getTracks()
        tracks.forEach(track => track.stop()) // Kamera mati
        console.log('Kamera dan pengawasan AI dihentikan.')
    }
}

// Menjalankan sistem pendeteksi AI sesaat setelah seluruh elemen DOM selesai dimuat
window.addEventListener('DOMContentLoaded', () => {
    // Beri jeda 2 detik agar hardware kamera internal laptop stabil terlebih dahulu
    setTimeout(inisialisasiProctoring, 2000)
})
