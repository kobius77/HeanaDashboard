# 🥚 HeanaDashboard

> **"Heana"** [Austrian dialect]: *Hens / Chickens.*

Welcome to the mission-control center for our flock of ~10 ladies. Because why just *collect* eggs when you can **visualize** them in high-definition charts?

## 🚧 WIP Disclaimer
**Current Status:** *Broken shells everywhere.*
This project is actively being developed. Features may appear, disappear, or moult without warning. The code is currently cleaner than the coop, but that's a low bar.

---

## ⚙️ The Architecture (The "Egg-Traction" Pipeline)

We moved away from "Excel Engineering" to a proper stack. Here is the data journey:

1.  **The Input:** I walk into the coop, collect eggs, and send a single number (e.g., "8") to a **Telegram Bot**.
2.  **The Middleware:** **n8n** catches the webhook, sanitizes the input, and executes an SQL `INSERT`.
3.  **The Vault:** Data is stored in a local **MySQL/MariaDB** database (RIP Google Sheets).
4.  **The Visuals:** **Laravel + FilamentPHP** read the data and render beautiful, reactive dashboards.

## 💻 The Tech Stack

* **Core:** [Laravel 11](https://laravel.com/) (PHP 8.3)
* **UI/Dashboard:** [FilamentPHP v3](https://filamentphp.com/) (The real MVP here)
* **Database:** MySQL
* **Environment:** WSL / Ubuntu 24.04

## 🚀 Getting Started (Dev)

If you want to run your own chicken analytics platform:

1.  **Clone the repo:**
    ```bash
    git clone [https://github.com/kobius77/HeanaDashboard.git](https://github.com/kobius77/HeanaDashboard.git)
    cd HeanaDashboard
    ```

2.  **Install Dependencies:**
    ```bash
    # Note: We ignore security audits because we live on the edge (and dev tools are annoying)
    composer install --no-audit
    npm install && npm run build
    ```

3.  **Setup Environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    # Configure your DB credentials in .env
    ```

4.  **Migrate & Serve:**
    ```bash
    php artisan migrate
    php artisan serve
    ```

## 🔮 Roadmap / Wishlist

- [x] Basic daily logging
- [ ] **Weather Overlay:** correlate production drops with rainy days.
- [ ] **Feed Tracker:** Calculate the exact Cost-Per-Egg (CPE) so I can feel guilty about the expensive organic feed.
- [ ] **Comparison Views:** "This week vs. same week last year."

---

*Made with ❤️ and 🌽 in Lower Austria.*
