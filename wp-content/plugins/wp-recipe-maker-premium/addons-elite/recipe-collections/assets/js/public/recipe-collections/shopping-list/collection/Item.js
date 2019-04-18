import React, { Component } from 'react';

import Icon from '../../general/Icon';

export default class Item extends Component {
    render() {
        const { item } = this.props;

        const changeServings = (e, plus) => {
            e.preventDefault();

            const servings = plus ? item.servings + 1 : item.servings - 1;
            this.props.onChangeServings(servings);

            return false;
        }

        return (
            <div className={`wprmprc-shopping-list-item${ item.servings <= 0 ? ' wprmprc-shopping-list-item-unused' : ''}`}>
                <div className="wprmprc-shopping-list-item-servings-adjust">
                    <div className="wprmprc-shopping-list-item-servings-adjust-minus" onClick={(e) => changeServings(e, false)}><Icon type="minus" /></div>
                    <div className="wprmprc-shopping-list-item-servings-adjust-servings-container">
                        <div className="wprmprc-shopping-list-item-servings-adjust-servings">{item.servings}</div>
                        {
                            item.servingsUnit
                            && <div className="wprmprc-shopping-list-item-servings-adjust-servings-unit">{item.servingsUnit}</div>
                        }
                    </div>
                    <div className="wprmprc-shopping-list-item-servings-adjust-plus" onClick={(e) => changeServings(e, true)}><Icon type="plus" /></div>
                </div>
                <div className="wprmprc-shopping-list-item-details">
                    <div className="wprmprc-shopping-list-item-name">{item.name}</div>
                    {
                        item.image
                        &&
                        <div className="wprmprc-shopping-list-item-image">
                            <img className="wprmprc-shopping-list-item-image" width="50" src={item.image} />
                        </div>
                    }
                </div>
            </div>
        );
    }
}