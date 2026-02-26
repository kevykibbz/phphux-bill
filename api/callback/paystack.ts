// =============================================================================
// PHPNuxBill — Paystack Payment Callback Page (Vercel Serverless)
// =============================================================================
// GET /api/callback/paystack?reference=xxx&trxref=xxx
//
// Paystack redirects here after checkout. Verifies transaction and shows
// success/failure page.
// =============================================================================

import type { VercelRequest, VercelResponse } from "@vercel/node";
import https from "https";

interface PaystackTransaction {
  status: string;
  reference: string;
  amount: number;
  currency: string;
  paid_at: string;
  customer: { email: string };
  channel: string;
  authorization?: {
    last4?: string;
    brand?: string;
    card_type?: string;
  };
}

function verifyTransaction(
  reference: string,
  secretKey: string,
): Promise<PaystackTransaction> {
  return new Promise((resolve, reject) => {
    const options = {
      hostname: "api.paystack.co",
      port: 443,
      path: `/transaction/verify/${encodeURIComponent(reference)}`,
      method: "GET",
      headers: { Authorization: `Bearer ${secretKey}` },
    };

    const req = https.request(options, (res) => {
      let data = "";
      res.on("data", (chunk) => (data += chunk));
      res.on("end", () => {
        try {
          const parsed = JSON.parse(data);
          if (parsed.status && parsed.data) {
            resolve(parsed.data);
          } else {
            reject(new Error(parsed.message || "Verification failed"));
          }
        } catch (e) {
          reject(e);
        }
      });
    });

    req.on("error", reject);
    req.end();
  });
}

function formatAmount(amount: number, currency: string): string {
  const formatted = (amount / 100).toLocaleString("en-NG", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
  return currency === "NGN" ? `₦${formatted}` : `${currency} ${formatted}`;
}

function renderSuccessPage(tx: PaystackTransaction): string {
  const formattedAmount = formatAmount(tx.amount, tx.currency);
  const paidAt = new Date(tx.paid_at).toLocaleString("en-NG", {
    dateStyle: "medium",
    timeStyle: "short",
  });

  return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful - PHPNuxBill</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: #f1f1f1;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .container {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 720px;
      width: 100%;
      overflow: hidden;
    }
    .header {
      background: #fff;
      padding: 30px 40px 20px;
      text-align: center;
      border-bottom: 2px solid #f1f1f1;
    }
    .checkmark {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #22c55e;
      margin: 0 auto 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .checkmark svg {
      width: 28px;
      height: 28px;
      stroke: white;
      stroke-width: 3;
      fill: none;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
    .header h1 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 4px;
      color: #1f2937;
    }
    .header p {
      font-size: 14px;
      color: #6b7280;
    }
    .content {
      padding: 30px 40px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      align-items: start;
    }
    .amount {
      text-align: center;
      padding: 30px 20px;
      background: #f9fafb;
      border-radius: 6px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100%;
    }
    .amount-label {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 500;
    }
    .amount-value {
      font-size: 36px;
      font-weight: 700;
      color: #1f2937;
    }
    .details {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      padding: 0;
    }
    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 14px 20px;
      border-bottom: 1px solid #f3f4f6;
      gap: 20px;
    }
    .detail-row:last-child {
      border-bottom: none;
    }
    .detail-label {
      color: #6b7280;
      font-size: 14px;
      font-weight: 500;
      white-space: nowrap;
    }
    .detail-value {
      color: #1f2937;
      font-weight: 500;
      font-size: 14px;
      text-align: right;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .actions {
      grid-column: 1 / -1;
      display: flex;
      gap: 12px;
      margin-top: 10px;
    }
    .btn {
      flex: 1;
      padding: 14px 24px;
      border-radius: 6px;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      text-align: center;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .btn-primary {
      background: #3b82f6;
      color: white;
    }
    .btn-primary:hover {
      background: #2563eb;
    }
    .btn-secondary {
      background: white;
      color: #6b7280;
      border: 1px solid #d1d5db;
    }
    .btn-secondary:hover {
      background: #f9fafb;
    }
    .footer {
      text-align: center;
      padding: 16px;
      color: #9ca3af;
      font-size: 12px;
      background: #fafafa;
      border-top: 1px solid #f3f4f6;
    }
    .reference {
      font-family: 'Courier New', monospace;
      background: #f3f4f6;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 13px;
      color: #374151;
    }
    @media (max-width: 640px) {
      .content {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="checkmark">
        <svg viewBox="0 0 52 52">
          <polyline points="14 27 22 35 38 19"/>
        </svg>
      </div>
      <h1>Payment Successful!</h1>
      <p>Your transaction has been completed</p>
    </div>
    
    <div class="content">
      <div class="amount">
        <div class="amount-label">Amount Paid</div>
        <div class="amount-value">${formattedAmount}</div>
      </div>
      
      <div class="details">
        <div class="detail-row">
          <span class="detail-label">Reference</span>
          <span class="detail-value reference">${tx.reference}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Email</span>
          <span class="detail-value">${tx.customer.email}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Payment Method</span>
          <span class="detail-value">${tx.channel}${tx.authorization?.last4 ? ` •••• ${tx.authorization.last4}` : ""}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Date & Time</span>
          <span class="detail-value">${paidAt}</span>
        </div>
      </div>
      
      <div class="actions">
        <a href="http://localhost/phpnuxbill/?_route=home" class="btn btn-primary">Return to Dashboard</a>
      </div>
    </div>
    
    <div class="footer">
      Powered by PHPNuxBill × Paystack
    </div>
  </div>
</body>
</html>`;
}

function renderErrorPage(error: string): string {
  return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Failed - PHPNuxBill</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f1f1f1;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .container {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 720px;
      width: 100%;
      padding: 60px 40px;
      text-align: center;
    }
    .error-icon {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: #ef4444;
      margin: 0 auto 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      color: white;
    }
    h1 {
      font-size: 28px;
      color: #1f2937;
      margin-bottom: 12px;
      font-weight: 600;
    }
    p {
      color: #6b7280;
      margin-bottom: 32px;
      line-height: 1.6;
      font-size: 15px;
    }
    .btn {
      display: inline-block;
      padding: 16px 40px;
      background: #3b82f6;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      font-size: 15px;
      transition: all 0.2s;
    }
    .btn:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="error-icon">✕</div>
    <h1>Payment Failed</h1>
    <p>${error}</p>
    <a href="http://localhost/phpnuxbill/?_route=order/package" class="btn">Try Again</a>
  </div>
</body>
</html>`;
}

export default async function handler(
  req: VercelRequest,
  res: VercelResponse,
): Promise<void> {
  const { reference, trxref } = req.query;
  const ref = (reference || trxref) as string;

  if (!ref) {
    res.status(400).send(renderErrorPage("No transaction reference provided"));
    return;
  }

  const secretKey = process.env.PAYSTACK_SECRET_KEY;
  if (!secretKey) {
    console.error("[paystack-callback] PAYSTACK_SECRET_KEY not configured");
    res.status(500).send(renderErrorPage("Server configuration error"));
    return;
  }

  try {
    const tx = await verifyTransaction(ref, secretKey);

    if (tx.status === "success") {
      res.setHeader("Content-Type", "text/html");
      res.status(200).send(renderSuccessPage(tx));
    } else {
      res.status(400).send(renderErrorPage(`Transaction status: ${tx.status}`));
    }
  } catch (error) {
    console.error("[paystack-callback] Verification error:", error);
    res.status(500).send(renderErrorPage("Could not verify transaction"));
  }
}
