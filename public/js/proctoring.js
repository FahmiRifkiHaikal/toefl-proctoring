/**
 * CORE PROCTORING & AUTHENTICATION AI - METHOD: SSD MOBILENET V1, LANDMARKS & RECOGNITION NET
 * PENELITIAN SKRIPSI - ITN MALANG 2026
 */

// Konfigurasi Threshold (Ambang Batas)
const THRESHOLD_MENOLEH = 125.0 // Jarak Euclidean deviasi ujung hidung dalam piksel
const THRESHOLD_MELIRIK = 0.05 // Rasio jarak pupil terhadap sudut mata
const INTERVAL_DETEKSI = 1000 // Jeda minimal kirim log ke server (1 detik)

// Konfigurasi Batas Toleransi Pelanggaran (Tambahan Baru)
let violationCount = 0
const MAX_VIOLATIONS = 5

let modelSudahSiap = false
let koordinatKalibrasi = null
let waktuPelanggaranTerakhir = 0
let userFaceMatcher = null // Menyimpan pencocok wajah yang telah dikalibrasi database

// Mengambil elemen video berdasarkan halaman aktif
const videoElement = document.getElementById('proctor-cam') // Di halaman Ujian
const registerVideoElement = document.getElementById('webcam-register') // Di halaman Registrasi

// =========================================================================
// 1. INISIALISASI MODEL UTAMA
// =========================================================================
async function inisialisasiSistemAI () {
    try {
        console.log('Memuat model weights face-api.js...')
        const MODEL_URL = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights' // Pastikan folder ini ada di public/models

        // Memuat model satu per satu
        await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL)
        console.log('Model SsdMobilenetv1 Berhasil Dimuat.')

        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL)
        console.log('Model Landmarks Berhasil Dimuat.')

        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        console.log('Model Rekognisi Berhasil Dimuat.')

        modelSudahSiap = true
        console.log('Seluruh Model AI Berhasil Dimuat.')

        // Cek halaman aktif untuk menyalakan kamera
        if (registerVideoElement) {
            console.log('Menjalankan kamera registrasi...')
            inisialisasiKameraRegistrasi()
        } else if (videoElement) {
            inisialisasiUjianDenganVerifikasi()
        }
    } catch (error) {
        console.error('Gagal memuat model AI:', error)
        alert(
            'Eror Muat Model AI: ' +
                error.message +
                '\nPeriksa folder public/models Anda!'
        )

        // Cadangan: Jika model macet tapi kamera ingin dipaksa menyala untuk tes layout
        if (registerVideoElement) {
            document.getElementById('status-perekaman').innerText =
                'Gagal memuat model AI dari server.'
            document
                .getElementById('status-perekaman')
                .classList.replace('text-blue-600', 'text-red-600')
        }
    }
}

// =========================================================================
// 2. LOGIKA HALAMAN REGISTRASI (FACE ENROLLMENT)
// =========================================================================
let mediaStreamRegistrasi = null

