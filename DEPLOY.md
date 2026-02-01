# Deploying Fluffy to Railway

This guide will help you deploy your Laravel application to **Railway.app**.

## Prerequisites

1.  A GitHub repository containing this project (Push your latest code!).
2.  A [Railway.app](https://railway.app/) account.

## Step 1: Create a Project

1.  Log in to the **Railway Dashboard**.
2.  Click **+ New Project** > **Deploy from GitHub repo**.
3.  Select your `Fluffy` repository.
4.  Click **Deploy Now**.
    *   *Note: The first build might fail or the app might crash because we haven't set up the database variables yet. This is normal.*

## Step 2: Add a Database

1.  In your Railway project view, right-click on the canvas (or click **+ New**).
2.  Select **Database** > **MySQL**.
3.  Wait for the MySQL service to initialize.

## Step 3: Configure Environment Variables

1.  Click on your **Laravel service** (the card representing your GitHub repo).
2.  Go to the **Variables** tab.
3.  You need to add the following variables.

### Database Connection (Use Railway's "Reference" feature)
Instead of copying hard values, type `${{` to see available variables from your MySQL service.

| Variable | Value (Reference) |
| :--- | :--- |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.HOST}}` |
| `DB_PORT` | `${{MySQL.PORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQL_DATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQL_USER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQL_PASSWORD}}` |

### Application Settings (Manual Entry)

| Variable | Value | Notes |
| :--- | :--- | :--- |
| `APP_NAME` | `Fluffy` | |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `APP_URL` | `https://<your-railway-url>.up.railway.app` | You can find this in the *Settings* tab after it generates. |
| `APP_KEY` | `base64:...` | Run `php artisan key:generate --show` locally and copy the value here. |
| `LOG_CHANNEL` | `stderr` | Important for seeing logs in Railway. |

### Stripe & Other Secrets
Copy these from your local `.env` file:
*   `STRIPE_KEY`
*   `STRIPE_SECRET`
*   `STRIPE_WEBHOOK_SECRET`

## Step 4: Redeploy

1.  Once the variables are saved, Railway usually triggers a redeploy automatically.
2.  If not, go to the **Deployments** tab and click **Redeploy**.
3.  Watch the **Build Logs** to ensure `npm run build` execution.
4.  Watch the **Deploy Logs** to see `php artisan migrate` running.

## Troubleshooting

*   **500 Error**: Check the **Deploy Logs**. It usually means `APP_KEY` is missing or DB connection failed.
*   **Assets 404**: Ensure `ASSET_URL` is NOT set, or set it to your `APP_URL`. `npm run build` in `nixpacks.toml` should handle this.
