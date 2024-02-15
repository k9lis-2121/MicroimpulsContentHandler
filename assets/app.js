// import { registerVueControllerComponents } from '@symfony/ux-vue';
//import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import './styles/app.css'


import Vue from 'vue';
import './styles/app.css';
import DirCreator from "./controllers/DirCreator";
import Search from "./controllers/Search";

    new Vue({
        el: '#dircreator',
        render: h => h(DirCreator)
    });

    new Vue({
        el: '#search',
        render: h => h(Search)
    });
