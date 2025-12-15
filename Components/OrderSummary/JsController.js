import {Ajax} from "../../../../../Core/Js/Ajax";
import {PageManager} from "../../../../../Core/Js/PageManager";

export default class {
    constructor() {
        console.log('order js controller loaded');
        const form = document.querySelector('form.orderForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            await this.updateDeliveryDetails();
            window.location='/order/'+form.cartId.value+'/summary';
        })

        form.addEventListener('input', async (e) => {
            await this.updateDeliveryDetails();
        });
    }

    async updateDeliveryDetails() {
        const form = document.querySelector('form.orderForm');
        const deliveryDetails = {
            firstName: form.firstName.value,
            lastName: form.lastName.value,
            email: form.email.value,
            tel: form.tel.value
        }

        Ajax.KivapiShop.Order.Cart.updateDeliveryDetails(form.cartId.value, deliveryDetails);
    }

}
