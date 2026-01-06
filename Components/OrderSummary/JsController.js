import {Ajax} from "../../../../../Core/Js/Ajax";
import {PageManager} from "../../../../../Core/Js/PageManager";

export default class {
    constructor() {
        console.log('order js controller loaded');

        document.querySelector('.orderButton').onclick = e => {
            e.preventDefault();

            Ajax.KivapiShop.Order.Cart.doOrder(JSON.parse(e.target.dataset.data));
            try {
                fbq('track', 'Purchase', {currency: "USD", value: 30.00, data: JSON.parse(e.target.dataset.data)});
            } catch (_) {
                //ignore
            }
            try {
                gtag('event', 'purchase', {currency: "USD", value: 30.00, data: JSON.parse(e.target.dataset.data)});
            } catch (_) {
                //ignore
            }
        }
    }
}
