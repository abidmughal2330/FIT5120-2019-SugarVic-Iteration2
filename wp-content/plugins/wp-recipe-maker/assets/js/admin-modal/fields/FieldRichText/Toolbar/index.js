import React, { Fragment } from 'react';

import Icon from '../../../../shared/Icon';
import ModalToolbar from '../../../general/toolbar';
import ButtonAction from './ButtonAction';
import ButtonCharacter from './ButtonCharacter';
import ButtonLink from './ButtonLink';
import ButtonMark from './ButtonMark';
import Spacer from './Spacer';
import ToolbarLink from './ToolbarLink';
import ToolbarSuggest from './ToolbarSuggest';

const Toolbar = (props) => {
	const hidden = {
		visibility: 'hidden'
	};

	let hideStyling = false;
	let hideLink = false;

	switch( props.type ) {
		case 'no-styling':
			hideStyling = true;
			hideLink = true;
			break;
		case 'ingredient':
			hideLink = true;
			break;
	}

	return (
		<ModalToolbar>
			{
				props.richText.hasInline('link')
				&&
				<ToolbarLink
					richText={ props.richText }
				/>
			}
			{
				'ingredient' === props.type
				&&
				<ToolbarSuggest
					richText={ props.richText }
					value={ props.value }
				/>
			}
			<div className="wprm-admin-modal-toolbar-buttons">
				<span
					style={ hideStyling ? hidden : null }
				>
					<ButtonMark richText={ props.richText } type="bold" title="Bold" />
					<ButtonMark richText={ props.richText } type="italic" title="Italic" />
					<ButtonMark richText={ props.richText } type="underline" title="Underline" />
					<Spacer />
					<ButtonMark richText={ props.richText } type="subscript" title="Subscript" />
					<ButtonMark richText={ props.richText } type="superscript" title="Superscript" />
				</span>
				<Spacer />
				<span
					style={ hideLink ? hidden : null }
				>
					<ButtonLink richText={ props.richText } />
				</span>
				<Spacer />
				<ButtonAction
					type="adjustable"
					title="Adjustable Shortcode"
					action={() => {
						props.richText.editor.wrapText( '[adjustable]', '[/adjustable]' );
						props.richText.editor.moveToEnd();
					}}
				/>
				<ButtonAction
					type="clock"
					title="Timer Shortcode"
					action={() => {
						props.richText.editor.wrapText( '[timer minutes=0]', '[/timer]' );
						props.richText.editor.moveToEnd();
					}}
				/>
				<Spacer />
				<ButtonCharacter richText={ props.richText } character="½" />
				<ButtonCharacter richText={ props.richText } character="⅓" />
				<ButtonCharacter richText={ props.richText } character="⅔" />
				<ButtonCharacter richText={ props.richText } character="¼" />
				<ButtonCharacter richText={ props.richText } character="¾" />
				<Spacer />
				<ButtonCharacter richText={ props.richText } character="°" />
			</div>
		</ModalToolbar>
	);
}
export default Toolbar;