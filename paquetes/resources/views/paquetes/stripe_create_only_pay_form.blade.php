<style>
    .payment {
        background-color: #ededed;
        border-radius: 1.5%;
        width: 23%;
        padding: 3%;
    }
</style>

<div class="payment">
    <input id="card-holder-name" type="text">

    <!-- Stripe Elements Placeholder -->
    <div id="card-element"></div>

    <button id="card-button">
        Process Payment
    </button>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    const secret_key = 'pk_test_51MluAbHOmndNleswdwRRxHyJUigXcDQucEX5eJwggPLvNDfw3Iw3L0QAUvhuLQwONWQqIEZVGuy3wBSP1JhwAtZk00trCncFPK';
    const stripe = Stripe(secret_key);

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');

    const cardButton = document.getElementById('card-button');

    cardButton.addEventListener('click', async (e) => {
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: cardHolderName.value }
            }
        );

        if (error) {
            // Display "error.message" to the user...
        } else {
            console.log(paymentMethod);
        }
    });

</script>