ISABEL
======

WFP HydroApp
------------
There are several application environments:
[Production](http://35.198.69.129:8080)
[Staging](TBD)
[QA](http://35.198.162.151:8080)

# Backend
## Requirements for Local Development

+ [Docker version 17.06.0](https://www.docker.com/community-edition#/download)
  + docker-compose version 1.14.0
  + PHP 5.6.31

`gcloud source repos clone wfp-hydro-app`

`cd wfp-hydro-app`

`docker-compose up`

## Starting/migrating a new compute instance to run the app on Google Cloud Platform
+ If using Ubuntu 14.04+, [install Docker Compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-14-04).
+ SSH into the instance under the ubuntu user, and set up git credentials from the remote.netrc file under `ansible/` into a file named `.netrc` under the ubuntu user home directory, used by ansible scripts.
+ Add network tags (not labels) to the new instance
+ Run the ansible deploy script, by targeting your instance groups by tag: `ansible-playbook deploy.yml -e "branch=mybranch my_tag_name=my_tag_value"`
+ Run `docker-compose up` for the first time. If you run into "Couldn't connect to Docker daemon" issue, you may need to add the ubuntu user to the docker group `sudo usermod -aG docker $USER`, log out, and log back in.
+ Grant wp-admin permission to modify the uploads directory. Within the docker container for the wordpress app (not DB), `chown -R www-data /var/www/html/wp-content/uploads`
+ Update the wordpress DB fields `home` and `siteurl` from localhost to its new IP.
  + Shell into the DB container and MySQL using the appropriate commands. `show databases` and `show tables` will list the available resources for use. `use <database>` (default DB is `wp_aquarius_release`) to operate on these tables.
  + `UPDATE wp_options SET option_value = "http://<new_ip>:8080" WHERE option_name IN ("siteurl", "home");` to update wordpress settings.
+ Update guid, post_content, meta_value in current DB (if not updated, links will route to old IP address)
  + `UPDATE wp_posts SET guid = replace(guid, 'http://<old_ip>:<old_port>', 'http://<new_ip>:<new_port>');`
  + `UPDATE wp_posts SET post_content = replace(post_content, 'http://<old_ip>:<old_port>', 'http://<new_ip>:<new_port>');`
  + `UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://<old_ip>:<old_port>', 'http://<new_ip>:<new_port>');`
+ If tiles/icons on Home will not display, CDN may need to be disabled
  + Log into WP Admin at http://SITE_IP_ADDRESS/wp-admin
  + Click 'Jetpack' on main menu sidebar
  + Scroll down 'Jetpack' page and locate 'Photon' under the 'Performance' heading
  + Click 'Photon' toggle to disable
+ If 'Home' is linking to old siteurl
  + Log into WP Admin at http://SITE_IP_ADDRESS/wp-admin
  + Hover 'Pages' on main menu sidebar
  + Click 'Add New' (this action updates permalinks)
  + Check that 'Home' permalink is updated to current siteurl
+ Media files transfer (the most current wp-content/uploads directory must be backed up at the same time as a SQL dump)
  + scp most current /uploads dir from remote to local: `gcloud compute scp --recurse <instance_name>:/path/to/wfp-hydro-app/app/wp-content/uploads ~/local/path`
  + Replace old /uploads with the most current `cp -fR ~/path/to/recent/uploads ~/path/to/wfp-hydro-app/app/wp-content`

### Generate SQL dump to replace new DB seed in Docker image
+ SSH into the target VM instance containing the Docker MySQL container (as the ubuntu user). Then SSH into the docker container itself, and run `mysqldump --add-drop-table -h hydro-db -u root -p wp_aquarius_release > ./hydrodb_backup.sql`
+ `exit` from mysql container
+ Copy .sql dump from mysql container to the VM instance: `sudo docker cp wfphydroapp_hydro-db_1:/hydrodb_backup.sql /to/my/desired/path`
+ Copy from GCE instance to local machine: `gcloud beta compute scp ubuntu@<gce_instance_name>:/hydrodb_backup.sql /path/to/my/docker/data/dir/ --zone <zone_location>`
+ Rename the old DB seed file to something different in case you need to return to this DB seed `mv wp_aquarius_release old_db_backup`, and move it out of the directory `data/`, since that MUST ONLY contain one file
+ Rename the new DB seed `mv hydrodb_backup.sql wp_aquarius_release` in `data/`
+ If containers are already running, `docker-compose down`, otherwise `docker-compose up`

### Ansible deployment setup
+ install latest Ansible via pip
+ install libcloud
+ get service account key for the default compute engine service account, and move to the path under `ansible/inventory/gce.ini` `gce_service_account_pem_file_path` config setting. This must be a valid path on your local machine for this to work (you may need to adjust your repo location). Note that this file will not be committed to git, as it is specified in .gitignore
+ From the `ansible/` directory, run `ansible-playbook deploy.yml -e "branch=<my_git_branch env=<my_gce_network_tag>"`

# Front End
When a new instance of WordPress is spun up, it will be set to old defaults of using adaptive CSS for web and mobile versions. In order to point all traffic to the mobile version, you'll need to update the values in the wp_options table:
+ `UPDATE wp_options SET option_value = 'ponos' WHERE option_name IN ('template', 'stylesheet', 'current_theme');`
+ `UPDATE wp_options SET option_value = 'Ponos mobile' WHERE option_name IN ('ipad_theme', 'android_tab_theme');`

### Edits to CSS
+ Install SASS
  + [SASS](sudo gem install sass --no-user-install)

+ SASS Compile Mapping
  + PONOS Theme at wp-content/themes/ponos/css
    + `sass style.scss:style.css`, or
    + `sass --watch style.scss:style.css`
  + PONOS System at wp-content/plugins/aq_inventory/css
  + `sass mobile.scss:mobile.css`, or
  + `sass --watch mobile.scss:mobile.css`

# Miscellaneous

### Adding/Modifying Navigation Buttons
+ Log into WP Admin at http://SITE_IP_ADDRESS/wp-admin 
+ Hover 'Appearance' on left nav bar 
+ Select 'Menus' sub menu
+ Select desired menu to edit from 'Menu to edit' drop down
+ Refer to 'Menu Structure' to make desired changes

### Modifying Copy Translations
+ Update copy at app/wp-content/plugins/aq_inventory/view/mobile/home.php
+ Confirm that copy is registered in Polylang
  + Navigate to app/wp-content/plugins/aq_inventory/local/local.php
  + Modify/Add desired copy to local.php `pll_register_string('Homepage', 'copy_string_goes_here', 'ponos_home')`
+ Log into WP Admin at http://SITE_IP_ADDRESS/wp-admin
+ Select 'Languages' on left nav bar
+ Select 'String translations' sub menu
+ Find desired copy and update in desired language


### App Structure
    .
    ├── docker-compose.yml      # docker-compose config file for container generation
    ├── data/                   # Database SQL seed file
    ├── app/                    # WordPress application files
    ├── ansible/                # Deployment scripts
    └── README.md               # Documentation

### Helpful commands:
+ Enter container shell: `docker exec -it <full_docker_container_name> bash`
+ Enter MySQL shell: `mysql -u <db_user> -h <full_docker_container_name> -p`
+ GCE Compute instances are likely x86_64/AMD64 (run `lscpu` to confirm)
+ `docker-compose ps` to show running/stopped containers
+ scp command `scp -i local/path/to/ssh-key -r wfp-hydro-app USERNAME@INSTANCEIP:~`

### TODO:
+ consider using restart: true in compose file

+ consider running docker-compose detached state, logging to target file