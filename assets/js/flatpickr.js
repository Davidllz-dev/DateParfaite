import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js";

flatpickr.localize(French);

export function initFlatpickrFr(selector = 'input') {
    document.querySelectorAll(selector).forEach(el => {
        if (!el._flatpickr) {
            flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
            });
        }
    });
}