async function inisialisasiKameraRegistrasi () {
    const statusText = document.getElementById('status-perekaman')
    const captureBtn = document.getElementById('btn-capture-face')

    try {
        mediaStreamRegistrasi = await navigator.mediaDevices.getUserMedia({
            video: {}
        })
        registerVideoElement.srcObject = mediaStreamRegistrasi

        statusText.innerText = 'Mendeteksi wajah Anda...'
        statusText.classList.remove('text-blue-600')
        statusText.classList.add('text-yellow-600')

        // Loop pengecekan kualitas deteksi wajah sebelum tombol capture aktif
        const intervalCheck = setInterval(async () => {
            if (!modelSudahSiap || registerVideoElement.paused) return

            const detection = await faceapi
                .detectSingleFace(
                    registerVideoElement,
                    new faceapi.SsdMobilenetv1Options({ minConfidence: 0.6 })
                )
                .withFaceLandmarks()
                .withFaceDescriptor()

            if (detection) {
                statusText.innerText =
                    'Wajah terdeteksi dengan baik! Silakan klik tombol di bawah.'
                statusText.classList.remove('text-yellow-600')
                statusText.classList.add('text-green-600')
                captureBtn.disabled = false
                captureBtn.classList.remove('bg-gray-400', 'cursor-not-allowed')
                captureBtn.classList.add('bg-blue-600', 'hover:bg-blue-700')
            } else {
                statusText.innerText =
                    'Wajah tidak terdeteksi. Silakan posisikan wajah ke tengah.'
                statusText.classList.remove('text-green-600')
                statusText.classList.add('text-yellow-600')
                captureBtn.disabled = true
                captureBtn.classList.add('bg-gray-400', 'cursor-not-allowed')
                captureBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700')
            }
        }, 1000)

        // Aksi ketika tombol "Ambil Sidik Wajah" diklik
        captureBtn.onclick = async () => {
            clearInterval(intervalCheck)
            statusText.innerText = 'Mengekstrak ciri wajah...'

            const detection = await faceapi
                .detectSingleFace(registerVideoElement)
                .withFaceLandmarks()
                .withFaceDescriptor()

            if (detection) {
                const faceDescriptorString = JSON.stringify(
                    Array.from(detection.descriptor)
                )

                // Matikan kamera registrasi
                if (mediaStreamRegistrasi) {
                    mediaStreamRegistrasi
                        .getTracks()
                        .forEach(track => track.stop())
                }

                statusText.innerText = 'Proses pendaftaran akun...'
                kirimPendaftaranKeBackend(faceDescriptorString)
            } else {
                alert('Gagal merekam wajah, silakan coba lagi.')
                inisialisasiKameraRegistrasi()
            }
        }
    } catch (err) {
        console.error('Gagal mengakses webcam:', err)
        statusText.innerText = 'Akses webcam ditolak atau tidak ditemukan!'
    }
}

function kirimPendaftaranKeBackend (faceVectorString) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content')

    const payload = {
        name: document.getElementById('reg-name').value,
        email: document.getElementById('reg-email').value,
        password: document.getElementById('reg-password').value,
        face_vector: faceVectorString
    }

    fetch('/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json'
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(
                    'Registrasi Berhasil! Data akun dan sidik wajah Anda sudah terdaftar.'
                )
                window.location.href = '/login' // Redirect ke halaman login
            } else {
                alert('Gagal: ' + JSON.stringify(data.errors || data.message))
                window.location.reload()
            }
        })
        .catch(error => {
            console.error('Error pendaftaran:', error)
            alert('Terjadi kesalahan koneksi server.')
        })
}

// =========================================================================
// 3. LOGIKA HALAMAN UJIAN (VERIFIKASI WAJAH & PENGAWASAN REAL-TIME)
// =========================================================================
async function inisialisasiUjianDenganVerifikasi () {
    if (
        typeof userFaceVectorFromDatabase === 'undefined' ||
        !userFaceVectorFromDatabase
    ) {
        console.error('Data face_vector user tidak ditemukan di halaman ini.')
        return
    }

    try {
        // Rekonstruksi array 128 desimal dari database menjadi objek Float32Array
        const dbVektorArray = new Float32Array(
            JSON.parse(userFaceVectorFromDatabase)
        )
        const labeledDescriptors = new faceapi.LabeledFaceDescriptors(
            loggedUsername,
            [dbVektorArray]
        )

        // Buat objek pencocok wajah dengan ambang toleransi kemiripan 0.6
        userFaceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6)
        console.log('Pencocok wajah ujian berhasil disiapkan.')

        // Jalankan pengawasan utama
        mulaiPengawasanUjian()
    } catch (e) {
        console.error('Gagal melakukan inisialisasi pencocokan wajah ujian:', e)
    }
}

