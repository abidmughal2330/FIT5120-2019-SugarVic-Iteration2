import ReactDOM from 'react-dom';
import React from 'react';

import './admin/manage';
import './admin/modal';
import Layout from './admin/Layout';

let layoutContainer = document.getElementById( 'wprmprs-layout' );

if (layoutContainer) {
	ReactDOM.render(
		<Layout/>,
		layoutContainer
	);
}