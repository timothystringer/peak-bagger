# Media Library Setup

This project uses Spatie Laravel Media Library to store `Ascent` media on S3 and serve via CloudFront.

Required .env variables:

- AWS_ACCESS_KEY_ID=
- AWS_SECRET_ACCESS_KEY=
- AWS_DEFAULT_REGION=
- AWS_BUCKET=
- FILESYSTEM_DISK=s3
- MEDIA_DISK=s3
- CLOUDFRONT_URL=https://your-cloudfront-domain.example
- QUEUE_CONNECTION=database

Publish and migrate (already automated in project setup):

```bash
composer install
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate
```

Queue setup (database driver):

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work --tries=3
```

Regenerate conversions:

```bash
php artisan media:regenerate {mediaId?} --all
```

Notes:
- Objects are uploaded to S3 with private visibility — configure CloudFront as the public CDN origin and set `CLOUDFRONT_URL` to prefix media URLs.
- You can supply a Wainwright dataset at `database/seeders/data/wainwrights.json` and run `php artisan db:seed` to load peaks.

