# 📝 UAS Cloud Computing - Dockerized Todo App

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Container-2496ED?style=flat&logo=docker&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white)

Aplikasi **Todo List Manager** sederhana namun modern yang dibangun menggunakan **PHP Native**. Aplikasi ini dirancang untuk memenuhi tugas UAS Cloud Computing, mendemonstrasikan kemampuan containerization tanpa database SQL (menggunakan JSON Storage).

## ✨ Fitur Utama
* **CRUD Operation:** Create, Read, Update (Edit), Delete tugas.
* **Data Persistence:** Data tersimpan aman dalam file `todos.json` (tidak hilang saat refresh).
* **Modern UI:** Menggunakan **Tailwind CSS** dengan tampilan *Glassmorphism*.
* **Dynamic Background:** Efek animasi background "Aurora" yang estetis.
* **Docker Ready:** Siap dijalankan di mana saja menggunakan Docker.

## 📂 Struktur File
```text
.
├── Dockerfile       # Konfigurasi Image (PHP 8.2 + Apache)
├── index.php        # Logic PHP + Tampilan HTML/Tailwind
├── todos.json       # Penyimpanan data (Database file)
└── README.md        # Dokumentasi ini