import type { VercelRequest, VercelResponse } from "@vercel/node";

export default function handler(req: VercelRequest, res: VercelResponse) {
  res.status(200).json({
    status: "ok",
    service: "phpnuxbill-webhooks",
    timestamp: new Date().toISOString(),
    endpoints: {
      callback: "/webhook/paystack or /api/callback/paystack",
      webhook: "/api/paystack-webhook",
    },
  });
}
