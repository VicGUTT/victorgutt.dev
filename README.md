<p align="center">
  <a href="https://victorgutt.dev" target="_blank">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/VicGUTT/victorgutt.dev/HEAD/public/images/logo/gvs_logo_light_with_background@2x.png">
      <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/VicGUTT/victorgutt.dev/HEAD/public/images/logo/gvs_logo_light_with_background@2x.png">
      <img alt="Victor Gutt" src="https://raw.githubusercontent.com/VicGUTT/victorgutt.dev/HEAD/public/images/logo/gvs_logo_light_with_background@2x.png" width="200" style="max-width: 100%;">
    </picture>
  </a>
</p>

# Source code for `victorgutt.dev`

The source code of this website is open source and [can be seen on GitHub](https://github.com/VicGUTT/victorgutt.dev).

## Tech

[victorgutt.dev](https://victorgutt.dev) is built with:

- [Laravel](https://laravel.com)
- [Vue](https://vuejs.org)
- [Inertia](https://inertiajs.com)
- [Tailwind CSS](https://tailwindcss.com)

## Development

Clone the repository:

```bash
git clone https://github.com/VicGUTT/victorgutt.dev
```

Install the PHP requirements

```bash
composer install
```

Install the JavaScript requirements

```bash
npm install
```

Run Vite

```bash
npm run dev
```

You may optionally run the queue worker

```bash
php artisan queue:listen --queue=critical,important,high,default,low
```

Lastly, configure Laravel Herd or Laravel Valet to setup the app at: `victorgutt.test`.

## Testing

```bash
composer test
```

<!-- ## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. -->

## Contributing

If you're interested in contributing to the project, please read our [contributing docs](/.github/CONTRIBUTING.md) **before submitting a pull request**.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Victor GUTT](https://github.com/vicgutt)
- [All Contributors](../../contributors)
