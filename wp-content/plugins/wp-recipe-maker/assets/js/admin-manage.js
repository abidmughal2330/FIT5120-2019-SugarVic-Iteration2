if (!global._babelPolyfill) { require('babel-polyfill'); }
import ReactDOM from 'react-dom';
import React from 'react';
import App from './admin-manage/App';

ReactDOM.render(
    <App/>,
	document.getElementById( 'wprm-manage-recipes' )
);