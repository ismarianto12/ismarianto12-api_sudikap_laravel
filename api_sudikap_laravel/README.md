# Hentikan dan hapus container lama
docker-compose down -v

# Build ulang dan jalankan
docker-compose up -d --build

# Cek status container
docker-compose ps   

ismarianto@ISMARIANTOS-MAC BACKEND_ARSIP % docker exec -it php74_app php artisan jwt:secret
docker exec -it php74_app php /var/www/html/api_sudikap_laravel/artisan key:generate

docker exec -it php74_app php /var/www/html/api_sudikap_laravel/artisan rtisan jwt:secret

jwt-auth secret [ZhPZY9LarDuJVZpd2cpMPoLsGY7HGNSKDJxscWhnMVJw6aeFHdUrYO0ELuAKD9H4] set successfully.
ismarianto@ISMARIANTOS-MAC BACKEND_ARSIP % docker exec -it php74_app php artisan config:clear

Configuration cache cleared!
ismarianto@ISMARIANTOS-MAC BACKEND_ARSIP % docker exec -it php74_app php artisan cache:clear

Application cache cleared!





docker exec -it php74_app chown -R www-data:www-data /var/www/html/api_sudikap_laravel/vendor

docker exec -it php74_app composer dump-autoload

docker exec -it php74_app bash


==check docker yang exited di dalam image id nya : 
# Lihat daftar container yang exited
docker ps -a --filter "status=exited"

# Jalankan kembali container yang sudah berhenti (perhatikan ID atau NAMENYA)
docker start <container_id_atau_nama>
