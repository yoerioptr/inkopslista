import {Application} from '@hotwired/stimulus';
import BasketItemToggleController from './controllers/basket_item_toggle_controller';
import BasketReorderController from './controllers/basket_reorder_controller';

const app = Application.start();
app.register('basket-item-toggle', BasketItemToggleController)
app.register('basket-reorder', BasketReorderController)
