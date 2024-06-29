# laravel_deni-hadiya-akmala

Repository ini berisi proyek Laravel yang digunakan untuk keperluan screening web programmer di PT TERAKORP INDONESIA.

## Deskripsi Proyek
Proyek ini mencakup beberapa soal pemrograman yang menggunakan framework Laravel, jQuery, Bootstrap, dan MySQL. Setiap soal akan disimpan dalam folder terpisah dan mencakup file-file yang diperlukan untuk menyelesaikan soal tersebut.

## Struktur Direktori
- `soal_a/`: Folder untuk soal A
- `soal_b/`: Folder untuk soal B

## Cara Menginstal dan Menjalankan Aplikasi pada Lingkungan Pengembangan Lokal

### Langkah-langkah Instalasi:

1. **Clone Repository**

   ```bash
   git clone https://github.com/deni-hadiya-akmala/laravel_deni-hadiya-akmala.git
   ```

   2. **Masuk ke direktori proyek**:
    ```bash
    cd laravel_deni-hadiya-akmala
    ```
3. **Install dependencies menggunakan Composer**:
    ```bash
    composer install
    ```
4. **Copy file `.env.example` menjadi `.env`**:
    ```bash
    cp .env.example .env
    ```
5. **Generate aplikasi key**:
    ```bash
    php artisan key:generate
    ```
6. **Sesuaikan pengaturan basis data pada file `.env`**:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_deni_db
    DB_USERNAME=...
    DB_PASSWORD=...
    ```
7. **Migrasi dan seed database**:
    ```bash
    php artisan migrate --seed
    ```
8. **Install dependencies front-end**:
    ```bash
    npm install
    npm run dev
    ```
9. **Jalankan server Laravel**:
    ```bash
    php artisan serve
    ```


