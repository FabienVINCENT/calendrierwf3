// commande a taper pour générer une route ()
//symfony console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

// Puis rajouter dans le controller, dans les annotations

//options = { "expose" = true }
 // voir l'exemple ci dessous
   //@Route("/", name="home", options = { "expose" = true })


const routes = require('../../js/fos_js_routes.json');

import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
Routing.generate('rep_log_list');

//Routing.generate('route_name', /* {your params} */)
console.log(Routing.generate('home', /* your params */))


