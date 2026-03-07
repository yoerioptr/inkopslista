import {Application} from '@hotwired/stimulus';
import BasketItemToggleController from './controllers/basket_item_toggle_controller';

const app = Application.start();
app.register('basket-item-toggle', BasketItemToggleController)
