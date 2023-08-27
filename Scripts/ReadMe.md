# Laravel Envoy Script

## Description
This file is used to deploy a laravel project to a web server.
it's a Zero-Downtime-Deployment script that only creates a single ssh connection

## Prerequisites

1. A Laravel Project (With a remote git repository)
2. A basic knowledge with the Laravel Envoy package
3. A server for staging and production.
   1. Both servers should have your SSH keys authorized.
   2. SSH keys from this servers should be added as a deploy keys for the Git Repository

## Installation

Install Laravel Envoy (Globally)
```
composer global require laravel/envoy
```

Copy the `Envoy.blade.php` script to your project directory

Change some values...

- `@server` Settings
- `$git_repository`
  - The Repository to clone during a release
- `$releases_dir`
  - The path where the project is going to be cloned.
- `$shared_dir`
  - The path where we should store the shared assets for each releases.
    - This is where the `storage` directory and `.env` file is stored
- `$release_branch`
  - The branch to use when releasing a project on specific server.

That's it 🎉

## How to use?

To deploy a project to `staging`
```
envoy deploy --server=staging --cleanup
```
To deploy a project to `production`
```
envoy deploy --server=prod --cleanup
```

## Options Documentation

`--server={server}`

(Required) Used to select which server to deploy the project.

`--cleanup={n}`

To clean old releases, This will by default keep the latest 4 releases, but you can specify a number `n` that instructs it how much releases to keep.
