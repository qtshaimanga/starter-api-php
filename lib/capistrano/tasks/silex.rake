namespace :silex do
  desc 'Installation des dépendances liées à composer'
  task :composer do
    on roles(:web) do
      within release_path do
        execute :composer, :install
      end
    end
  end

  desc 'Create logs folder'
  task :logs do
    on roles(:web) do
      within release_path do
        execute :mkdir, :logs
      end
    end
  end

  desc 'update les permissions bdd, cache et uploads folders'
  task :premissions do
    on roles(:web) do
      within release_path do
        execute :chmod,'-R', '777', 'uploads/'
        sudo :chown, '-R', 'www-data:www-data', 'var/cache'
        sudo :chmod,'-R', '755', 'var/cache'
        sudo :chown, '-R', 'www-data:www-data', 'var/logs'
        sudo :chmod,'-R', '755', 'var/logs'
        sudo :chmod,'-R', '755', 'bdd/'
      end
    end
  end

  desc 'Restart Apache'
  task :restart_apache do
    on roles(:web) do
      sudo :service, :apache2, :restart
    end
  end

end
