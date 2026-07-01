// Uploads a built app directory to 20i over FTP.
//
// 20i's account-level "FTP Locked" security blocks connections from
// unrecognized IPs, which includes GitHub Actions runners — so this can't
// run in that cloud workflow. Run it locally instead, from a machine whose
// IP has been allowlisted in 20i StackCP (Manage Hosting > site > "Unlock
// FTP by IP Address").
//
// Usage:
//   DEPLOY_20I_HOST=... DEPLOY_20I_USERNAME=... DEPLOY_20I_PASSWORD=... \
//   DEPLOY_20I_REMOTE_DIR=... node scripts/deploy-20i.mjs <local-build-dir>

import { Client } from "basic-ftp";

const localDir = process.argv[2];
const host = process.env.DEPLOY_20I_HOST;
const user = process.env.DEPLOY_20I_USERNAME;
const password = process.env.DEPLOY_20I_PASSWORD;
const remoteDir = process.env.DEPLOY_20I_REMOTE_DIR;

if (!localDir || !host || !user || !password || !remoteDir) {
    console.error("Usage: DEPLOY_20I_HOST=... DEPLOY_20I_USERNAME=... DEPLOY_20I_PASSWORD=... DEPLOY_20I_REMOTE_DIR=... node scripts/deploy-20i.mjs <local-build-dir>");
    process.exit(1);
}

const client = new Client();
client.ftp.verbose = true;

try {
    await client.access({ host, user, password, secure: false });
    await client.ensureDir(remoteDir);
    await client.uploadFromDir(localDir);
    console.log("Deploy complete.");
} catch (err) {
    console.error("Deploy failed:", err);
    process.exitCode = 1;
} finally {
    client.close();
}
