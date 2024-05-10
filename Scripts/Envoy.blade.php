@servers(['staging' => 'staging@724.231.234.123', 'prod' => 'prod@server.hosting.com', 'local' => '127.0.0.1',])

@setup
  $git_repository = 'git@github.com:project/project.git'; 

  $project_name = 'project';
  $server_host = $__container->servers[$server];
  
  $release_root = time();
  $releases_dir = [
    'prod' => "~/{$project_name}/releases",
    'staging' => "~/{$project_name}/releases",
  ][$server];
  $shared_dir = [
    'prod' => "~/{$project_name}/shared",
    'staging' => "~/{$project_name}/shared",
  ][$server];
  $release_branch = $branch ?? [
    'prod' => 'main',
    'staging' => 'develop',
  ][$server];

  // Make sure that this paths already exists on the Server
  $storage_path = "$shared_dir/storage";
  $lang_custom_path = "$shared_dir/lang-custom";
  $env_path = "$shared_dir/.env";

  $release_path = "$releases_dir/$release_root";
  $release_storage_path = "$release_path/storage";
  $release_lang_custom_path = "$release_path/lang-custom";
  $release_env_path = "$release_path/.env";
  $release_public_path = "$release_path/public";
  $current_path = "~/{$project_name}/current_release";

  $serving_path = "~/www/public";

  // Special Packages
  // https://github.com/spatie/laravel-sitemap#generating-the-sitemap-frequently
  $spatieSitemapGenerator = true;
  // https://laravel.com/docs/10.x/telescope#data-pruning
  $laravelTelescope = true;
  // https://github.com/blade-ui-kit/blade-icons#caching
  $bladeUiKitIcons = true;
  // https://laravel.com/docs/10.x/horizon#deploying-horizon
  $laravelHorizon = true;
  //
  $spatieMediaLibrary = true;
@endsetup

@before
  if (!isset($server)) {
      throw new Exception("--server option is required");
  }
@endbefore

{{-- Servers --}}
@task('deploy', ['on' => $server])
    {{-- Clone Project --}}
    cd {{ $releases_dir }}
    git clone --single-branch --branch {{ $release_branch }} {{ $git_repository }} {{ $release_path }}

    {{-- Setup Project --}}
    cd {{ $release_path }}

    @if ($commit)
      git reset --hard {{ $commit }}
    @endif
    
    {{-- link storage --}}
    rm -rf {{ $release_storage_path }}
    ln -nfs {{ $storage_path }} {{ $release_storage_path }}
    
    {{-- link .env --}}
    ln -nfs {{ $env_path }} {{ $release_env_path }}
    
    {{-- Install Composer --}}
    composer install -o --prefer-dist

    {{-- install npm --}}
    @unless ($noBuild)
      npm install
      npm run build
    @endunless

    {{-- Migrate Database --}}
    @if ($freshdb && ($server !== 'prod' || $forceFreshProd))
      php artisan migrate:fresh --force
      php artisan db:seed --force
      {{-- php artisan migrate --force --}}
    @else
      php artisan migrate --force
    @endif

    {{-- Restart Horizon --}}
    @if ($laravelHorizon)
      php artisan horizon:terminate
    @endif

    {{-- Run Artisan Commands --}}
    php artisan storage:link
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    php artisan cache:clear
    php artisan clear

    @if ($bladeUiKitIcons)
      php artisan icons:clear
    @endif
    

    {{-- Link after installation (Remove if you are not using laravel Chained Translations) --}}
    {{-- mkdir() will fail  --}}
    {{-- link Custom Language Files --}}
    rm -rf {{ $release_lang_custom_path }}
    ln -nfs {{ $lang_custom_path }} {{ $release_lang_custom_path }}

    {{-- Live --}}
    rm -rf {{ $serving_path }}
    rm -rf {{ $current_path }}
    ln -nfs {{ $release_public_path }} {{ $serving_path }}
    ln -nfs {{ $release_path }} {{ $current_path }}

    @if ($cleanup)
      {{-- Remove old release but keep three --}}
      ls -dt {{ "$releases_dir/*" }} | tail -n +{{ is_bool($cleanup) ? 4 : $cleanup }} | xargs -d "\n" rm -rf;
      {{-- Remove old media --}}
      {{-- Remove this if your are not using laravel media-library by spatie --}}
      {{-- php artisan media-library:clean --force --}}
    @endif

    {{-- Caching --}}
    php artisan config:cache
    php artisan view:cache
    php artisan route:cache
    php artisan event:cache
    php artisan optimize
    @if ($bladeUiKitIcons)
      php artisan icons:cache
    @endif
    
    {{-- Remove this if you are not using laravel telescope --}}
    @if ($laravelTelescope)
      php artisan telescope:prune --hours=48
    @endif

    {{-- Generate Sitemap --}}
    @if ($spatieSitemapGenerator && !$noSitemap)
      php artisan sitemap:generate
    @endif

    {{-- Display Information --}}
    ls -la {{ $current_path }}
    ls -la {{ $serving_path }}

    php artisan about
    cat {{ $env_path }}
@endtask

{{-- Show .env values --}}
@task('show:env', ['on' => $server])
  cd {{ $current_path }}
  cat .env
@endtask

{{-- run artisan commands --}}
{{-- envoy run artisan --command="about" --}}
@task('artisan', ['on' => $server])
  cd {{ $current_path }}
  php artisan {{ $command }}
@endtask

{{-- quick deployment --}}
{{-- envoy run deploy:quick --server="prod" --}}
@task('deploy:quick', ['on' => $server])
  cd {{ $current_path }}
  git pull
@endtask

@task("database:display", ['on' => $server])
  cat ~/db.txt
@endtask

{{-- Make sure to use .pgpass || .my.cnf --}}
{{-- envoy run database:backup --server=? --user=? --host=? --name=? [--is-postgres] [--clean-up] --}}
@task("database:backup", ['on' => $server])
  cd {{$shared_dir}}
  mkdir -p database-backup
  cd database-backup
  @if ($cleanUp)
      rm -rf ./*.sql
  @endif
  @if ($isPostgres)
    pg_dump --no-privileges --no-owner --clean -U {{ $user ?? $server }} -h {{ $host ?? '127.0.0.1' }} -p 5432 {{ $name ?? $server }} > latest.sql
  @else
    mysqldump -u {{ $user ?? $server }} -p -h {{ $host ?? '127.0.0.1' }} -P 3306 {{ $name ?? $server }} > latest.sql
  @endif
@endtask

{{-- envoy run database:download --server=? --}}
@task("database:download", ['on' => 'local'])
  mkdir -p storage/database-backup/{{$server}}
  rsync --progress -avzpr {{$server_host}}:{{$shared_dir}}/database-backup/ storage/database-backup/{{$server}}
@endtask

{{-- Make sure to configure use .pgpass || .my.cnf --}}
{{-- envoy run database:recover --server=? --db=? --user=? --filename=? [--is-postgres] --}}
@task("database:recover", ['on' => 'local'])
  @if ($isPostgres)
    psql -U {{ $user ?? 'postgres' }} -h 127.0.0.1 -p 5432 -d {{ $db ?? $project_name }} -f storage/database-backup/{{ $server }}/{{ $filename ?? 'latest.sql' }} -q -E
  @else
    mysql -h 127.0.0.1 -P 3306 {{ $db ?? $project_name }} < storage/database-backup/{{ $server }}/{{ $filename ?? 'latest.sql' }}
  @endif
@endtask
