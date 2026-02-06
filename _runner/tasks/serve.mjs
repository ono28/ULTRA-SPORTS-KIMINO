import http from 'node:http';
import fs from 'node:fs';
import path from 'node:path';
import chokidar from 'chokidar';
import mime from 'mime';
import open from 'open';
import { WebSocketServer } from 'ws';
import { siteConfig } from '../../site.config.mjs';
import { debug } from '../utils/logger.mjs';

const cwd = process.cwd();

let started = false;
let wss = null;

export function triggerReload() {
  if (!wss) return;

  wss.clients.forEach((client) => {
    if (client.readyState === 1) {
      client.send('reload');
    }
  });
}

export async function startServe() {
  if (started) return;
  started = true;

  debug('serve', 'Starting development server');

  const distDir = path.resolve(cwd, `${siteConfig.distPath}`);
  const { port = 3000, basePath = siteConfig.basePath, reload = true } = siteConfig.serve;

  debug('serve', `Port: ${port}`);
  debug('serve', `Base path: ${basePath}`);
  debug('serve', `Reload: ${reload}`);

  /* -------------------------
     reload server
  ------------------------- */
  if (reload) {
    debug('serve', 'Starting WebSocket server on port 35729');
    wss = new WebSocketServer({ port: 35729 });
  }

  /* -------------------------
     watch dist
  ------------------------- */
  if (reload && wss) {
    let scheduled = false;

    chokidar.watch(distDir, { ignoreInitial: true }).on('all', () => {
      if (scheduled) return;
      scheduled = true;

      setTimeout(() => {
        scheduled = false;
        wss.clients.forEach((client) => {
          if (client.readyState === 1) {
            client.send('reload');
          }
        });
      }, 50);
    });
  }

  /* -------------------------
     static server
  ------------------------- */
  const reloadScript = reload
    ? `
    <script>
    (() => {
      const ws = new WebSocket('ws://localhost:35729')
      ws.onmessage = () => location.reload()
    })()
    </script>`
    : '';

  const waitForFile = (filePath, timeout = 3000) =>
    new Promise((resolve, reject) => {
      const start = Date.now();
      const timer = setInterval(() => {
        if (fs.existsSync(filePath)) {
          clearInterval(timer);
          resolve();
        }
        if (Date.now() - start > timeout) {
          clearInterval(timer);
          reject(new Error('waitForFile timeout'));
        }
      }, 50);
    });

  http
    .createServer((req, res) => {
      let reqPath = req.url.split('?')[0];
      if (reqPath.endsWith('/')) reqPath += 'index.html';

      const filePath = path.join(distDir, reqPath.replace(/^\/+/, ''));

      if (!fs.existsSync(filePath)) {
        res.writeHead(404);
        res.end('Not Found');
        return;
      }

      const type = mime.getType(filePath) || 'application/octet-stream';

      if (filePath.endsWith('.html')) {
        let html = fs.readFileSync(filePath, 'utf8');
        if (reload) {
          html = html.replace(/<\/body>/i, `${reloadScript}\n</body>`);
        }
        res.writeHead(200, { 'Content-Type': type });
        res.end(html);
        return;
      }

      res.writeHead(200, { 'Content-Type': type });
      fs.createReadStream(filePath).pipe(res);
    })
    .listen(port, async () => {
      const url = `http://localhost:${port}${basePath}`;
      console.log(`🚀 server ${url}`);

      // ブラウザ自動起動（初回のみ）
      const indexPath = path.join(distDir, basePath, 'index.html');

      try {
        await waitForFile(indexPath);
        await open(url);
      } catch {
        console.warn('⚠️ index.html not found. skip open');
      }
    });
}
