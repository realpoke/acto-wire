# ![logosmall](https://github.com/user-attachments/assets/d4176b6d-5068-412c-b23d-770c431e2334) ActoWire Starter Kit

An opinionated Laravel FATLL stack website base. [Live Demo](https://actowire.laravel.cloud/)

[![tests](https://github.com/realpoke/acto-wire/actions/workflows/tests.yaml/badge.svg)](https://github.com/realpoke/acto-wire/actions/workflows/tests.yaml)
[![deploy](https://github.com/realpoke/acto-wire/actions/workflows/deploy.yaml/badge.svg)](https://github.com/realpoke/acto-wire/actions/workflows/deploy.yaml)
<a href="https://herd.laravel.com/new?starter-kit=realpoke/acto-wire"><img src="https://img.shields.io/badge/Install%20with%20Herd-f55247?logo=laravel&logoColor=white"></a>

## Stack
- [FluxUI Pro](https://fluxui.dev/) – Premium UI component library for Livewire.
- [Alpine.js](https://alpinejs.dev/) – Minimal, reactive JavaScript framework for handling UI interactivity.
- [TailwindCSS](https://tailwindcss.com/) – Utility-first CSS framework for rapid, customizable design.
- [Laravel](https://laravel.com/) – Modern PHP framework for building robust websites.
- [Livewire](https://livewire.laravel.com/) – Full-stack framework for building dynamic interfaces in Laravel.

## Development Setup
1. Clone the repo:
   - **Using GitHub Template**:

     Click "Use this template" on the [GitHub repo page](https://github.com/realpoke/acto-wire) to create a new repository and clone it.

   - **Manually**: 

     ```sh
     git clone https://github.com/realpoke/acto-wire
     cd acto-wire
     ```

   - **Laravel Installer**: 

     ```sh
     laravel new --using=realpoke/acto-wire
     cd acto-wire
     ```
    - **Laravel Herd**:

      <a href="https://herd.laravel.com/new?starter-kit=realpoke/acto-wire"><img src="https://img.shields.io/badge/Install%20with%20Herd-f55247?logo=laravel&logoColor=white"></a>

2. Set up and run the development environment:
   - **Initial Setup**: First time starting the development environment, use the setup script:

     ```sh
     composer setup
     ```
   - **Start the development environment**: Run this to start the development environment:

     ```sh
     composer dev
     ```

## GitHub Setup & Secrets
1. Create a GitHub repository.

2. Add the following repository secrets:
   - `LARAVEL_CLOUD_API_TOKEN` → Your Laravel Cloud Deploy Hook URL
   - `FLUX_USERNAME` → Your email for Flux Pro
   - `FLUX_LICENSE_KEY` → Your Flux Pro license key

3. Push your code:

   ```sh
   git remote add origin https://github.com/your-username/your-repo.git
   git branch -M main
   git push -u origin main
   ```

## Deployment to Laravel Cloud
1. Make sure all tests pass.

2. Change the Git branch to `production` in the general settings on Laravel Cloud.

3. Simply push to the `production` branch on Github.

**NOTE** You might want to setup custom deploy commands, here are some examples on Laravel Cloud:

**Build Commands:**

```sh
composer config http-basic.composer.fluxui.dev my@email.com my-super-secret-flux-key
composer install --no-dev

npm install -g bun
bun install
bun run build
```

**Deploy Commands:**

```sh
php artisan migrate --force
php artisan optimize
```

**Auto-deploy**: Once the tests pass and the code is committed to the `production` branch, the auto-deployment pipeline triggers and pushes the code to Laravel Cloud.

## Action Pattern / Command Pattern with Dependency Injection
This project leverages the **Action Pattern** (Command Pattern) extensively, using **Dependency Injection (DI)** to promote clean, scalable, and maintainable code.

The **Command Pattern** encapsulates actions as objects, decoupling the logic execution from the request. Each command is responsible for executing a specific task, and DI is used to inject necessary services, enhancing testability and flexibility.

The **FATLL stack** (Flux Pro, Alpine.js, TailwindCSS, Laravel, Livewire) integrates seamlessly with this pattern, allowing for clear separation of concerns and effective management of actions across the application. DI ensures all dependencies are automatically resolved, making it easy to manage complex workflows and actions.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). As a derivative, **ActoWire Starter Kit** is also licensed under the same [MIT license](https://opensource.org/licenses/MIT).
