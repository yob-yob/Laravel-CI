# Laravel CI/CD Setup for Gitlab

## Description
This is an explanation on how to use the `.gitlab.yml` file that is found on this directory.
please make sure to read the documentaion thoroughly.

## Prerequisites

1. See envoy prerequisites
   1. A server for staging and production.
   2. Both servers should have your SSH keys authorized.
2. Basic knowdlege of Gitlab CI/CD ($$$ Don't hesitate to contact me so I can provide you assistance)

## How to use the gitlab yaml file for CI/CD?

1. Copy the `.gitlab.yml` file into your project
2. Copy the `Envoy.blade.php` file into your project
3. Make the Envoy script work...
   1. See Envoy script ReadMe File. **(Very Important)**
   2. On your gitlab repository make sure that you create a CI/CD Variable.
      1. Which Can be found on this page [https://gitlab.com/{group}/{project}/-/settings/ci_cd]
      2. The variable name should be `SSH_PRIVATE_KEY`
      3. The value is going to be an SSH private key that has access to the server where the project is going to be deployed.
      4. This is *COMMONLY* the file which can be found on your `~/.ssh` directory `id_rsa` or `id_ed25519`
         1. `pbcopy < ~/.ssh/id_rsa` - if you are using RSA 
         2. `pbcopy < ~/.ssh/id_ed25519` - if you are using ED25519
4. Make some changes on the `Yaml` file (Change the URL values under the environment option)
5. 

## How it works?
By reading the comments on the yaml file, you should get a good grasp on how it will work, but here's a TL;DR.

1. **Image and Stages:**
   - `image: sean1999/laravel-ci:8.1`: Specifies the Docker image that the pipeline will use. It's the environment in which your jobs will run.
   - `stages`: Lists the different stages of your pipeline. In your case, you have `setup`, `test`, and `deploy`.

2. **Cache Configuration:**
   - `.cache-config`: This is a template job configuration that sets up caching. Caching stores certain files or directories between job runs to speed up subsequent runs.
   - It defines files and paths to cache, like lock files, npm packages, vendor packages, build artifacts, and environment files.

3. **SSH Configuration:**
   - `.ssh-config`: Another template job that configures SSH-related settings for deployment.
   - It installs `openssh-client`, adds the SSH private key (loaded from GitLab's secret variables), and configures SSH settings to bypass strict host key checking.

4. **Install Job:**
   - `install`: A job defined under the `setup` stage.
   - Extends `.cache-config`, so it uses the caching settings from that template.
   - Copies a `.env.ci` file to `.env` (environment setup).
   - Installs npm packages using `npm ci`, composer packages using `composer install`, builds assets, generates an application key, all in preparation for the next stages.

5. **Pint Test Job:**
   - `pint`: A job under the `test` stage.
   - Extends `.cache-config`, utilizing cached files.
   - Executes tests using a tool named `pint`, possibly related to your application's testing.

6. **Pest Test Job:**
   - `pest`: Another job under the `test` stage.
   - Extends `.cache-config`, leveraging cached files.
   - Uses PostgreSQL service (database) for testing.
   - Executes tests using Laravel's testing framework (`php artisan test`) with coverage and parallel execution.

7. **Staging Deployment:**
   - `staging`: A deployment job under the `deploy` stage.
   - Extends `.ssh-config`, setting up SSH for deployment.
   - Executes a deployment command using the `envoy` tool, targeting a production server.
   - This job only runs when changes are pushed to the `develop` branch.

8. **Production Deployment:**
   - `production`: Similar to the staging deployment job, but for the `main` branch.
   - Also extends `.ssh-config` and deploys to a production server.
   - This job runs when changes are pushed to the `main` branch.

These jobs and stages collectively create a CI/CD pipeline. When you push changes to your repository, GitLab will execute these jobs in the specified order. The pipeline helps automate building, testing, and deploying your application, ensuring that the code changes are properly tested before being deployed to production environments.

