import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["list"];

    add(event) {
        const index = parseInt(this.listTarget.dataset.index);
        const prototype = this.listTarget.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const li = document.createElement('li');
        li.className = 'relative flex gap-x-4 rounded-xl p-4 ring-1 ring-inset ring-gray-200 dark:ring-white/10 bg-gray-50 dark:bg-white/5';
        li.innerHTML = newForm;

        this.listTarget.appendChild(li);
        this.listTarget.dataset.index = index + 1;
    }

    remove(event) {
        event.preventDefault();
        const item = event.currentTarget.closest('li');
        if (item) {
            item.remove();
        }
    }
}