function mulaiPengawasanUjian () {
    if (!modelSudahSiap || !videoElement) return

    const canvasHidden = document.createElement('canvas')
    const ctxHidden = canvasHidden.getContext('2d')
    canvasHidden.width = 3
    canvasHidden.height = 3

    async function frameLoop () {
        // Jika sistem di-terminate atau dihentikan, putuskan perulangan animasi frame
        if (!modelSudahSiap) return

        if (videoElement.paused || videoElement.ended) {
            requestAnimationFrame(frameLoop)
            return
        }

        // Deteksi wajah tunggal beserta landmarks dan deskriptor live-nya
        const hasilDeteksi = await faceapi
            .detectSingleFace(
                videoElement,
                new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 })
            )
            .withFaceLandmarks()
            .withFaceDescriptor() // <-- Ambil deskriptor wajah live

        if (hasilDeteksi) {
            // PROSES VERIFIKASI IDENTITAS (RECOGNITION)
            if (userFaceMatcher) {
                const matchResult = userFaceMatcher.findBestMatch(
                    hasilDeteksi.descriptor
                )
                if (matchResult.label === 'unknown') {
                    console.warn(
                        '[WARNING]: Wajah di depan kamera tidak cocok dengan peserta terdaftar!'
                    )
                    catatPelanggaranKeServer('Wajah Berbeda (Calo)', 1.0)
                }
            }

            const landmarks = hasilDeteksi.landmarks
            const titikKoordinat = landmarks.positions

            // Matriks Piksel GS 3x3
            ctxHidden.drawImage(videoElement, 0, 0, 3, 3)
            const imgData = ctxHidden.getImageData(0, 0, 3, 3).data
            let matriksGrayscale = [
                [0, 0, 0],
                [0, 0, 0],
                [0, 0, 0]
            ]

            for (let i = 0; i < 9; i++) {
                let r = imgData[i * 4]
                let g = imgData[i * 4 + 1]
                let b = imgData[i * 4 + 2]
                let grayValue = Math.round(0.299 * r + 0.587 * g + 0.114 * b)
                let row = Math.floor(i / 3)
                let col = i % 3
                matriksGrayscale[row][col] = grayValue
            }

            console.log(
                'Matriks Piksel Wajah Input (X) [3x3 Grayscale]:',
                JSON.stringify(matriksGrayscale)
            )

            if (!koordinatKalibrasi) {
                koordinatKalibrasi = titikKoordinat
                console.log(
                    'Kalibrasi Berhasil! Posisi ideal awal wajah telah terkunci.'
                )
            } else {
                analisisGerakanWajah(titikKoordinat)
            }
        } else {
            catatPelanggaranKeServer('Wajah Hilang', 0)
        }

        requestAnimationFrame(frameLoop)
    }

    frameLoop()
}

// 4. LOGIKA ANALISIS: Komputasi Geometri Wajah dengan Rumus Jarak Euclidean
function analisisGerakanWajah (titikBaru) {
    const hidungAwal = koordinatKalibrasi[33]
    const hidungBaru = titikBaru[33]

    const jarakEuclideanHidung = Math.sqrt(
        Math.pow(hidungBaru.x - hidungAwal.x, 2) +
            Math.pow(hidungBaru.y - hidungAwal.y, 2)
    )

    if (jarakEuclideanHidung > THRESHOLD_MENOLEH) {
        catatPelanggaranKeServer('Menoleh', jarakEuclideanHidung)
        return
    }

    const mataKiriKiri = titikBaru[36]
    const mataKiriKanan = titikBaru[39]
    const pupilKiri = titikBaru[37]

    const jarakKiriKeluar = Math.abs(pupilKiri.x - mataKiriKiri.x)
    const lebarMataKiri = Math.abs(mataKiriKanan.x - mataKiriKiri.x)
    const rasioMelirik = jarakKiriKeluar / lebarMataKiri

    if (
        rasioMelirik < THRESHOLD_MELIRIK ||
        rasioMelirik > 1 - THRESHOLD_MELIRIK
    ) {
        catatPelanggaranKeServer('Melirik', jarakEuclideanHidung)
    }
}

