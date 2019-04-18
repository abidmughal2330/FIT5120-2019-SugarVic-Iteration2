import React, { Component, Fragment } from 'react';

import Api from '../../../general/Api';
import Loader from '../../../../shared/Loader';

export default class ToolbarSuggest extends Component {
    constructor(props) {
        super(props);

        this.state = {
			suggestions: [],
			loading: false,
		}
	}

	componentDidMount() {
		const search = this.props.richText.getHtmlFromValue( this.props.value );

		this.updateSuggestions( search );
	}

	componentDidUpdate(prevProps) {
		const search = this.props.richText.getHtmlFromValue( this.props.value );
		const prevSearch = this.props.richText.getHtmlFromValue( prevProps.value );

		if ( search !== prevSearch ) {
			this.updateSuggestions( search );
		}
	}
	
	updateSuggestions( search ) {
		this.setState({
			loading: true,
		});

		Api.getSuggestions({ search }).then(data => {
            if ( data ) {
				this.state.suggestions = data.suggestions;
				this.setState({
					suggestions: data.suggestions,
					loading: false,
				});
            }
        });
	}
  
    render() {
        return (
            <div className="wprm-admin-modal-toolbar-suggest">
				{
					! this.state.loading
					&& 0 === this.state.suggestions.length
					?
					<strong>No suggestions found.</strong>
					:
					<Fragment>
						<strong>Suggestions:</strong>
						{
							this.state.loading
							?
							<Loader/>
							:
							<Fragment>
								{
									this.state.suggestions.map((suggestion, index) => (
										<span
											className="wprm-admin-modal-toolbar-suggestion"
											onMouseDown={ (event) => {
												event.preventDefault();

												const newValue = this.props.richText.getValueFromHtml( suggestion.name );
												this.props.richText.onChange( { value: newValue } );
											} }
											key={ index }
										>
											<span className="wprm-admin-modal-toolbar-suggestion-text">{ suggestion.name } ({ suggestion.count})</span>
										</span>
									))
								}
							</Fragment>
						}
					</Fragment>
				}
			</div>
        );
    }
}
