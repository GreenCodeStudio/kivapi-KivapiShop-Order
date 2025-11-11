import {Ajax} from "../../../../../Core/Js/Ajax";

export default class {
    constructor() {
        console.log('order js controller loaded');
        const form = document.querySelector('form.orderForm');
        form.addEventListener('submit', async (e) => {
            await this.updateDeliveryDetails();
        })

        form.addEventListener('input', async (e) => {
            await this.updateDeliveryDetails();
        });
    }

    async updateDeliveryDetails() {
        const form = document.querySelector('form.orderForm');
        const deliveryDetails = {firstName: form.firstName.value, email: form.email.value}

        Ajax.KivapiShop.Order.Cart.updateDeliveryDetails(form.cartId.value, deliveryDetails);
    }

}
