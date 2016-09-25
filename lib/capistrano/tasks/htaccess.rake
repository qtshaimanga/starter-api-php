# namespace :htaccess  do
#
#   desc "Création du fichier de conf .htaccess"
#   task :conf do
#     on roles(:web) do
#       erb = File.read "lib/capistrano/templates/htaccess_conf.erb"
#       config_file = "/tmp/.htaccess"
#       upload! StringIO.new(ERB.new(erb).result(binding)), config_file
#       sudo :mv, config_file, current_path
#       puts
#     end
#   end
#
# end


# Verifier current PATH
