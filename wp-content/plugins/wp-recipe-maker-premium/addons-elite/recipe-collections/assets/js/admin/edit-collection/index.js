import ReactDOM from 'react-dom';
import React from 'react';
import { HashRouter } from 'react-router-dom';

import App from './App';

let appContainer = document.getElementById( 'wprm-recipe-collections-manage-app' );

if (appContainer) {
	ReactDOM.render(
		<HashRouter>
    	    <App/>
  	    </HashRouter>,
		appContainer
	);
}