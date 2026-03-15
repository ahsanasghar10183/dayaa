# Stripe Payment Integration Setup Guide

This guide explains how to set up Stripe payment processing for the Dayaa eCommerce shop.

## ⚠️ Important Notes

- **Stripe is ONLY used for eCommerce shop payments**
- Donations use SumUp terminals (not affected by this integration)
- Subscriptions are not yet using Stripe (future implementation)

## Prerequisites

1. A Stripe account (https://stripe.com)
2. Access to your Stripe Dashboard

## Step 1: Get Your Stripe API Keys

### For Testing (Development)

1. Log in to your Stripe Dashboard
2. Make sure you're in **Test Mode** (toggle in top right)
3. Go to **Developers** → **API keys**
4. Copy the following keys:
   - **Publishable key** (starts with `pk_test_`)
   - **Secret key** (starts with `sk_test_`)

### For Production

1. Switch to **Live Mode** in Stripe Dashboard
2. Go to **Developers** → **API keys**
3. Copy the following keys:
   - **Publishable key** (starts with `pk_live_`)
   - **Secret key** (starts with `sk_live_`)

⚠️ **NEVER commit your live secret keys to version control!**

## Step 2: Configure Environment Variables

Add these to your `.env` file:

```env
# Stripe Configuration (for eCommerce Shop only)
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

Replace the placeholder values with your actual Stripe keys.

## Step 3: Set Up Stripe Webhook

Webhooks allow Stripe to notify your application about payment events.

### Local Development (using Stripe CLI)

1. Install Stripe CLI: https://stripe.com/docs/stripe-cli
2. Login to Stripe CLI:
   ```bash
   stripe login
   ```
3. Forward webhooks to your local server:
   ```bash
   stripe listen --forward-to https://dayaa.test/webhook/stripe
   ```
4. Copy the webhook signing secret (starts with `whsec_`) and add it to your `.env` file

### Production Setup

1. Go to Stripe Dashboard → **Developers** → **Webhooks**
2. Click **Add endpoint**
3. Enter your webhook URL: `https://yourdomain.com/webhook/stripe`
4. Select the following events to listen to:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Click **Add endpoint**
6. Copy the **Signing secret** (starts with `whsec_`) and add it to your `.env` file

## Step 4: Clear Configuration Cache

After updating `.env`, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 5: Test the Integration

### Testing Checkout Flow

1. Go to the shop: `https://dayaa.test/shop`
2. Add a product to cart
3. Proceed to checkout
4. Select **Credit Card (Stripe)** as payment method
5. Fill in customer details
6. Click **Place Order**
7. You'll be redirected to Stripe Checkout
8. Use Stripe test card numbers:
   - **Success:** `4242 4242 4242 4242`
   - **Decline:** `4000 0000 0000 0002`
   - Use any future expiry date, any CVC, and any postal code

### Verifying Payment

1. After successful payment, you'll be redirected back to the success page
2. Check the order in Super Admin dashboard → Shop → Orders
3. Payment status should be "completed"
4. Check Stripe Dashboard → Payments to see the payment

## Payment Flow Diagram

```
Customer                  Dayaa                    Stripe
   |                        |                         |
   |--Add to Cart---------->|                         |
   |                        |                         |
   |--Checkout------------->|                         |
   |                        |                         |
   |                        |--Create Session-------->|
   |                        |<--Session URL-----------|
   |                        |                         |
   |<--Redirect to Stripe---|                         |
   |                                                   |
   |--Enter Card Details---------------------------->|
   |                                                   |
   |<--Payment Confirmation---------------------------|
   |                                                   |
   |                        |<--Webhook: Payment OK---|
   |                        |                         |
   |                        |--Update Order Status--->|
   |                        |                         |
   |<--Success Page---------|                         |
```

## Security Considerations

1. **API Keys:** Never commit secret keys to version control
2. **Webhook Signatures:** Always verify webhook signatures (already implemented)
3. **HTTPS:** Always use HTTPS in production
4. **Environment:** Keep test and live keys separate

## Troubleshooting

### Webhook not receiving events

- Check that webhook URL is accessible from internet
- Verify webhook signing secret is correct
- Check Laravel logs: `storage/logs/laravel.log`
- Check Stripe webhook logs in Dashboard → Developers → Webhooks

### Payment failing

- Ensure Stripe keys are correct in `.env`
- Check `config:clear` was run after updating `.env`
- Verify test card numbers are correct
- Check browser console for JavaScript errors

### Orders not updating to "completed"

- Verify webhook is set up correctly
- Check that webhook events include `checkout.session.completed`
- Check Laravel logs for webhook processing errors

## Support

For Stripe-specific issues, consult:
- Stripe Documentation: https://stripe.com/docs
- Stripe Support: https://support.stripe.com

For Dayaa platform issues, contact your development team.

## Future Enhancements

- Add support for PayPal payments
- Add support for Apple Pay / Google Pay
- Implement subscription billing with Stripe
- Add refund management
- Add invoice generation
