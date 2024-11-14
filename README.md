# BFP Fire Tracker

The BFP Fire Tracker Realtime system is likely a real-time monitoring and alert platform designed to support the Bureau of Fire Protection (BFP) 
in tracking and managing fire-related incidents. It provides live updates on fire incidents, resource allocation, and responder status, enabling 
quicker responses and better coordination. Using technologies like Laravel, real-time broadcasting (such as Laravel Echo with Reverb), and Docker, 
it integrates location tracking, status updates, and possibly an interactive map interface to assist responders in managing ongoing incidents effectively. 
This setup enhances situational awareness and improves overall fire safety management.

## Tech Stacks
 - Laravel ( PHP Framework )
 - Vue.JS ( Javascript Framework )
 - Mysql
 - Docker
 - Reverb (Laravel WebSocket)
 - Caddy ( WebServer )
## Pre requisites
Download the following packages
- Docker
- Git
- Node
## Deployment
To deploy this app you need to follow this steps:

1. Open Docker app
2. Create a folder in desktop name **BFP Fire Tracker**
3. Inside of that folder run a terminal
4. Clone the app using git
5. after cloning you should see **BFP Fire Tracker** folder open that folder. To build the app run this command inside of the folder youve just open just run this one time
  - `docker compose build --no-cache app && docker compose up app -d && docker exec -d app bash -c "chmod 777 -R ./ && cp .env.example .env && composer install && php artisan config:clear && php artisan migrate:fresh --seed && npm install && npm run build " && docker compose build --no-cache caddy && docker compose up caddy -d && docker exec -d app bash -c "chmod 777 -R ./" && docker exec -it app php artisan reverb:start & && docker exec -it app php artisan queue:work &`
7. To start the app run this inside of the mentioned folder `docker compose up && docker exec -it app php artisan reverb:start &`
8. To stop the app run this inside of the mentioned folder `docker compose down`
9. After that you can access the application via `http://localhost`

### Developer
*Jurie Tylier Pedrogas ( Software Engineer )*

Development Time Range: 1month+
