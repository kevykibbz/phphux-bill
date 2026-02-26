# PHPNuxBill Webhooks Deployment Guide

## Deploy to Vercel

### Prerequisites
1. Vercel CLI installed: `npm i -g vercel`
2. Paystack Secret Key

### Steps

1. **Install dependencies:**
   ```bash
   cd C:\xampp\htdocs\phpnuxbill
   npm install
   ```

2. **Login to Vercel:**
   ```bash
   vercel login
   ```

3. **Set environment variable:**
   ```bash
   vercel env add PAYSTACK_SECRET_KEY
   ```
   Paste your Paystack secret key when prompted.

4. **Deploy:**
   ```bash
   vercel --prod --config vercel-webhooks.json
   ```

5. **Get your deployment URL:**
   After deployment, Vercel will give you a URL like:
   `https://phpnuxbill-webhooks.vercel.app`

6. **Update PHPNuxBill settings:**
   - Go to Settings > Payment Gateway > Paystack
   - Set Callback URL: `https://your-deployment.vercel.app/webhook/paystack`
   - Set Webhook URL: `https://your-deployment.vercel.app/api/paystack-webhook`

7. **Update Paystack Dashboard:**
   - Go to Settings > Webhooks
   - Add: `https://your-deployment.vercel.app/api/paystack-webhook`

## Development

Test locally:
```bash
vercel dev --config vercel-webhooks.json
```

Access at: http://localhost:3000/webhook/paystack

## Endpoints

- **GET /webhook/paystack** - Payment callback (success page)
- **POST /api/paystack-webhook** - Webhook handler
- **GET /api/health** - Health check

## Troubleshooting

1. **404 Error**: Make sure vercel-webhooks.json routes are correct
2. **500 Error**: Check PAYSTACK_SECRET_KEY is set in Vercel environment
3. **Verification Failed**: Ensure secret key matches Paystack dashboard
