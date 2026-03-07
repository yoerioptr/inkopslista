import {Application} from '@hotwired/stimulus';

const app = Application.start();

const controllers = import.meta.glob('./controllers/**/*_controller.js', {eager: true});

for (const path in controllers) {
    const controller = controllers[path];
    const name = path
        .split('/')
        .pop()
        .replace('_controller.js', '')
        .replace(/_/g, '-');

    app.register(name, controller.default);
}
