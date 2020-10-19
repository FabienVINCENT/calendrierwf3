/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');



// commande a taper pour générer une route ()
//symfony console fos:js-routing:dump --format=json --target=public/assets/js/fos_js_routes.json

// Puis rajouter dans le controller, dans les annotations

//options = { "expose" = true }
// voir l'exemple ci dessous
//@Route("/", name="home", options = { "expose" = true })


const routes = require('../public/assets/js/fos_js_routes.json');

import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

//Routing.generate('route_name', /* {your params} */)
console.log('route home : ')
console.log(Routing.generate('home', true))
console.log('route listUser : ')
console.log(Routing.generate('listUser', true))


