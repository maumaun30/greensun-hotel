# Deploy workflow

`deploy.yml` builds the theme on every push to `main` and rsyncs it into the CloudPanel site directory over SSH.

## One-time CloudPanel setup

1. **Create an SSH key pair for the deploy** (locally, NOT on the server):
   ```bash
   ssh-keygen -t ed25519 -C "github-actions-greensun" -f greensun_deploy
   ```
   Two files are produced: `greensun_deploy` (private) and `greensun_deploy.pub` (public).

2. **Authorize the public key on the CloudPanel site user**:
   - In CloudPanel: *Sites → your site → SSH/FTP → SSH Public Keys → Add* and paste the contents of `greensun_deploy.pub`.
   - OR via shell as that user:
     ```bash
     mkdir -p ~/.ssh && chmod 700 ~/.ssh
     cat >> ~/.ssh/authorized_keys <<< "<contents of greensun_deploy.pub>"
     chmod 600 ~/.ssh/authorized_keys
     ```

3. **Verify SSH from your laptop** before trusting GitHub Actions:
   ```bash
   ssh -i greensun_deploy -p 22 <ssh-user>@<host> "echo ok && ls -d /home/<ssh-user>/htdocs/<domain>/wp-content/themes/greensun-hotel"
   ```
   Both lines must succeed. If the theme directory doesn't exist yet, create it once with `mkdir -p`.

## GitHub repository secrets

In *Settings → Secrets and variables → Actions → New repository secret*, add:

| Secret | Value | Required |
| --- | --- | --- |
| `DEPLOY_SSH_PRIVATE_KEY` | Full contents of `greensun_deploy` (the private key, including `-----BEGIN/END-----` lines) | ✅ |
| `DEPLOY_HOST` | Server hostname or IP, e.g. `123.45.67.89` or `cp.example.com` | ✅ |
| `DEPLOY_USER` | CloudPanel site SSH user, e.g. `greensun` | ✅ |
| `DEPLOY_PATH` | Absolute theme directory on the server, e.g. `/home/greensun/htdocs/greensun.hotel/wp-content/themes/greensun-hotel` | ✅ |
| `DEPLOY_PORT` | Custom SSH port if not 22 | optional |
| `DEPLOY_OPCACHE_URL` | URL to a `?opcache_reset=1` route to bust PHP opcache after deploy | optional |

Once those exist, `git push origin main` triggers the workflow. You can also trigger it manually from *Actions → Deploy theme to CloudPanel → Run workflow*.

## What gets deployed

The workflow runs `npm ci && npm run build` on the runner, then rsyncs everything under the theme root **except** dev-only files:

- `.git`, `.github`, `.gitignore`, `.gitattributes`
- `node_modules`, `package.json`, `package-lock.json`
- `tailwind.config.js`, `postcss.config.js`
- `scripts/` (build helpers — server doesn't need them)
- Unminified `assets/css/main.css` and `assets/css/critical.css` (minified versions are deployed)
- Source maps and `.DS_Store`

`rsync --delete` removes files on the server that no longer exist in the repo, keeping the deployed theme an exact mirror of `main`. Block `build/` directories ARE shipped because the runtime references them from `block.json`.

## Rolling back

CloudPanel sites are normally backed up nightly; restore via *Sites → your site → Snapshots*. For a quick rollback without restoring a snapshot, revert the offending commit on `main` and push — the workflow redeploys the previous state in ~90 seconds.
