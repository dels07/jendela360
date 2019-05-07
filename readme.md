# Take Home Test Jendela360

Specification:
- PHP 7.1.x
- Laravel 5.8.x
- SQLite

### Problem found
- I cannot send whatsapp message to candidate as file attachment due to missing domain pointing to real file location, it can be solved by using s3 or setup ngrok. However I leave uncommented code on `CandidateController.php:76,83,84` as references.
- Code for webhook not tested due to same problem with no real domain for webhook url.

### Step to run:
<pre>
git clone jendela360
cd jendela360
cp env.example .env
php artisan key:generate
composer-install
php artisan serve

open browser http://127.0.0.1:8000
</pre>

### .env settings
- for quick start use sqlite as database
- for chatapi please add required env variable, by registering on chat-api.com website
- for smtp, I use mailtrap.io for testing
