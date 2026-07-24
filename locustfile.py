import re
from locust import HttpUser, task, between


class EduCounselUser(HttpUser):
    """
    Simulasi user membuka EduCounsel:
    - Login pakai akun yang sudah dibuat di database (bukan database testing)
    - Buka dashboard mahasiswa
    - Buka halaman bimbingan

    Cara jalankan:
        1. php artisan serve   (di terminal lain)
        2. locust -f locustfile.py
        3. Buka http://localhost:8089
        4. Isi jumlah user + spawn rate, isi Host: http://127.0.0.1:8000
        5. Klik "Start swarming"
    """

    wait_time = between(1, 3)  # jeda antar aksi tiap user, biar realistis
    host = "http://educounsel-laravel.test"

    # ── Ganti sesuai akun yang sudah kamu buat di database ──
    EMAIL = "carlislecullen@gmail.com"
    PASSWORD = "edwardcullen"

    def on_start(self):
        """Dipanggil sekali di awal, sebelum user mulai jalanin task."""
        self.login()

    def get_csrf_token(self, path):
        """
        Laravel wajib CSRF token di setiap form POST.
        Ambil dulu halamannya, cari input hidden bernama _token.
        """
        response = self.client.get(path, name=path)
        match = re.search(r'name="_token" value="(.+?)"', response.text)
        return match.group(1) if match else None

    def login(self):
        token = self.get_csrf_token("/login")
        if not token:
            print("CSRF token tidak ditemukan, cek apakah /login bisa diakses.")
            return

        response = self.client.post(
            "/login",
            data={
                "_token": token,
                "email": self.EMAIL,
                "password": self.PASSWORD,
            },
            name="/login [POST]",
        )

        if response.status_code not in (200, 302):
            print(f"Login gagal, status: {response.status_code}")

    @task(1)
    def open_home(self):
        self.client.get("/", name="/ (home)")

    @task(3)
    def open_dashboard(self):
        self.client.get("/mahasiswa/dashboard", name="/mahasiswa/dashboard")

    @task(2)
    def open_bimbingan(self):
        self.client.get("/mahasiswa/bimbingan", name="/mahasiswa/bimbingan")