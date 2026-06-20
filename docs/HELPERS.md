# Global Helper Functions & Helper Classes

The Nomanur Laravel Starter Kit provides a comprehensive set of helper classes and global wrapper functions to simplify date formatting, string formatting, general utility tasks, secure image handling, and real-time SEO management.

These functions and classes are located under:
- **Global Wrappers**: `bootstrap/helpers.php`
- **Helper Classes**: `app/Helpers/`

---

## Table of Contents
1. [Date Helpers](#1-date-helpers)
2. [Formatting Helpers](#2-formatting-helpers)
3. [General Helpers](#3-general-helpers)
4. [Image & Storage Helpers](#4-image--storage-helpers)
5. [SEO & Structured Data Helpers](#5-seo--structured-data-helpers)

---

## 1. Date Helpers
Located at `app/Helpers/DateHelper.php` and mapped via global wrapper functions.

### `format_date()`
Wrapper for `DateHelper::format()`. Formats a date string or instance into a consistent output format.

* **Signature**: `format_date(mixed $date, string $format = 'M d, Y'): string`
* **Parameters**:
  - `$date`: String, Carbon instance, or timestamp.
  - `$format` (optional): Output format. Defaults to `'M d, Y'` (e.g., "Jun 20, 2026").
* **Example**:
  ```php
  $formatted = format_date('2026-06-20 14:00:00'); // Returns "Jun 20, 2026"
  $custom = format_date(now(), 'Y-m-d'); // Returns "2026-06-20"
  ```

### `time_ago()`
Wrapper for `DateHelper::timeAgo()`. Returns a human-readable string indicating how long ago a date occurred.

* **Signature**: `time_ago(mixed $timestamp): string`
* **Parameters**:
  - `$timestamp`: String, Carbon instance, or timestamp.
* **Example**:
  ```php
  $ago = time_ago(now()->subHours(2)); // Returns "2 hours ago"
  ```

### `DateHelper::toUserTimezone()`
Converts a date to a specific timezone (defaults to system timezone in `config/app.php`).

* **Signature**: `DateHelper::toUserTimezone(mixed $date, ?string $timezone = null): Carbon`
* **Parameters**:
  - `$date`: String, Carbon instance, or timestamp.
  - `$timezone` (optional): The target timezone (e.g. `'Asia/Dhaka'`).
* **Example**:
  ```php
  $userTime = DateHelper::toUserTimezone('2026-06-20 12:00:00', 'Asia/Dhaka');
  ```

### `DateHelper::isPast()`
Checks if the specified date lies in the past.

* **Signature**: `DateHelper::isPast(mixed $date): bool`
* **Example**:
  ```php
  if (DateHelper::isPast($expiredAt)) {
      // Handle expired state
  }
  ```

### `DateHelper::isFuture()`
Checks if the specified date lies in the future.

* **Signature**: `DateHelper::isFuture(mixed $date): bool`
* **Example**:
  ```php
  if (DateHelper::isFuture($scheduledAt)) {
      // Handle scheduled/upcoming state
  }
  ```

### `DateHelper::age()`
Calculates the current age based on a birth date.

* **Signature**: `DateHelper::age(mixed $birthDate): int`
* **Example**:
  ```php
  $age = DateHelper::age('1995-10-15'); // Returns 30 (as of 2026)
  ```

---

## 2. Formatting Helpers
Located at `app/Helpers/FormatHelper.php` and mapped via global wrapper functions.

### `clean_html()`
Wrapper for `FormatHelper::cleanHtml()`. Strips all HTML tags except for specified allowed ones to prevent XSS attacks while retaining safe rich text structures.

* **Signature**: `clean_html(string $html, array $allowedTags = ['p', 'br', 'strong', 'em', 'a']): string`
* **Parameters**:
  - `$html`: The raw input string containing HTML.
  - `$allowedTags` (optional): Array of tag names to preserve.
* **Example**:
  ```php
  $clean = clean_html('<p>Hello <script>alert("XSS")</script> <strong>world!</strong></p>');
  // Returns: "<p>Hello alert("XSS") <strong>world!</strong></p>"
  ```

### `generate_slug()`
Wrapper for `FormatHelper::generateSlug()`. Generates an URL-friendly unique slug from a string title.

* **Signature**: `generate_slug(string $title, string $separator = '-'): string`
* **Parameters**:
  - `$title`: Raw text string to generate slug from.
  - `$separator` (optional): The string divider character. Defaults to `'-'`.
* **Example**:
  ```php
  $slug = generate_slug('Nomanur Starter Kit Release!'); // Returns "nomanur-starter-kit-release"
  ```

### `truncate_text()`
Wrapper for `FormatHelper::truncate()`. Truncates a string to a specified length and appends a customizable ending indicator (like `...`).

* **Signature**: `truncate_text(string $text, int $limit = 100, string $ending = '...'): string`
* **Parameters**:
  - `$text`: String to truncate.
  - `$limit` (optional): Max character limit. Defaults to `100`.
  - `$ending` (optional): Suffix to append if truncated. Defaults to `'...'`.
* **Example**:
  ```php
  $truncated = truncate_text('This is a very long post title that needs to be shortened.', 20);
  // Returns: "This is a very long..."
  ```

### `mask_string()`
Wrapper for `FormatHelper::maskString()`. Masks a portion of a sensitive string (such as email prefixes, credit cards, or phone numbers).

* **Signature**: `mask_string(string $string, int $visibleChars = 3, string $maskChar = '*'): string`
* **Parameters**:
  - `$string`: Raw input.
  - `$visibleChars` (optional): Number of characters to leave unmasked at the beginning. Defaults to `3`.
  - `$maskChar` (optional): Character used to mask the remainder. Defaults to `'*'`.
* **Example**:
  ```php
  $masked = mask_string('secretpassword', 4); // Returns "secr**********"
  ```

### `FormatHelper::htmlToText()`
Strips all HTML tags entirely and trims extra whitespaces to retrieve pure text content.

* **Signature**: `FormatHelper::htmlToText(string $html): string`
* **Example**:
  ```php
  $text = FormatHelper::htmlToText('<p>Paragraph content</p>'); // Returns "Paragraph content"
  ```

### `FormatHelper::arrayToString()`
Converts an array to a comma-separated (or custom-separated) string representation.

* **Signature**: `FormatHelper::arrayToString(array $array, string $glue = ', '): string`
* **Example**:
  ```php
  $labels = FormatHelper::arrayToString(['admin', 'editor', 'subscriber']); // Returns "admin, editor, subscriber"
  ```

---

## 3. General Helpers
Located at `app/Helpers/GeneralHelper.php` and mapped via global wrapper functions.

### `format_number()`
Wrapper for `GeneralHelper::formatNumber()`. Formats a numeric input with localized thousand separators.

* **Signature**: `format_number(float|int $number, int $decimals = 0): string`
* **Example**:
  ```php
  $number = format_number(1250000.45, 2); // Returns "1,250,000.45"
  ```

### `format_money()`
Wrapper for `GeneralHelper::formatMoney()`. Formats an amount as currency using PHP's `NumberFormatter` class, looking up system configuration settings.

* **Signature**: `format_money(float|int $amount, ?string $currency = null, ?string $locale = null): string`
* **Parameters**:
  - `$amount`: Numeric currency amount.
  - `$currency` (optional): Currency code (e.g. `'USD'`). Defaults to `config('app.currency')`.
  - `$locale` (optional): Language/Region locale (e.g. `'en_US'`). Defaults to `config('app.locale')`.
* **Example**:
  ```php
  $price = format_money(99.99); // Returns "$99.99"
  $euro = format_money(50, 'EUR', 'de_DE'); // Returns "50,00 €"
  ```

### `calculate_percentage()`
Wrapper for `GeneralHelper::percentage()`. Safely calculates the ratio percentage of a part to a total, protecting against division-by-zero errors.

* **Signature**: `calculate_percentage(float|int $part, float|int $total, int $precision = 2): float`
* **Example**:
  ```php
  $percent = calculate_percentage(15, 60); // Returns 25.0
  $zeroSafe = calculate_percentage(10, 0); // Returns 0.0
  ```

### `get_client_ip()`
Wrapper for `GeneralHelper::getClientIp()`. Resolves the client's real IP address, detecting proxies, Cloudflare headers (`HTTP_CF_CONNECTING_IP`), and forwarded IPs.

* **Signature**: `get_client_ip(): ?string`
* **Example**:
  ```php
  $ip = get_client_ip(); // Returns "192.168.1.1" or real client IP
  ```

### `is_mobile()`
Wrapper for `GeneralHelper::isMobile()`. Evaluates the request user-agent header to determine if it originates from a mobile web browser.

* **Signature**: `is_mobile(): bool`
* **Example**:
  ```php
  if (is_mobile()) {
      // Redirect or load mobile view layout
  }
  ```

### `active_route()`
Wrapper for `GeneralHelper::isActiveRoute()`. Checks if the current route matches one or more given routes, returning a CSS class string if positive.

* **Signature**: `active_route(string|array $routes, string $activeClass = 'active'): string`
* **Example**:
  ```html
  <a href="/dashboard" class="{{ active_route('dashboard') }}">Dashboard</a>
  <!-- If active, renders: class="active" -->
  
  <a href="/settings" class="{{ active_route(['settings.profile', 'settings.security']) }}">Settings</a>
  ```

### `GeneralHelper::getUserAgent()`
Retrieves the request User-Agent string from server headers.

* **Signature**: `GeneralHelper::getUserAgent(): ?string`
* **Example**:
  ```php
  $userAgent = GeneralHelper::getUserAgent();
  ```

### `GeneralHelper::generateRandomString()`
Generates a secure random hexadecimal string of the specified length.

* **Signature**: `GeneralHelper::generateRandomString(int $length = 32): string`
* **Example**:
  ```php
  $token = GeneralHelper::generateRandomString(16); // Returns a 16-character hex string
  ```

### `GeneralHelper::parseVideoId()`
Extracts the video ID from a YouTube or Vimeo URL structure.

* **Signature**: `GeneralHelper::parseVideoId(string $url): ?string`
* **Example**:
  ```php
  $ytId = GeneralHelper::parseVideoId('https://www.youtube.com/watch?v=dQw4w9WgXcQ'); // Returns "dQw4w9WgXcQ"
  $vimeoId = GeneralHelper::parseVideoId('https://vimeo.com/847623912'); // Returns "847623912"
  ```

### `GeneralHelper::hexToRgb()`
Converts a CSS hex color code (3 or 6 digits, optional `#`) to an associative RGB array.

* **Signature**: `GeneralHelper::hexToRgb(string $hex): ?array`
* **Example**:
  ```php
  $rgb = GeneralHelper::hexToRgb('#ff0000'); // Returns ['r' => 255, 'g' => 0, 'b' => 0]
  ```

### `GeneralHelper::sanitize()`
Recursively sanitizes input strings, stripping tags and encoding entities to shield output rendering.

* **Signature**: `GeneralHelper::sanitize(mixed $data): mixed`
* **Example**:
  ```php
  $cleanInput = GeneralHelper::sanitize($request->all());
  ```

---

## 4. Image & Storage Helpers
Located at `app/Helpers/ImageHelper.php` and mapped via global wrapper functions.

### `get_avatar()`
Wrapper for `ImageHelper::getAvatar()`. Resolves a user's avatar URL. If the user object contains a custom avatar path, it generates the storage URL. Otherwise, it falls back to a Gravatar query string or a local default template.

* **Signature**: `get_avatar(mixed $user, int $size = 100): string`
* **Parameters**:
  - `$user`: User model instance or null.
  - `$size` (optional): Output size dimension in pixels. Defaults to `100`.
* **Example**:
  ```html
  <img src="{{ get_avatar(auth()->user(), 150) }}" alt="Avatar">
  ```

### `storage_url()`
Wrapper for `ImageHelper::storageUrl()`. Ensures a stored file path is fully qualified into a public HTTP URL. Full URL strings are returned intact; relative local storage paths are processed through `Storage::url()`.

* **Signature**: `storage_url(?string $path): string`
* **Example**:
  ```php
  $imageUrl = storage_url('uploads/banners/logo.png'); // Returns "http://yourdomain.com/storage/uploads/banners/logo.png"
  ```

### `file_size_human()`
Wrapper for `ImageHelper::fileSizeHuman()`. Converts integer byte measurements into human-readable formatted file size suffixes (e.g., KB, MB, GB).

* **Signature**: `file_size_human(int $bytes, int $precision = 2): string`
* **Example**:
  ```php
  $sizeString = file_size_human(1548576); // Returns "1.48 MB"
  ```

### `ImageHelper::getDefaultAvatar()`
Retrieves the standard fallback avatar image URL path from assets.

* **Signature**: `ImageHelper::getDefaultAvatar(int $size = 100): string`
* **Example**:
  ```php
  $defaultAvatarUrl = ImageHelper::getDefaultAvatar(); // Returns URL pointing to images/default-avatar.png
  ```

### `ImageHelper::uploadImage()`
Uploads, scales, processes, and persists an uploaded image using Intervention Image. Enforces aspect ratio retention.

* **Signature**: `ImageHelper::uploadImage(UploadedFile $file, string $directory, array $options = []): ?string`
* **Parameters**:
  - `$file`: Uploaded file instance.
  - `$directory`: Destination subdirectory path inside the public storage disk.
  - `$options` (optional): Configuration array (`'max_width'`, `'max_height'`, `'quality'`).
* **Example**:
  ```php
  $savedPath = ImageHelper::uploadImage($request->file('photo'), 'avatars', [
      'max_width' => 800,
      'max_height' => 800,
      'quality' => 90
  ]);
  ```

### `ImageHelper::deleteImage()`
Removes an image file from the public storage disk if it exists.

* **Signature**: `ImageHelper::deleteImage(?string $path): bool`
* **Example**:
  ```php
  ImageHelper::deleteImage($user->avatar);
  ```

### `ImageHelper::getDimensions()`
Resolves the pixel dimensions (width and height) of a stored image.

* **Signature**: `ImageHelper::getDimensions(string $path): ?array`
* **Example**:
  ```php
  $dim = ImageHelper::getDimensions('uploads/banner.jpg'); // Returns ['width' => 1200, 'height' => 600]
  ```

---

## 5. SEO & Structured Data Helpers
Located at `app/Helpers/SeoHelper.php` and mapped via global wrapper functions.

### `set_seo()`
Wrapper for `SeoHelper::setDefault()`. Populates global SEO meta tags, OpenGraph properties, and Twitter Card attributes for standard pages.

* **Signature**: `set_seo(string $title, ?string $description = null, array $keywords = [], ?string $image = null): void`
* **Example**:
  ```php
  public function index() {
      set_seo(
          'Contact Support',
          'Get in touch with our helpdesk team.',
          ['support', 'help', 'contact'],
          asset('images/support-banner.jpg')
      );
      return view('contact');
  }
  ```

### `set_article_seo()`
Wrapper for `SeoHelper::setArticle()`. Sets structured meta data tailored for publishing blogs, news articles, or posts.

* **Signature**: `set_article_seo(string $title, string $description, string $url, ?string $image = null, ?string $publishedTime = null, ?string $author = null): void`
* **Example**:
  ```php
  public function show(Post $post) {
      set_article_seo(
          $post->title,
          $post->excerpt,
          route('posts.show', $post),
          storage_url($post->cover),
          $post->published_at->toIso8601String(),
          $post->author->name
      );
      return view('posts.show', compact('post'));
  }
  ```

### `structured_data()`
Wrapper for `SeoHelper::addStructuredData()`. Generates an inline JSON-LD script block containing structured rich snippets for search engines.

* **Signature**: `structured_data(array $data): string`
* **Example**:
  ```html
  {!! structured_data([
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => [...]
  ]) !!}
  ```

### `SeoHelper::setProduct()`
Sets metadata attributes representing product specs for e-commerce crawling.

* **Signature**: `SeoHelper::setProduct(string $title, string $description, string $url, float $price, string $currency = 'USD', ?string $image = null, string $availability = 'in stock'): void`
* **Example**:
  ```php
  SeoHelper::setProduct('Premium Leather Jacket', '100% genuine leather...', $productUrl, 149.99);
  ```

### `SeoHelper::organizationSchema()`
Generates dynamic schema representation scripts representing an Organization.

* **Signature**: `SeoHelper::organizationSchema(string $name, string $url, ?string $logo = null, ?string $description = null): string`
* **Example**:
  ```html
  {!! SeoHelper::organizationSchema('Acme Inc.', 'https://acme.com', 'https://acme.com/logo.png') !!}
  ```

### `SeoHelper::breadcrumbSchema()`
Generates BreadcrumbList microdata for Google SERP display.

* **Signature**: `SeoHelper::breadcrumbSchema(array $items): string`
* **Example**:
  ```html
  {!! SeoHelper::breadcrumbSchema([
      ['name' => 'Home', 'url' => '/'],
      ['name' => 'Blog', 'url' => '/blog'],
      ['name' => 'Current Post', 'url' => '/blog/my-post']
  ]) !!}
  ```

### `SeoHelper::setRobots()`
Configures crawler instructions meta values (e.g. noindex, nofollow) on a page level.

* **Signature**: `SeoHelper::setRobots(bool $noIndex = false, bool $noFollow = false): void`
* **Example**:
  ```php
  // Prevent indexing for checkout/sensitive pages
  SeoHelper::setRobots(true, true);
  ```
