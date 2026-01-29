# 🥚 HeanaDashboard

> **"Heana"** [Austrian dialect]: *Hens / Chickens.*

A mission-control center for our flock. Tracks daily egg production, flock composition, and environmental factors.

![Egg Production Heatmap](screenshots/heatmap.png)

## ✨ Features

*   **Production Heatmap**: Visual overview of egg laying performance and sun hours.
*   **Localization 🌍**: Fully translated in **English** and **German** (auto-detection enabled).
*   **Admin Panel**: Powered by [FilamentPHP](https://filamentphp.com/).
    *   **Daily Logs**: Track eggs, temperature, and sun hours.
    *   **Flock Records**: Manage population (Hens, Cocks, Chicklets).
    *   **Charts**: Daily production bars, monthly comparisons.
*   **Integration Ready**:
    *   **Webhook API**: Ingest data (e.g., from Home Assistant) via `POST /webhook/ingest`.

## 🛠 Tech Stack

*   **Framework**: Laravel 12
*   **UI**: Filament v3, Livewire, Tailwind CSS
*   **Database**: MySQL
*   **Charts**: Cal-Heatmap, Chart.js

## 🚀 Setup

1.  Clone & Install:
    ```bash
    composer install
    npm install && npm run build
    ```
2.  Configure `.env` (Database & `WEBHOOK_SECRET`).
3.  Migrate: `php artisan migrate`.

---
*Made with ❤️ and 🌽 in Lower Austria.*