import ReactDOM from 'react-dom';
import React from 'react';
import { HashRouter } from 'react-router-dom';

import App from './App';

let appContainer = document.getElementById( 'wprm-recipe-saved-collections-app' );
let existingAppContainer = document.getElementById( 'wprm-recipe-collections-app' );

if ( appContainer && ! existingAppContainer ) {
	ReactDOM.render(
		<HashRouter>
    	    <App/>
  	    </HashRouter>,
		appContainer
	);
}