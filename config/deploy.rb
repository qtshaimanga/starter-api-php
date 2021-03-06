# config valid only for current version of Capistrano
lock '3.8.2'

set :application, 'starterApiSecure'
set :deploy_to, '/var/www/api/starterApiSecure'
set :repo_url, 'git@github.com:airEDF/starter-api-secure-php.git'

set :git_https_username, 'laboratoirei2r'
set :ssh_options, {:forward_agent => true, :keys => ['~/.ssh/id_rsa.pub']}
set :log_level, :debug

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
ask(:path_project, 'Path pf the project for deploy', echo: true)
set :deploy_to, fetch(:path_project)

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push('bdd/bdd.sqlite')

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('tmp/sessions', 'uploads', 'var/cache', 'var/logs')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Delete the symfony default task symfony:create_cache_dir
#Rake::Task["symfony:create_cache_dir"].clear_actions

# Option for composer command
set :composer_install_flags, "--verbose --no-interaction --prefer-dist --optimize-autoloader"

# Default value for keep_releases is 5
set :keep_releases, 3

namespace :deploy do

  after :updated, :silex_update do
    invoke "silex:composer"
    invoke "silex:logs"
    invoke "silex:premissions"
  end

  after :finished, "docker:apache_restart"

end