// 5. AJAX FETCH API: Mengirimkan Payload Data Pelanggaran & Bukti Foto ke Backend Laravel
function catatPelanggaranKeServer (jenisPelanggaran, skorJarak) {
    const waktuSekarang = Date.now()

    if (waktuSekarang - waktuPelanggaranTerakhir < INTERVAL_DETEKSI) {
        return
    }

    waktuPelanggaranTerakhir = waktuSekarang

    // Increment jumlah total pelanggaran berjalan
    violationCount++

    console.warn(
        `[TERDETEKSI KECURANGAN]: ${jenisPelanggaran} (${violationCount}/${MAX_VIOLATIONS})`
    )

    // Tampilkan notifikasi peringatan di layar web peserta
    tampilkanNotifikasiPeserta(jenisPelanggaran)

    let buktiFotoBase64 = null
    if (videoElement && !videoElement.paused && !videoElement.ended) {
        try {
            const canvasCapture = document.createElement('canvas')
            canvasCapture.width = videoElement.videoWidth || 640
            canvasCapture.height = videoElement.videoHeight || 480
            const ctxCapture = canvasCapture.getContext('2d')
            ctxCapture.drawImage(
                videoElement,
                0,
                0,
                canvasCapture.width,
                canvasCapture.height
            )
            buktiFotoBase64 = canvasCapture.toDataURL('image/jpeg', 0.7)
        } catch (e) {
            console.error(
                'Gagal mengambil snapshot gambar bukti dari webcam:',
                e
            )
        }
    }

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content')

    // Kirim log pelanggaran ke Laravel Backend
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
            violation_image: buktiFotoBase64,
            current_violation_count: violationCount
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Respon Database Laravel:', data.message)

            // Cek jika counter sudah menyentuh batas maksimal 5 kali
            if (violationCount >= MAX_VIOLATIONS) {
                terminateExam()
            }
        })
        .catch(error => {
            console.error('Gagal mengirimkan log kecurangan ke server:', error)
            if (violationCount >= MAX_VIOLATIONS) {
                terminateExam()
            }
        })
}

// 6. LOGIKA PENGHENTIAN PAKSA UJIAN (TERMINATED / TERMINASI AI)
function terminateExam () {
    hentikanProctoring()

    document.body.innerHTML = `
        <div class="fixed inset-0 bg-slate-950 flex items-center justify-center text-white text-center p-6 font-sans z-[99999]">
            <div class="max-w-md space-y-5 animate-fadeIn">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500/10 rounded-[26px] text-red-500 text-5xl border border-red-500/20 shadow-xl shadow-red-500/5">
                    <i class="fa-solid fa-circle-xmark animate-pulse"></i>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-100">UJIAN DIHENTIKAN!</h1>
                    <p class="text-xs font-bold text-red-500 tracking-widest uppercase">Batas Toleransi Kecurangan Terlampaui</p>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Sistem AI Proctoring mendeteksi Anda telah melakukan tindakan di luar aturan sebanyak <span class="text-red-400 font-bold">${MAX_VIOLATIONS} kali</span>. Sesi ujian Anda otomatis dibekukan.
                </p>
                <div class="pt-2">
                    <p class="text-xs text-slate-500 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-spinner animate-spin"></i> Mengalihkan ke halaman riwayat dan pengulangan...
                    </p>
                </div>
            </div>
        </div>
    `

    // Redirect dialihkan ke halaman summary peserta (sesuaikan dengan route kamu)
    setTimeout(() => {
        window.location.href = '/peserta/exam-summary'
    }, 3500)
}

// =========================================================================
// INISIALISASI BERDASARKAN STRUKTUR HALAMAN BLADE
// =========================================================================
window.addEventListener('DOMContentLoaded', () => {
    // 1. Cek apakah berada di auth/registrasi.blade.php
    // Pastikan tag <form> atau div utama di registrasi.blade.php memiliki id="form-registrasi"
    const onRegisterPage = document.getElementById('form-registrasi') !== null

    // 2. Cek apakah berada di peserta/exam.blade.php
    // Cukup cek apakah elemen <video id="proctor-cam"> ada di halaman tersebut
    const onExamPage = document.getElementById('proctor-cam') !== null

    if (onRegisterPage) {
        console.log('Sistem mendeteksi halaman: Registrasi Peserta')
        inisialisasiSistemAI()
    } else if (onExamPage) {
        console.log(
            'Sistem mendeteksi halaman: Ujian TOEFL Peserta (Jeda 2 detik untuk stabilisasi)'
        )
        setTimeout(inisialisasiSistemAI, 2000)
    }
})

