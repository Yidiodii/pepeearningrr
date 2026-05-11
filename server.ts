import express from 'express';
import { createServer as createViteServer } from 'vite';
import path from 'path';

// Parse FaucetPay API Key from environment or use the one provided by user
const FAUCETPAY_API_KEY = process.env.FAUCETPAY_API_KEY || "de828f1252b15e7604fc166973b053827d6ec7b3dc5f39be9628372d4b00a45c";

async function startServer() {
  const app = express();
  const PORT = Number(process.env.PORT) || 3000;

  app.use(express.json());

  // API Route for FaucetPay Withdrawals
  app.post('/api/withdraw', async (req, res) => {
    try {
      const { amount, to } = req.body;

      if (!amount || amount < 100) {
        return res.status(400).json({ success: false, message: 'Minimum withdrawal is 100 PEPE.' });
      }

      if (!to || !to.includes('@')) {
        return res.status(400).json({ success: false, message: 'Invalid FaucetPay email address.' });
      }

      // Convert amount to satoshis (FaucetPay usually expects amounts multiplied by 10^8)
      // We will assume 1 PEPE = 100,000,000 "satoshis" for their API.
      const satoshiAmount = Math.floor(amount * 100000000);

      // Make request to FaucetPay API
      const params = new URLSearchParams();
      params.append('api_key', FAUCETPAY_API_KEY);
      params.append('currency', 'PEPE');
      params.append('amount', satoshiAmount.toString());
      params.append('to', to);

      const response = await fetch('https://faucetpay.io/api/v1/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params,
      });

      const result = await response.json();

      if (result.status === 200) {
        return res.json({ success: true, message: `Successfully withdrawn ${amount} PEPE to ${to}.` });
      } else {
        return res.status(400).json({ 
          success: false, 
          message: result.message || 'FaucetPay API Error' 
        });
      }
    } catch (error) {
      console.error('Withdrawal error:', error);
      return res.status(500).json({ success: false, message: 'Internal server error during withdrawal.' });
    }
  });

  // Vite middleware for development
  if (process.env.NODE_ENV !== 'production') {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: 'spa',
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    app.use(express.static(distPath));
    app.get('*', (req, res) => {
      res.sendFile(path.join(distPath, 'index.html'));
    });
  }

  app.listen(PORT, '0.0.0.0', () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
}

startServer();
