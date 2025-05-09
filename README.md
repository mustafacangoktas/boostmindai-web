# BoostMindAI – Prototype Frontend (HTML Structure)

> This is a **prototype-only** frontend structure prepared as part of a Web Programming Workshop assignment.

## ⚠️ Disclaimer

This structure is **not optimized for production**. It uses a basic HTML/CSS/JS setup with JavaScript-based component loading for educational purposes.

### Why This Approach?

- Component-like loading with `loadHTML()` is used to simulate reusable HTML parts (e.g., navbar, footer).
- While this helps avoid repetition during prototyping, it is **not SEO-friendly** since key content is injected dynamically after the page loads.
- Normally, such reusable structures would be implemented using server-side rendering (e.g., PHP, Node.js) or a framework (e.g., React, Vue).

### Why Not Use PHP or a Framework?

At this stage of the workshop:
- PHP and MySQL have **not yet been covered**.
- The current focus is on learning HTML, CSS, and vanilla JavaScript.
- Later stages may include converting this structure into a proper PHP-based web app.

## 💡 Notes

- The goal here is to focus on layout, structure, and component separation.
- Content like meta tags and external assets (e.g., Bootstrap, Feather Icons) are loaded via `loadHeadAssets()` for simplicity.


## 🧪 Status

This is a **work-in-progress** branch and is not intended to be merged into `main` until a proper backend or static site strategy is adopted.

---

Feel free to explore the structure, and keep in mind that this is for **learning and demonstration purposes only**.