function hentikanProctoring () {
    modelSudahSiap = false
    if (videoElement && videoElement.srcObject) {
        const stream = videoElement.srcObject
        const tracks = stream.getTracks()
        tracks.forEach(track => track.stop())
        console.log('Kamera dan pengawasan AI dihentikan.')
    }
}

// Fungsi Menampilkan Notifikasi Peringatan di Sisi Peserta
function tampilkanNotifikasiPeserta (jenisPelanggaran) {
    const warningBox = document.getElementById('proctor-warning-box')
    const warningMessage = document.getElementById('warning-message')

    if (!warningBox) return

    let pesan = ''
    switch (jenisPelanggaran) {
        case 'Menoleh':
            pesan = `Peringatan (${violationCount}/${MAX_VIOLATIONS})! Kepala Anda menoleh. Harap tetap fokus ke layar.`
            break
        case 'Melirik':
            pesan = `Peringatan (${violationCount}/${MAX_VIOLATIONS})! Mata Anda melirik keluar. Fokus pada soal ujian.`
            break
        case 'Wajah Hilang':
            pesan = `Peringatan (${violationCount}/${MAX_VIOLATIONS})! Wajah tidak terdeteksi. Posisikan diri di depan webcam.`
            break
        case 'Wajah Berbeda (Calo)':
            pesan = `Peringatan Keras (${violationCount}/${MAX_VIOLATIONS})! Identitas wajah tidak sesuai dengan peserta ujian!`
            break
        default:
            pesan = `Aktivitas mencurigakan terdeteksi (${violationCount}/${MAX_VIOLATIONS}).`
    }

    warningMessage.innerText = pesan

    // Putar suara peringatan (Beep Audio API bawaan Browser)
    putarSuaraPeringatan()

    // Jalankan efek animasi slide-in Tailwind
    warningBox.classList.remove('hidden', 'translate-x-full')
    warningBox.classList.add('translate-x-0')

    // Sembunyikan otomatis notifikasi setelah 4 detik jika belum menyentuh batas limit
    if (violationCount < MAX_VIOLATIONS) {
        setTimeout(() => {
            warningBox.classList.remove('translate-x-0')
            warningBox.classList.add('translate-x-full')
            setTimeout(() => {
                warningBox.classList.add('hidden')
            }, 300)
        }, 4000)
    }
}

// Fungsi menghasilkan suara Beep instan tanpa membutuhkan file .mp3 tambahan
function putarSuaraPeringatan () {
    try {
        const audioCtx = new (window.AudioContext ||
            window.webkitAudioContext)()
        const oscillator = audioCtx.createOscillator()
        const gainNode = audioCtx.createGain()

        oscillator.connect(gainNode)
        gainNode.connect(audioCtx.destination)

        oscillator.type = 'sine'
        oscillator.frequency.setValueAtTime(850, audioCtx.currentTime) // Frekuensi suara sedikit dinaikkan agar terdengar tegas
        gainNode.gain.setValueAtTime(0.15, audioCtx.currentTime)

        oscillator.start()
        oscillator.stop(audioCtx.currentTime + 0.35)
    } catch (e) {
        console.warn(
            'Browser memblokir pemutaran suara otomatis sebelum interaksi user:',
            e
        )
    }
}

// Menjalankan inisialisasi ketika DOM siap
window.addEventListener('DOMContentLoaded', () => {
    const onRegisterPage = document.getElementById('form-registrasi') !== null
    const onExamPage = document.getElementById('proctor-cam') !== null

    if (onRegisterPage) {
        inisialisasiSistemAI()
    } else if (onExamPage) {
        setTimeout(inisialisasiSistemAI, 2000)
    }
})
