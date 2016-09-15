namespace :silex do
  desc 'Installation des dépendances liées à composer'
  task :composer do
    on roles(:web) do
      within release_path do
        execute :composer, :install
      end
    end
  end

  desc 'update les permissions bdd, cache et uploads folders'
  task :premissions do
    on roles(:web) do
      within release_path do
        execute :chmod, '777', '-R', 'uploads/'
        execute :chmod, '777', '-R', 'var/cache'
        execute :chmod, '777', '-R', 'var/logs'
        execute :chmod, '777', '-R', 'bdd/'
      end
    end
  end

  desc 'Restart Apache'
  task :restart_apache do
    on roles(:web) do
      sudo :service, 'apache2', :restart
    end
  end

end
