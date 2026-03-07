import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static values = {url: String};

    dragstart(event) {
        this.draggedElement = event.currentTarget;
        event.dataTransfer.effectAllowed = "move";
        event.dataTransfer.setData("text/plain", this.draggedElement.dataset.id);
        this.draggedElement.classList.add('opacity-50');
        this.draggedElement.classList.add('[&_*]:pointer-events-none');

        // Important for some browsers to allow dragover
        this.draggedElement.style.cursor = 'grabbing';
    }

    dragover(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = "move";

        const target = event.currentTarget.closest('li');
        if (this.draggedElement && target && this.draggedElement !== target) {
            const rect = target.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            const parent = target.parentNode;

            if (event.clientY < midpoint) {
                parent.insertBefore(this.draggedElement, target);
            } else {
                parent.insertBefore(this.draggedElement, target.nextSibling);
            }
        }
    }

    dragend(event) {
        if (this.draggedElement) {
            this.draggedElement.classList.remove('opacity-50');
            this.draggedElement.classList.remove('[&_*]:pointer-events-none');
            this.draggedElement.style.cursor = '';
            this.draggedElement = null;
            this.saveOrder();
        }
    }

    drop(event) {
        event.preventDefault();
        // The movement is handled in dragover for immediate feedback
    }

    async saveOrder() {
        const ids = Array.from(this.element.querySelectorAll('li')).map(li => li.dataset.id);

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({ids: ids})
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            console.error('Failed to save order:', error);
            // Optionally reload to revert UI to server state
            // window.location.reload();
        }
    }
}
