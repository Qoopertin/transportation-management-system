# Railway Deployment Guide for TMS

## Automatic Deployment to Railway

Your TMS application is now configured for deployment to Railway! Follow these simple steps:

### Step 1: Create Railway Account
1. Go to https://railway.app
2. Click "Start a New Project"
3. Sign up with GitHub (recommended)

### Step 2: Deploy from GitHub
1. Click "Deploy from GitHub repo"
2. Select your repository: `Qoopertin/transportation-management-system`
3. Railway will automatically detect the configuration

### Step 3: Add Database
1. In your Railway project, click "+ New"
2. Select "Database" → "PostgreSQL"
3. Railway will automatically set `DATABASE_URL` environment variable

### Step 4: Configure Environment Variables

In Railway dashboard, add these variables:

```
APP_NAME=TMS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

DB_CONNECTION=pgsql
(DATABASE_URL is auto-set by Railway)

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 5: Generate APP_KEY
1. In Railway dashboard, click on your service
2. Go to "Settings" → "Variables"
3. Click "RAW Editor"
4. Add: `APP_KEY=base64:YOUR_32_CHAR_KEY_HERE`

To generate a key locally:
```bash
php artisan key:generate --show
```

### Step 6: Deploy!
1. Railway will automatically build and deploy
2. Wait 5-10 minutes for first deployment
3. Your app will be live at: `https://your-project-name.up.railway.app`

## Post-Deployment

### Access the Application
Visit your Railway URL and login:
- **Admin**: admin@example.com / password
- **Dispatcher**: dispatcher@example.com / password
- **Driver**: driver@example.com / password

### View Logs
In Railway dashboard:
1. Click on your service
2. Go to "Deployments"
3. Click on latest deployment
4. View real-time logs

### Run Commands
In Railway dashboard:
1. Click on service → "Settings"
2. Scroll to "Deploy Command Override"
3. Or use Railway CLI

### Configure Custom Domain (Optional)
1. In Railway dashboard → "Settings" → "Domains"
2. Click "Generate Domain" or add custom domain
3. Follow DNS instructions

## Troubleshooting

### Build Fails
- Check logs in Railway dashboard
- Ensure all environment variables are set
- Verify PostgreSQL service is running

### Database Connection Errors
- Make sure PostgreSQL service is added
- Check that `DB_CONNECTION=pgsql` is set
- Verify `DATABASE_URL` is auto-populated

### App crashes on start
- Check `APP_KEY` is set
- Verify `APP_URL` matches your Railway URL
- Check logs for specific errors

## Estimated Costs

**Railway Free Tier:**
- $5/month credit (free)
- ~500 hours runtime
- Perfect for MVP

**After Free Tier:**
- ~$5-10/month for small traffic
- $0.01/hour for compute
- $0.01/GB for database

## Auto-Deploy on Push

Railway automatically redeploys when you push to GitHub:

```bash
# Make changes locally
git add .
git commit -m "Update feature"
git push origin main

# Railway auto-deploys in ~5 minutes
```

## Need Help?

- Railway Docs: https://docs.railway.app/
- Railway Discord: https://discord.gg/railway
- Check Railway status: https://status.railway.app/

---

**Your TMS application is production-ready and configured for Railway deployment!** 🚀
