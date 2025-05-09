/**
 * @typedef {Object} TemplateData
 * @description Key-value pairs to replace placeholders in the HTML template
 * @example
 * {
 *   title: "Welcome",
 *   description: "This is a demo"
 * }
 */

/**
 * Dynamically loads an HTML component and injects data into {{placeholders}}.
 *
 * @param {string} elementId - The ID of the container where the component will be rendered.
 * @param {string} filePath - Path to the HTML file (relative to the root or current file).
 * @param {TemplateData} [data={}] - An optional object containing placeholder values.
 */
async function loadHTML(elementId, filePath, data = {}) {
    try {
        const response = await fetch(filePath);
        if (!response.ok) throw new Error(`Failed to load ${filePath}`);

        let html = await response.text();

        // Replace placeholders in the form of {{ key }} with data[key]
        for (const key in data) {
            const regex = new RegExp(`{{\\s*${key}\\s*}}`, "g");
            html = html.replace(regex, data[key]);
        }

        document.getElementById(elementId).innerHTML = html;
    } catch (error) {
        console.error(`[loadHTML] Error loading ${filePath}:`, error);
    }
}
