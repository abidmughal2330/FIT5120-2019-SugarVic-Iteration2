import React from 'react';
 
const FieldText = (props) => {
    const time = parseInt( props.value );

    let days = 0,
        hours = 0,
        minutes = 0;

    if ( time > 0 ) {
        days = Math.floor( time / 24 / 60 );
        hours = Math.floor( time / 60 % 24 );
        minutes = Math.floor( time % 60 );
    }

    return (
        <div className="wprm-admin-modal-field-time">
            <input
                type="number"
                value={ days }
                onChange={ (e) => {
                    const newTime = 24 * 60 * parseInt( e.target.value ) + 60 * hours + minutes;
                    props.onChange( newTime );
                }}
            /> days 
            <input
                type="number"
                value={ hours }
                onChange={ (e) => {
                    const newTime = 24 * 60 * days + 60 * parseInt( e.target.value ) + minutes;
                    props.onChange( newTime );
                }}
            /> hours 
            <input
                type="number"
                value={ minutes }
                onChange={ (e) => {
                    const newTime = 24 * 60 * days + 60 * hours + parseInt( e.target.value );
                    props.onChange( newTime );
                }}
            /> minutes
        </div>
    );
}
export default FieldText;