import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static values = {url: String};

    async toggle(event) {
        const isChecked = event.target.checked;

        if (isChecked) this.element.classList.add('line-through');
        else this.element.classList.remove('line-through');

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({inCart: isChecked}),
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            event.target.checked = !isChecked;
            this.element.classList.toggle('line-through', !isChecked);
        }
    }
}
